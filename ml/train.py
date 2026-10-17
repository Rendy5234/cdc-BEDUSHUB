#!/usr/bin/env python3
"""
Training script untuk ALS (Alternating Least Squares) menggunakan implicit.
Prototipe BEDUSHUB - Fase B: Python Training
"""

import os
import json
import pickle
import numpy as np
import pandas as pd
from scipy.sparse import csr_matrix
from implicit.als import AlternatingLeastSquares
from tqdm import tqdm


def load_data(base_dir: str) -> pd.DataFrame:
    """Load interactions CSV."""
    path = os.path.join(base_dir, "training_data", "interactions.csv")
    df = pd.read_csv(path)
    print(f"Loaded {len(df)} interactions")
    print(f"Unique users: {df['user_id'].nunique()}")
    print(f"Unique items (item_type + item_id): {df.groupby(['item_type', 'item_id']).ngroups}")
    return df


def build_mappings(df: pd.DataFrame):
    """Build user_id -> internal_idx and (item_type, item_id) -> internal_idx mappings."""
    # User mapping
    unique_users = sorted(df["user_id"].unique())
    user_to_idx = {u: i for i, u in enumerate(unique_users)}
    idx_to_user = {i: u for u, i in user_to_idx.items()}

    # Item mapping: combined key (item_type, item_id)
    unique_items = sorted(df.groupby(["item_type", "item_id"]).groups.keys())
    item_to_idx = {item: i for i, item in enumerate(unique_items)}
    idx_to_item = {i: {"type": item[0], "id": item[1]} for item, i in item_to_idx.items()}

    # Item type mapping
    item_type_to_idx = {}
    for (itype, iid), idx in item_to_idx.items():
        item_type_to_idx.setdefault(itype, []).append(idx)

    print(f"User mapping: {len(user_to_idx)} users")
    print(f"Item mapping: {len(item_to_idx)} items")
    print(f"Item types: {list(item_type_to_idx.keys())}")

    return {
        "user_to_idx": user_to_idx,
        "idx_to_user": idx_to_user,
        "item_to_idx": item_to_idx,
        "idx_to_item": idx_to_item,
        "item_type_to_idx": item_type_to_idx,
    }
def build_confidence_matrix(df: pd.DataFrame, mappings: dict) -> csr_matrix:
    """Build user-item confidence matrix from interactions."""
    n_users = len(mappings["user_to_idx"])
    n_items = len(mappings["item_to_idx"])

    rows = []
    cols = []
    data = []

    for _, row in tqdm(df.iterrows(), total=len(df), desc="Building matrix"):
        u = mappings["user_to_idx"].get(row["user_id"])
        item_key = (row["item_type"], row["item_id"])
        i = mappings["item_to_idx"].get(item_key)
        if u is None or i is None:
            continue

        confidence = 1.0 if row["label"] == 1 else 0.1

        rows.append(u)
        cols.append(i)
        data.append(confidence)

    matrix = csr_matrix((data, (rows, cols)), shape=(n_users, n_items), dtype=np.float32)
    print(f"Confidence matrix: {matrix.shape}, nnz: {matrix.nnz}")
    return matrix


def split_train_test(
    df: pd.DataFrame, test_ratio: float = 0.2, random_state: int = 42
) -> tuple[pd.DataFrame, pd.DataFrame]:
    """Hold-out PER USER: sisihkan sebagian interaksi tiap user sebagai test.

    Mengapa bukan temporal split (versi lama):
    CSV ini berisi pasangan unik (user x item). Pada split temporal, 0% test pair
    pernah muncul di train, sehingga ALS tidak punya konteks apa pun untuk
    memprediksinya -> metrik jatuh ke level random.

    Mengapa bukan random per-pair:
    Karena pasangan bersifat unik, random per-pair membuat test pair SELALU ada
    di train. Dengan filter_already_liked_items=True seluruh test pair akan
    difilter keluar -> Precision@5 = 0.0000.

    Hold-out per-user menghindari keduanya: target test belum pernah disentuh
    user tersebut, tetapi itemnya tetap ada di katalog dari interaksi user lain.
    Inilah setup collaborative filtering yang sesungguhnya.
    """
    train_parts = []
    test_parts = []

    for _, group in df.groupby("user_id"):
        group = group.sample(frac=1, random_state=random_state)
        n_test = max(1, int(round(len(group) * test_ratio)))
        # Pastikan tiap user tetap menyisakan data di train.
        n_test = min(n_test, len(group) - 1) if len(group) > 1 else 0

        test_parts.append(group.iloc[:n_test])
        train_parts.append(group.iloc[n_test:])

    train_df = pd.concat(train_parts).copy() if train_parts else df.iloc[0:0].copy()
    test_df = pd.concat(test_parts).copy() if test_parts else df.iloc[0:0].copy()

    # Acak urutan baris agar tidak berkelompok per user.
    train_df = train_df.sample(frac=1, random_state=random_state).reset_index(drop=True)
    test_df = test_df.sample(frac=1, random_state=random_state).reset_index(drop=True)

    print(f"Train: {len(train_df)}, Test: {len(test_df)}")
    print(f"Users in train: {train_df['user_id'].nunique()}, in test: {test_df['user_id'].nunique()}")
    return train_df, test_df
