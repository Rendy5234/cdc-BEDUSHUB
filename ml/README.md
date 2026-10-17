# ML Prototipe - BEDUSHUB

## Install
```bash
pip install -r requirements.txt
```

## Training
```bash
python train.py
```
Output:
- `model/model.npz` - Model ALS
- `model/mappings.pkl` - Mapping user/item
- `model/metrics.json` - Metrics evaluasi

## Predict
```bash
python predict.py --user_id=6 --item_type=lowongan --top_k=5
```
Output JSON ke stdout:
```json
[{"item_id": 5, "score": 0.85, "rank": 1}, ...]
```

## Catatan
- User ID 6-33 adalah siswa/mahasiswa/alumni (data training)
- User ID 1-5 adalah admin/perusahaan (tidak di-train)
- Item type: `lowongan` atau `pelatihan`