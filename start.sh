#!/usr/bin/env bash
#
# =============================================================================
#  start.sh — Skrip boot aplikasi CDC BEDUSHUB di Railway (container ephemeral)
# =============================================================================
#  Dijalankan oleh Nixpacks (lihat [start].cmd di nixpacks.toml).
#
#  Alur:
#   1. Siapkan file database SQLite (fresh setiap deploy — tidak ada volume).
#   2. Buat symlink public/storage -> storage/app/public.
#   3. migrate:fresh --seed  (data demo selalu bersih & baru).
#   4. Bersihkan cache lama, lalu cache config/route/view untuk performa.
#   5. Jalankan php artisan serve sebagai web server.
#
#  PORT: disediakan otomatis oleh Railway (fallback 8080 untuk lokal).
# =============================================================================

set -e

echo "==> [1/5] Menyiapkan file database SQLite..."
mkdir -p database
mkdir -p storage/app/public
mkdir -p storage/framework/cache
mkdir -p storage/framework/sessions
mkdir -p storage/framework/views
mkdir -p storage/logs
mkdir -p bootstrap/cache
touch database/database.sqlite

echo "==> [2/5] Membuat symlink storage (public/storage -> storage/app/public)..."
php artisan storage:link || true

echo "==> [3/5] Migrasi ulang & seed data demo (migrate:fresh --seed)..."
php artisan migrate:fresh --seed --force

echo "==> [4/5] Membuat cache config/route/view..."
php artisan optimize:clear
php artisan config:cache
php artisan route:cache
php artisan view:cache

echo "==> [5/5] Menjalankan web server pada port ${PORT:-8080}..."
exec php artisan serve --host=0.0.0.0 --port="${PORT:-8080}"