def compute_metrics(model, train_matrix: csr_matrix, test_df: pd.DataFrame, mappings: dict, k: int = 5):
    """Compute Precision@K, Recall@K, NDCG@K on test set."""
    from scipy.sparse import csr_matrix

    # Build test set per user: {user_idx: set of positive item_idx}
    test_pos = {}
    for _, row in test_df.iterrows():
        if row["label"] != 1:
            continue
        u = mappings["user_to_idx"].get(row["user_id"])
        item_key = (row["item_type"], row["item_id"])
        i = mappings["item_to_idx"].get(item_key)
        if u is not None and i is not None:
            test_pos.setdefault(u, set()).add(i)

    if not test_pos:
        return {"precision@5": 0.0, "recall@5": 0.0, "ndcg@5": 0.0}

    precisions = []
    recalls = []
    ndcgs = []

    for u, pos_items in test_pos.items():
        if not pos_items:
            continue

        # Get recommendations for user u
        user_vector = train_matrix[u]
        # filter_already_liked_items=True WAJIB: pada data implicit-feedback, item
        # yang sudah di-like selalu menang skor (terbukti 450/450 alias 100% top-5
        # tanpa filter adalah item lama). Tanpa filter, target test yang benar-benar
        # baru tidak akan pernah muncul dan metrik jadi 0.0000.
        rec_indices, rec_scores = model.recommend(
            u, user_vector, N=k, filter_already_liked_items=True, recalculate_user=True
        )
        rec_items = list(rec_indices)

        # Precision@K
        hits = len(set(rec_items) & pos_items)
        precisions.append(hits / k)

        # Recall@K
        recalls.append(hits / len(pos_items))

        # NDCG@K
        dcg = 0.0
        for rank, item in enumerate(rec_items, 1):
            if item in pos_items:
                dcg += 1.0 / np.log2(rank + 1)
        # Ideal DCG
        ideal_hits = min(len(pos_items), k)
        idcg = sum(1.0 / np.log2(r + 1) for r in range(1, ideal_hits + 1))
        ndcgs.append(dcg / idcg if idcg > 0 else 0.0)

    return {
        "precision@5": float(np.mean(precisions)) if precisions else 0.0,
        "recall@5": float(np.mean(recalls)) if recalls else 0.0,
        "ndcg@5": float(np.mean(ndcgs)) if ndcgs else 0.0,
    }


def compute_baselines(train_df: pd.DataFrame, test_df: pd.DataFrame, mappings: dict, k: int = 5):
    """Baseline pembanding pada setup hold-out yang sama.

    Berguna untuk menilai apakah ALS benar-benar belajar, atau hanya menang
    karena katalognya kecil. Random = acak 5 item; popularity = 5 item paling
    banyak mendapat label positif di train.
    """
    import random

    n_items = len(mappings["item_to_idx"])
    if n_items == 0:
        return {}

    test_pos = {}
    for _, row in test_df.iterrows():
        if row["label"] != 1:
            continue
        u = mappings["user_to_idx"].get(row["user_id"])
        item_key = (row["item_type"], row["item_id"])
        i = mappings["item_to_idx"].get(item_key)
        if u is not None and i is not None:
            test_pos.setdefault(u, set()).add(i)

    if not test_pos:
        return {}

    # Item terpopuler di train berdasarkan interaksi positif.
    pop_counts = (
        train_df[train_df["label"] == 1]
        .groupby(["item_type", "item_id"])
        .size()
        .sort_values(ascending=False)
    )
    pop_top = [
        mappings["item_to_idx"][key]
        for key in pop_counts.index
        if key in mappings["item_to_idx"]
    ][:k]

    rng = random.Random(42)
    all_items = list(range(n_items))
    rand_hits = pop_hits = 0
    n_eval = 0

    for u, pos_items in test_pos.items():
        n_eval += 1
        rand_hits += len(set(rng.sample(all_items, min(k, n_items))) & pos_items)
        pop_hits += len(set(pop_top) & pos_items)

    return {
        "random@5": float(rand_hits / (n_eval * k)) if n_eval else 0.0,
        "popularity@5": float(pop_hits / (n_eval * k)) if n_eval else 0.0,
        "n_users_evaluated": n_eval,
    }


