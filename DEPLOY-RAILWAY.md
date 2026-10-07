# Panduan Deploy CDC BEDUSHUB ke Railway

Panduan ini menjelaskan cara men-deploy aplikasi **CDC BEDUSHUB** (Laravel 12 + Filament 3.3)
ke **Railway** sebagai environment **demo** untuk dosen/penguji.

> Karakteristik deploy ini bersifat **demo / ephemeral**:
> - **Tidak ada volume** — setiap deploy ulang, database di-`migrate:fresh --seed` sehingga
>   **data selalu bersih & baru** (perubahan data akan hilang saat redeploy).
> - **Auto-deploy** dari GitHub setiap kali push ke branch `main`.
> - Media yang diupload tidak persisten (hilang saat redeploy), sesuai `.clinerules/media.md`.

---

## 1. Ringkasan Arsitektur

| Komponen | Nilai |
|---|---|
| Build system | **Railpack** (`railpack.json`) — Railway **tidak lagi memakai Nixpacks** |
| PHP | **8.4** (otomatis dari `composer.json` `"php": "^8.4"`; image `dunglas/frankenphp:php8.4-trixie`) |
| Node.js | **20** (otomatis dari file `.nvmrc`) |
| Ekstensi PHP | otomatis dari entri `ext-*` di `composer.json` (`pdo_sqlite`, `sqlite3`, `gd`, `intl`, `mbstring`, `zip`, `curl`, `fileinfo`, `openssl`, `tokenizer`, `xml`, `ctype`, `pdo`) |
| Database | **SQLite** (`database/database.sqlite`, dibuat ulang setiap boot) |
| Web server | `php artisan serve --host=0.0.0.0 --port=$PORT` |
| Sesi / Cache / Queue | driver `database` (default) |
| Boot script | `start.sh` (dijalankan via `deploy.startCommand` di `railpack.json`) |
| Repo | `github.com/Rendy5234/cdc-BEDUSHUB` (branch `main`) |

File konfigurasi yang ditambahkan untuk deploy:

- `railpack.json` — konfigurasi build Railpack (menggantikan `nixpacks.toml`).
- `start.sh` — skrip boot (symlink storage, migrate:fresh --seed, cache, jalankan server).
- `.nvmrc` — memaksa Node.js versi 20 (Vite 6 + Tailwind 4 memerlukan ≥ 20).

> ⚠️ **Railway sekarang memakai build driver `railpack`** (terlihat di log: `using build driver
> railpack-v0.40.1`). File `nixpacks.toml` **sudah dihapus** karena diabaikan sepenuhnya oleh
> Railpack. Konfigurasi kini memakai `railpack.json`.

Perubahan pendukung:

- `composer.json` — menambahkan `ext-*` (cara Railpack memasang ekstensi PHP otomatis).
- `bootstrap/app.php` — `$middleware->trustProxies(at: '*')` agar skema HTTPS Railway terbaca
  (mencegah mixed-content / redirect loop).

---

## 2. Prasyarat

