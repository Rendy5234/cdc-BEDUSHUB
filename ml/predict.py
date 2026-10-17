#!/usr/bin/env python3
"""
Prediction script untuk ALS model.
Prototipe BEDUSHUB - Fase B: Python Prediction
Usage: python predict.py --user_id=6 --item_type=lowongan --top_k=5
Output: JSON array to stdout
"""

import os
import sys
import json
import pickle
import argparse
import numpy as np


def load_artifacts(base_dir: str):
    """Load model.npz, mappings.pkl, and metrics.json."""
    model_dir = os.path.join(base_dir, "model")

    # Load mappings
    mappings_path = os.path.join(model_dir, "mappings.pkl")
    if not os.path.exists(mappings_path):
        raise FileNotFoundError(f"Mappings not found: {mappings_path}")
    with open(mappings_path, "rb") as f:
        mappings = pickle.load(f)

    # Load model
    model_path = os.path.join(model_dir, "model.npz")
    if not os.path.exists(model_path):
        raise FileNotFoundError(f"Model not found: {model_path}")

    # Use the CPU ALS class directly for loading (not the factory function)
    from implicit.cpu.als import AlternatingLeastSquares
    model = AlternatingLeastSquares.load(model_path)

    return model, mappings


def predict(model, mappings: dict, user_id: int, item_type: str, top_k: int = 5):
    """Generate recommendations for a user filtered by item_type."""
    # Check if user exists in mappings (keys might be numpy int64)
    user_to_idx = mappings.get("user_to_idx", {})
    # Normalize keys to int for comparison
    user_to_idx_norm = {int(k): v for k, v in user_to_idx.items()}
    if user_id not in user_to_idx_norm:
        return []

    u = user_to_idx_norm[user_id]

    # Get item indices for the requested item_type
    item_type_to_idx = mappings.get("item_type_to_idx", {})
    valid_item_indices = set(item_type_to_idx.get(item_type, []))
    if not valid_item_indices:
        return []

    idx_to_item = mappings.get("idx_to_item", {})

    # Get user vector (we need the full matrix for recommend)
    # Since we don't have the full train matrix here, we use the item factors directly
    # implicit's recommend needs user_items matrix, but we can approximate
    # by scoring all valid items manually
    user_factors = model.user_factors[u]
    item_factors = model.item_factors

    # Score only items of the requested type
    scores = []
    for idx in valid_item_indices:
        if idx < item_factors.shape[0]:
            score = float(np.dot(user_factors, item_factors[idx]))
            item_info = idx_to_item.get(idx, {})
            if item_info.get("type") == item_type:
                scores.append((item_info["id"], score))

    # Sort by score descending
    scores.sort(key=lambda x: x[1], reverse=True)

    # Take top_k
    top_scores = scores[:top_k]

    # Format output
    results = []
    for rank, (item_id, score) in enumerate(top_scores, 1):
        results.append({
            "item_id": int(item_id),
            "score": float(score),
            "rank": rank
        })

    return results


def main():
    parser = argparse.ArgumentParser(description="ALS Prediction for BEDUSHUB")
    parser.add_argument("--user_id", type=int, required=True, help="User ID (6-33 for students)")
    parser.add_argument("--item_type", type=str, required=True,
                        help="Item type to recommend (lowongan or pelatihan)")
    parser.add_argument("--top_k", type=int, default=5, help="Number of recommendations")
    args = parser.parse_args()

    base_dir = os.path.dirname(os.path.abspath(__file__))

    try:
        model, mappings = load_artifacts(base_dir)
        results = predict(model, mappings, args.user_id, args.item_type, args.top_k)
        # Output JSON to stdout
        print(json.dumps(results, ensure_ascii=False))
    except Exception as e:
        # On any error, output empty array
        print(json.dumps([]))
        sys.exit(0)


if __name__ == "__main__":
    main()