def train_als(train_matrix: csr_matrix, factors: int = 32, regularization: float = 0.1, iterations: int = 20):
    """Train ALS model."""
    model = AlternatingLeastSquares(
        factors=factors,
        regularization=regularization,
        iterations=iterations,
        calculate_training_loss=False,
        random_state=42,
    )
    print(f"Training ALS: factors={factors}, regularization={regularization}, iterations={iterations}")
    model.fit(train_matrix, show_progress=True)
    return model


def save_artifacts(model, mappings: dict, metrics: dict, base_dir: str):
    """Save model, mappings, and metrics."""
    model_dir = os.path.join(base_dir, "model")
    os.makedirs(model_dir, exist_ok=True)

    # Save model.npz
    model_path = os.path.join(model_dir, "model.npz")
    model.save(model_path)
    print(f"Saved model to {model_path}")

    # Save mappings.pkl
    mappings_path = os.path.join(model_dir, "mappings.pkl")
    with open(mappings_path, "wb") as f:
        pickle.dump(mappings, f)
    print(f"Saved mappings to {mappings_path}")

    # Save metrics.json
    metrics_path = os.path.join(model_dir, "metrics.json")
    with open(metrics_path, "w") as f:
        json.dump(metrics, f, indent=2)
    print(f"Saved metrics to {metrics_path}")


def main():
    base_dir = os.path.dirname(os.path.abspath(__file__))

    print("=" * 50)
    print("BEDUSHUB - ALS Training (Fase B)")
    print("=" * 50)

    # Load data
    df = load_data(base_dir)

    # Split train/test per user (hold-out)
    train_df, test_df = split_train_test(df, test_ratio=0.2)

    # Mappings dibangun dari SELURUH df agar item yang hanya muncul di test tetap
    # punya indeks. Data leakage tetap dicegah karena model hanya dilatih dari
    # train_matrix (bukan dari baris test).
    mappings = build_mappings(df)

    # Build confidence matrix from train
    train_matrix = build_confidence_matrix(train_df, mappings)

    # Train ALS
    model = train_als(train_matrix)

    # Evaluate on test set
    print("\nEvaluating on test set...")
    metrics = compute_metrics(model, train_matrix, test_df, mappings, k=5)

    # Baseline pembanding pada setup yang sama
    print("Computing baselines (random & popularity)...")
    baselines = compute_baselines(train_df, test_df, mappings, k=5)
    metrics.update(baselines)

    # Add metadata
    metrics["n_users"] = len(mappings["user_to_idx"])
    metrics["n_items"] = len(mappings["item_to_idx"])
    metrics["n_interactions_train"] = int(train_matrix.nnz)
    metrics["n_interactions_test"] = len(test_df)

    print("\n" + "=" * 50)
    print("TRAINING RESULTS")
    print("=" * 50)
    print(f"Users: {metrics['n_users']}")
    print(f"Items: {metrics['n_items']}")
    print(f"Train interactions (nnz): {metrics['n_interactions_train']}")
    print(f"Test interactions: {metrics['n_interactions_test']}")
    print(f"Precision@5: {metrics['precision@5']:.4f}")
    print(f"Recall@5:    {metrics['recall@5']:.4f}")
    print(f"NDCG@5:      {metrics['ndcg@5']:.4f}")
    if "random@5" in metrics:
        print("-" * 50)
        print("BASELINE PEMBANDING (setup sama)")
        print(f"Random@5:      {metrics['random@5']:.4f}")
        print(f"Popularity@5:  {metrics['popularity@5']:.4f}")
        delta = metrics["precision@5"] - metrics["popularity@5"]
        print(f"ALS vs popularity: {delta:+.4f}")
    print("=" * 50)

    # Save artifacts
    save_artifacts(model, mappings, metrics, base_dir)

    print("\nTraining completed successfully!")


if __name__ == "__main__":
    main()