1. Akun [Railway](https://railway.app) (bisa login dengan GitHub).
2. Repo proyek sudah ada di GitHub (`Rendy5234/cdc-BEDUSHUB`, branch `main`).
3. Perubahan konfigurasi deploy (file di atas) sudah di-**commit & push** ke `main`.

```bash
git add railpack.json start.sh .nvmrc composer.json composer.lock bootstrap/app.php DEPLOY-RAILWAY.md
git commit -m "chore: siapkan konfigurasi deploy Railway (railpack + start.sh)"
git push origin main
```

---

## 3. Langkah Deploy di Railway

### 3.1 Buat Project & Deploy dari GitHub

1. Buka [railway.app/new](https://railway.app/new) → **Deploy from GitHub repo**.
2. Pilih repo `Rendy5234/cdc-BEDUSHUB`.
3. Railway akan otomatis mendeteksi Laravel dan mulai build memakai **Railpack**
   (`railpack.json` mengonfigurasi langkah build & start command).
4. Setelah service dibuat, buka **Service → Settings → Source**:
   - **Branch**: `main`
   - **Auto Deploy**: aktif (Railway akan build ulang tiap push ke `main`).

### 3.2 Set Variabel Environment (WAJIB)

Buka **Service → Variables → Raw Editor** lalu tempel:

```
APP_NAME="CDC BEDUSHUB"
APP_ENV=production
APP_DEBUG=false
APP_KEY=base64:GANTI_DENGAN_KEY_ANDA
APP_URL=https://GANTI-DENGAN-DOMAIN.up.railway.app
LOG_CHANNEL=stderr
DB_CONNECTION=sqlite
FILESYSTEM_DISK=public
SESSION_DRIVER=database
SESSION_SECURE_COOKIE=true
CACHE_STORE=database
QUEUE_CONNECTION=database
PHP_CLI_SERVER_WORKERS=4
```

> **PENTING — PORT tidak perlu diset.** Railway menyuntikkan `PORT` otomatis dan `start.sh`
> memakainya (`${PORT:-8080}`). Jangan override `PORT`.

**Cara membuat `APP_KEY`:**

```bash
php artisan key:generate --show
```

Salin hasilnya (format `base64:...`) ke nilai `APP_KEY` di Railway.

### 3.3 Buat Domain Publik

1. Buka **Service → Settings → Networking → Public Networking → Generate Domain**.
2. Salin URL yang muncul (mis. `https://cdc-bedushub-production.up.railway.app`).
3. Kembali ke **Variables**, perbarui `APP_URL` agar **sama persis** dengan URL tersebut
   (termasuk `https://`, tanpa trailing slash).
4. Railway akan otomatis redeploy setelah variabel diubah.

---

## 4. Tabel Variabel Environment

| Variabel | Nilai untuk Demo | Keterangan |
|---|---|---|
| `APP_NAME` | `CDC BEDUSHUB` | Nama aplikasi |
| `APP_ENV` | `production` | Mode produksi |
| `APP_DEBUG` | `false` | Jangan tampilkan error detail ke publik |
| `APP_KEY` | `base64:...` | **Wajib**, dari `php artisan key:generate --show` |
| `APP_URL` | `https://<domain>.up.railway.app` | **Harus** sama dengan domain publik Railway |
| `LOG_CHANNEL` | `stderr` | Log tampil di Railway logs |
| `DB_CONNECTION` | `sqlite` | Database SQLite (file, ephemeral) |
| `FILESYSTEM_DISK` | `public` | Semua media disajikan dari disk `public` |
| `SESSION_DRIVER` | `database` | Sesi via tabel `sessions` |
| `SESSION_SECURE_COOKIE` | `true` | Cookie hanya lewat HTTPS (domain Railway HTTPS) |
| `CACHE_STORE` | `database` | Cache via tabel `cache` |
| `QUEUE_CONNECTION` | `database` | Queue via tabel `jobs` |
| `PHP_CLI_SERVER_WORKERS` | `4` | Concurrency `php artisan serve` |

> Variabel di atas **tidak** disimpan di repo (`.env` di-gitignore). Semuanya diisi lewat
> Dashboard Railway. Nilai default `config/*.php` sudah cocok, jadi minimal `APP_KEY` +
> `APP_URL` + `APP_ENV` + `APP_DEBUG` + `LOG_CHANNEL` yang benar-benar penting.


---

## 5. Apa yang Terjadi Saat Boot

Saat **build**, Railpack menjalankan step berikut secara berurutan:

1. `install:composer` → `composer install` (otomatis oleh provider PHP).
2. `install:node` → `npm install` (otomatis oleh provider Node; Node 20 dari `.nvmrc`).
3. `prune:node` → membuang dev dependency frontend.
4. `build` → **hanya 2 hal**: menjalankan `npm run build` (menghasilkan `public/build`)
   lalu menyiapkan folder `storage/*`, `bootstrap/cache`, `database` + file
   `database/database.sqlite` dan mengatur izin tulis.

> Mengapa `build` di-override? Secara default provider PHP Railpack menambahkan
> `php artisan config:cache`, `event:cache`, `route:cache`, `view:cache` ke step `build`.
> Perintah itu **gagal di build-time** karena belum ada `.env`/`APP_KEY` dan database.
> `railpack.json` **mengganti** daftar perintah step `build` (menghapus 4 perintah artisan
> tersebut), lalu caching dipindah ke **runtime** lewat `start.sh`.
>
> Catatan: `composer install` & `npm install` **tidak** diulang di step `build` — keduanya
> sudah dijalankan di step `install:composer` & `install:node` (layer-nya menjadi input step
> `build`), sehingga `vendor/` dan `node_modules/` sudah tersedia saat `npm run build` berjalan.

Urutan yang dijalankan `start.sh` setiap kontainer hidup:

1. Membuat folder `storage/*` & `bootstrap/cache`, serta file `database/database.sqlite`.
2. `php artisan storage:link` → membuat symlink `public/storage → storage/app/public`.
3. `php artisan migrate:fresh --seed --force` → **drop semua tabel + migrate + seed** data demo.
4. `php artisan optimize:clear` → bersihkan cache lama.
5. `php artisan config:cache`, `route:cache`, `view:cache` → cache untuk performa.
6. `php artisan serve --host=0.0.0.0 --port=$PORT` → menjalankan web server.

Karena tidak ada volume, langkah 3 selalu menghasilkan data demo yang bersih setiap redeploy.

---

## 6. Akun Demo

Semua akun memakai password: **`password`**

| Peran | Email | Panel | URL |
|---|---|---|---|
| Admin CDC | `admin@bedushub.test` | Admin | `/admin` |
| Perusahaan | `perusahaan@bedushub.test` (juga `perusahaan2..4@`) | Perusahaan | `/perusahaan` |
| Siswa (SMK) | `siswa@bedushub.test` (juga `siswa2..4@`) | Siswa | `/dashboard` |
| Mahasiswa | `mahasiswa@bedushub.test` (juga `mahasiswa2..4@`) | Siswa | `/dashboard` |
| Alumni | `alumni@bedushub.test` (juga `alumni2..4@`) | Siswa | `/dashboard` |

> Halaman beranda (`/`) adalah halaman `welcome` bawaan Laravel.

---

## 7. Verifikasi Setelah Deploy

1. Buka **Railway → Deployments → Logs**; pastikan build sukses dan log berakhir dengan
   `Server running on [http://0.0.0.0:<port>]` serta tidak ada error seeder.
2. Buka `https://<domain>/up` → harus mengembalikan health check `200 OK`.
3. Buka `https://<domain>/admin` → halaman login Filament Admin tampil.
4. Login dengan `admin@bedushub.test` / `password` → dashboard tampil normal.
5. Cek panel lain: `/dashboard` (siswa) dan `/perusahaan`.

### Troubleshooting Umum

| Gejala | Penyebab | Solusi |
|---|---|---|
| Build gagal saat `php artisan config:cache`/`route:cache`/`view:cache` | Railpack menjalankan cache Laravel di **build time** (tanpa `.env`/`APP_KEY`/DB) | `railpack.json` **mengganti** blok build sehingga cache dijalankan saat **runtime** (`start.sh`), bukan build |
| Build gagal: ekstensi PHP hilang | `ext-*` tidak dikenali Railpack/FrankenPHP | Sudah otomatis dari `composer.json`; tambah lewat `RAILPACK_PHP_EXTENSIONS` bila perlu |
| Build gagal `composer install ... Your lock file does not contain a compatible set of packages` | Versi PHP Railpack **lebih rendah** dari yang dibutuhkan `composer.lock` (mis. lock dibuat di PHP 8.4 berisi `symfony/* v8` yang butuh `>=8.4.1`, `openspout v4.32` butuh `~8.3/8.4`) | Selaraskan `"php"` di `composer.json` dengan versi PHP saat `composer.lock` dibuat (kini `^8.4`). Railpack membaca `composer.json > require > php`; hanya prefix `^` yang dibuang → `^8.4` menjadi `8.4` |
| `No application encryption key has been specified` | `APP_KEY` belum diset | Set `APP_KEY` di Variables Railway |
| Halaman tampil tanpa CSS / mixed-content | `APP_URL` beda dengan domain / proxy tidak dipercaya | Samakan `APP_URL`; `trustProxies` sudah diaktifkan |
| Aset Vite 404 (`/build/manifest.json`) | `npm run build` tidak jalan | Pastikan `package.json` punya script `build` (sudah ada) & `railpack.json` masih memuat `npm run build` |
| Upload gambar gagal | Batas `upload_max_filesize` PHP | Turunkan `maxSize` Filament atau naikkan limit (lihat `.clinerules/media.md` §7) |
| Data hilang setelah redeploy | Tidak ada volume (memang by design) | Segala perubahan data tidak persisten — intended untuk demo |

---

## 8. Update & Redeploy

- **Auto**: setiap `git push origin main` → Railway build & deploy ulang otomatis, lalu
  menjalankan `migrate:fresh --seed` lagi (data demo kembali bersih).
- **Manual**: **Service → Deployments → Redeploy**.
- Mengubah variabel environment otomatis memicu redeploy.

---

## 9. Catatan Penting

1. **Data & media tidak persisten** (demo). Jangan pakai untuk menyimpan data penting.
2. `SESSION_SECURE_COOKIE=true` **memerlukan HTTPS**. Domain Railway selalu HTTPS, jadi aman.
   Jika mengetes via `http://localhost` secara lokal, set `false` agar login bekerja.
3. Semua media memakai disk `public` secara eksplisit (lihat `.clinerules/media.md`), sehingga
   `FILESYSTEM_DISK=public` konsisten dengan kode.
4. Untuk lingkungan produksi sesungguhnya, gunakan **database terkelola** (PostgreSQL/MySQL)
   + volume/object storage, dan **hapus** `migrate:fresh` dari boot (ganti `migrate --force`).

---

*Panduan ini dibuat mengikuti kondisi kode saat ini. Perbarui bila mengubah `railpack.json`,
`start.sh`, variabel, atau strategi penyimpanan.*