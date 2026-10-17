#!/usr/bin/env python3
"""
Diagnosis script untuk Fase B - mengapa metrics ALS rendah?
- Overlap user/item train-test
- Distribusi test interactions
- Random baseline Precision@5
"""

import os
import pandas as pd
import numpy as np


def load_data(base_dir: str) -> pd.DataFrame:
    """Load interactions CSV."""
    path = os.path.join(base_dir, "training_data", "interactions.csv")
    return pd.read_csv(path)


def split_train_test(df: pd.DataFrame, test_ratio: float = 0.2):
    """Split by time (event_at) - 80% train, 20% test."""
    df_sorted = df.sort_values("event_at")
    split_idx = int(len(df_sorted) * (1 - test_ratio))
    return df_sorted.iloc[:split_idx].copy(), df_sorted.iloc[split_idx:].copy()


def diagnose(base_dir: str):
    """Run full diagnosis report."""
    df = load_data(base_dir)
    train_df, test_df = split_train_test(df, test_ratio=0.2)

    # --- Overlap User ---
    train_users = set(train_df["user_id"].unique())
    test_users = set(test_df["user_id"].unique())
    both_users = train_users & test_users
    test_only_users = test_users - train_users

    print("=" * 60)
    print("DIAGNOSIS LAPORAN - Fase B ALS Metrics")
    print("=" * 60)

    print("\n--- OVERLAP USER TRAIN/TEST ---")
    print(f"Total unique users (full data): {df['user_id'].nunique()}")
    print(f"Users in TRAINING: {len(train_users)}")
    print(f"Users in TEST: {len(test_users)}")
    print(f"Users in BOTH (overlap): {len(both_users)}")
    print(f"Users ONLY in test: {len(test_only_users)}")
    if test_only_users:
        print(f"  -> {sorted(test_only_users)}")
    pct_overlap = len(both_users) / len(test_users) * 100 if test_users else 0
    print(f"  -> {pct_overlap:.1f}% test users are in training set")

    # --- Overlap Item ---
    train_items = set(train_df.groupby(["item_type", "item_id"]).groups.keys())
    test_items = set(test_df.groupby(["item_type", "item_id"]).groups.keys())
    both_items = train_items & test_items
    test_only_items = test_items - train_items

    print("\n--- OVERLAP ITEM TRAIN/TEST ---")
    print(f"Items in TRAINING: {len(train_items)}")
    print(f"Items in TEST: {len(test_items)}")
    print(f"Items in BOTH (overlap): {len(both_items)}")
    print(f"Items ONLY in test: {len(test_only_items)}")
    pct_item_overlap = len(both_items) / len(test_items) * 100 if test_items else 0
    print(f"  -> {pct_item_overlap:.1f}% test items are in training set")

    # --- Distribusi Test Interactions ---
    print("\n--- DISTRIBUSI TEST INTERACTIONS ---")
    test_user_counts = test_df.groupby("user_id").size()
    print(f"Total test interactions: {len(test_df)}")
    print(f"Test users with interactions: {len(test_user_counts)}")
    print(f"Average interactions per test user: {test_user_counts.mean():.2f}")
    print(f"Median: {test_user_counts.median():.2f}")
    print(f"Min/Max per user: {test_user_counts.min()}/{test_user_counts.max()}")
    users_lt2 = (test_user_counts < 2).sum()
    print(f"Test users with < 2 interactions: {users_lt2}")

    # --- Label distribution ---
    print("\n--- LABEL DISTRIBUTION ---")
    print(f"Full data label=1: {int(df['label'].sum())} ({df['label'].mean()*100:.1f}%)")
    print(f"Train label=1: {int(train_df['label'].sum())} ({train_df['label'].mean()*100:.1f}%)")
    print(f"Test label=1: {int(test_df['label'].sum())} ({test_df['label'].mean()*100:.1f}%)")

    # --- Random Baseline Precision@5 ---
    print("\n--- RANDOM BASELINE (Precision@5) ---")
    test_positive_items = test_df[test_df["label"] == 1].groupby("user_id").apply(
        lambda g: set(g.groupby(["item_type", "item_id"]).groups.keys())
    ).to_dict()

    all_items = train_items | test_items
    all_item_list = list(all_items)

    precisions = []
    np.random.seed(42)
    for user_id, pos_items in test_positive_items.items():
        if len(pos_items) == 0:
            continue
        indices = np.random.randint(0, len(all_item_list), size=min(5, len(all_item_list)))
        random_items = set(all_item_list[i] for i in indices)
        hits = len(pos_items & random_items)
        precisions.append(hits / 5.0)

    avg_random_p5 = np.mean(precisions) if precisions else 0
    print(f"Evaluable test users (with positive labels): {len(precisions)}")
    print(f"Random Precision@5: {avg_random_p5:.4f}")

    # --- Current Model Metrics ---
    # Dibaca dinamis dari model/metrics.json supaya tidak perlu hardcode ulang.
    import json

    metrics_path = os.path.join(base_dir, "model", "metrics.json")
    model_p5 = None
    model_r5 = None
    model_n5 = None
    if os.path.exists(metrics_path):
        with open(metrics_path, "r") as f:
            m = json.load(f)
        model_p5 = m.get("precision@5")
        model_r5 = m.get("recall@5")
        model_n5 = m.get("ndcg@5")

    print("\n--- CURRENT MODEL METRICS ---")
    if model_p5 is not None:
        print(f"Precision@5: {model_p5:.4f}")
        print(f"Recall@5:    {model_r5:.4f}")
        print(f"NDCG@5:      {model_n5:.4f}")
    else:
        print("(metrics.json belum ada - jalankan train.py dulu)")
    print(f"Random P@5:  {avg_random_p5:.4f}")
    if model_p5 is not None:
        print(f"\nModel is BELOW random baseline: {model_p5 < avg_random_p5}")
        print(f"Model vs Random: {model_p5 - avg_random_p5:+.4f}")

    # --- Key Issues Summary ---
    print("\n" + "=" * 60)
    print("KESIMPULAN DIAGNOSIS")
    print("=" * 60)
    issues = []
    if pct_overlap < 30:
        issues.append(
            f"- OVERLAP USER RENDAH ({pct_overlap:.1f}%): banyak user test tidak ada di train"
        )
    if pct_item_overlap < 30:
        issues.append(
            f"- OVERLAP ITEM RENDAH ({pct_item_overlap:.1f}%): model kurang generalize"
        )
    if users_lt2 > 0:
        issues.append(
            f"- TEST USERS <2 INTERACTIONS: {users_lt2} user sulit di-evaluasi"
        )
    if model_p5 is not None and model_p5 < avg_random_p5:
        issues.append(
            f"- MODEL DI BAWAH RANDOM ({avg_random_p5:.4f}): "
            "belum belajar pola yang berguna"
        )

    if issues:
        for issue in issues:
            print(f"  {issue}")
    else:
        print("  Semua cek passed — masalah mungkin di confidence weight atau hyperparameters")

    print("\n" + "=" * 60)


if __name__ == "__main__":
    base_dir = os.path.dirname(os.path.abspath(__file__))
    diagnose(base_dir)
