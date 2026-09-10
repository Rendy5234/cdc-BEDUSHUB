<?php

namespace Database\Seeders;

use App\Models\Asesmen;
use App\Models\AsesmenJawaban;
use App\Models\AsesmenSoal;
use App\Models\Lamaran;
use App\Models\Lowongan;
use App\Models\Notifikasi;
use App\Models\Pelatihan;
use App\Models\PendaftaranPelatihan;
use App\Models\PotensiDaerah;
use App\Models\ProgramStudi;
use App\Models\RekomendasiLog;
use App\Models\TracerStudy;
use App\Models\User;
use Illuminate\Database\Seeder;

class TransactionSeeder extends Seeder
{
    public function run(): void
    {
        $this->lamarans();
        $this->pendaftaranPelatihans();
        $this->asesmenJawabans();
        $this->tracerStudies();
        $this->rekomendasiLogs();
        $this->notifikasis();
    }

    private function lamarans(): void
    {
        $data = [
            ['Backend Developer (PHP/Laravel)', 'siswa@bedushub.test', 'diproses'],
            ['Backend Developer (PHP/Laravel)', 'mahasiswa@bedushub.test', 'diterima'],
            ['Backend Developer (PHP/Laravel)', 'alumni@bedushub.test', 'diproses'],
            ['Backend Developer (PHP/Laravel)', 'alumni2@bedushub.test', 'diterima'],
            ['Frontend Developer (React)', 'mahasiswa2@bedushub.test', 'diproses'],
            ['Frontend Developer (React)', 'alumni2@bedushub.test', 'ditolak'],
            ['Staff Administrasi', 'siswa4@bedushub.test', 'diproses'],
            ['Staff Administrasi', 'mahasiswa4@bedushub.test', 'diterima'],
            ['Agronomis', 'alumni3@bedushub.test', 'diproses'],
            ['Quality Control Pertanian', 'alumni3@bedushub.test', 'diterima'],
            ['UI/UX Designer', 'siswa3@bedushub.test', 'diproses'],
            ['UI/UX Designer', 'mahasiswa2@bedushub.test', 'diproses'],
            ['Content Creator', 'siswa3@bedushub.test', 'ditolak'],
            ['Digital Marketing Specialist', 'mahasiswa4@bedushub.test', 'diproses'],
            ['Digital Marketing Specialist', 'alumni2@bedushub.test', 'diproses'],
        ];

        foreach ($data as [$judul, $email, $status]) {
            $this->lamaran($judul, $email, $status);
        }
    }

    private function pendaftaranPelatihans(): void
    {
        $data = [
            ['Bootcamp Laravel Dasar', 'siswa@bedushub.test'],
            ['Bootcamp Laravel Dasar', 'mahasiswa@bedushub.test'],
            ['Bootcamp Laravel Dasar', 'alumni@bedushub.test'],
            ['Pelatihan Digital Marketing', 'siswa3@bedushub.test'],
            ['Pelatihan Digital Marketing', 'mahasiswa4@bedushub.test'],
            ['Pelatihan Digital Marketing', 'alumni2@bedushub.test'],
            ['UI/UX Design Fundamentals', 'siswa3@bedushub.test'],
            ['UI/UX Design Fundamentals', 'mahasiswa2@bedushub.test'],
            ['Data Analysis dengan Python', 'mahasiswa2@bedushub.test'],
            ['Data Analysis dengan Python', 'alumni@bedushub.test'],
        ];

        foreach ($data as [$judul, $email]) {
            $this->daftarPelatihan($judul, $email);
        }
    }

    private function asesmenJawabans(): void
    {
        $data = [
            ['Asesmen Minat Karir', 'siswa@bedushub.test'],
            ['Asesmen Minat Karir', 'mahasiswa@bedushub.test'],
            ['Asesmen Minat Karir', 'alumni@bedushub.test'],
            ['Asesmen Bakat & Potensi', 'siswa@bedushub.test'],
            ['Asesmen Bakat & Potensi', 'mahasiswa2@bedushub.test'],
            ['Asesmen Skill Teknis', 'mahasiswa@bedushub.test'],
            ['Asesmen Skill Teknis', 'alumni@bedushub.test'],
        ];

        foreach ($data as [$judul, $email]) {
            $this->jawabAsesmen($judul, $email);
        }
    }

    private function tracerStudies(): void
    {
        $data = [
            ['alumni@bedushub.test', 'bekerja', 'PT Teknologi Nusantara', 'Backend Developer', 'Teknologi Informasi', '5.000.000 - 8.000.000', 5, 4],
            ['alumni2@bedushub.test', 'bekerja', 'CV Karya Mandiri', 'Staff IT', 'Teknologi Informasi', '3.000.000 - 5.000.000', 4, 4],
            ['alumni3@bedushub.test', 'bekerja', 'PT Agro Sejahtera', 'Agronomis', 'Pertanian', '4.000.000 - 6.000.000', 5, 5],
            ['alumni4@bedushub.test', 'wirausaha', null, 'Owner', 'Teknik Komputer', '3.000.000 - 7.000.000', 3, 4],
            ['alumni@bedushub.test', 'bekerja', 'PT Teknologi Nusantara', 'Senior Backend Developer', 'Teknologi Informasi', '8.000.000 - 12.000.000', 5, 5],
        ];

        foreach ($data as [$email, $status, $perusahaan, $posisi, $bidang, $penghasilan, $relevansi, $kepuasan]) {
            $user = User::where('email', $email)->first();
            if (! $user) {
                continue;
            }

            TracerStudy::create([
                'user_id' => $user->id,
                'status_pekerjaan' => $status,
                'nama_perusahaan' => $perusahaan,
                'posisi' => $posisi,
                'bidang_pekerjaan' => $bidang,
                'penghasilan' => $penghasilan,
                'relevansi_pekerjaan' => $relevansi,
                'kepuasan_pendidikan' => $kepuasan,
            ]);
        }
    }

    private function rekomendasiLogs(): void
    {
        $lowonganId = Lowongan::where('judul', 'Backend Developer (PHP/Laravel)')->value('id');
        $pelatihanId = Pelatihan::where('judul', 'Bootcamp Laravel Dasar')->value('id');
        $prodiId = ProgramStudi::where('kode', 'TI')->value('id');
        $potensiId = PotensiDaerah::where('nama', 'Budidaya Padi Organik')->value('id');

        $data = [
            ['siswa@bedushub.test', 'lowongan', $lowonganId, 0.85, 'Kecocokan skill PHP & MySQL.', 'view'],
            ['siswa@bedushub.test', 'pelatihan', $pelatihanId, 0.90, 'Sesuai minat pengembangan web.', 'daftar'],
            ['mahasiswa@bedushub.test', 'lowongan', $lowonganId, 0.92, 'Skill Laravel sangat sesuai.', 'apply'],
            ['mahasiswa2@bedushub.test', 'lowongan', Lowongan::where('judul', 'UI/UX Designer')->value('id'), 0.80, 'Kecocokan desain UI/UX.', 'view'],
            ['siswa3@bedushub.test', 'pelatihan', Pelatihan::where('judul', 'UI/UX Design Fundamentals')->value('id'), 0.88, 'Sesuai minat desain.', 'daftar'],
            ['alumni@bedushub.test', 'prodi', $prodiId, 0.90, 'Prodi relevan dengan karir.', 'view'],
            ['alumni3@bedushub.test', 'potensi', $potensiId, 0.75, 'Peluang usaha agribisnis.', 'view'],
            ['mahasiswa4@bedushub.test', 'pelatihan', Pelatihan::where('judul', 'Pelatihan Digital Marketing')->value('id'), 0.85, 'Sesuai minat pemasaran.', 'daftar'],
        ];

        foreach ($data as [$email, $tipe, $itemId, $skor, $alasan, $aksi]) {
            $user = User::where('email', $email)->first();
            if (! $user || ! $itemId) {
                continue;
            }

            RekomendasiLog::create([
                'user_id' => $user->id,
                'tipe_item' => $tipe,
                'item_id' => $itemId,
                'skor' => $skor,
                'alasan' => $alasan,
                'aksi' => $aksi,
            ]);
        }
    }

    private function notifikasis(): void
    {
        $data = [
            ['admin@bedushub.test', 'Pendaftaran baru', 'Ada peserta baru yang mendaftar.', false],
            ['perusahaan@bedushub.test', 'Lamaran masuk', 'Lamaran baru masuk untuk lowongan Backend Developer.', true],
            ['siswa@bedushub.test', 'Lowongan baru tersedia', 'Lowongan Backend Developer (PHP/Laravel) tersedia.', false],
            ['siswa@bedushub.test', 'Lamaran diterima', 'Lamaran Anda untuk posisi Backend Developer diterima.', true],
            ['mahasiswa@bedushub.test', 'Pelatihan dimulai', 'Pelatihan Bootcamp Laravel Dasar akan segera dimulai.', false],
            ['alumni@bedushub.test', 'Rekomendasi karir', 'Ada rekomendasi karir baru untuk Anda.', false],
            ['siswa3@bedushub.test', 'Asesmen tersedia', 'Asesmen baru telah tersedia untuk dikerjakan.', false],
            ['mahasiswa4@bedushub.test', 'Lamaran diproses', 'Lamaran Anda sedang diproses perusahaan.', true],
            ['perusahaan2@bedushub.test', 'Lowongan Anda nonaktif', 'Lowongan Customer Service telah dinonaktifkan.', false],
            ['admin@bedushub.test', 'Laporan bulanan', 'Laporan bulanan siap untuk diunduh.', true],
        ];

        foreach ($data as [$email, $judul, $isi, $dibaca]) {
            $user = User::where('email', $email)->first();
            if (! $user) {
                continue;
            }

            Notifikasi::create([
                'user_id' => $user->id,
                'judul' => $judul,
                'isi' => $isi,
                'dibaca' => $dibaca,
            ]);
        }
    }

    private function lamaran(string $judul, string $email, string $status): void
    {
        $lowongan = Lowongan::where('judul', $judul)->first();
        $user = User::where('email', $email)->first();
        if ($lowongan && $user) {
            Lamaran::create([
                'lowongan_id' => $lowongan->id,
                'user_id' => $user->id,
                'status' => $status,
                'cv' => null,
                'catatan' => null,
            ]);
        }
    }

    private function daftarPelatihan(string $judul, string $email): void
    {
        $pelatihan = Pelatihan::where('judul', $judul)->first();
        $user = User::where('email', $email)->first();
        if ($pelatihan && $user) {
            PendaftaranPelatihan::create([
                'pelatihan_id' => $pelatihan->id,
                'user_id' => $user->id,
                'status' => 'terdaftar',
            ]);
        }
    }

    private function jawabAsesmen(string $judul, string $email): void
    {
        $asesmen = Asesmen::where('judul', $judul)->first();
        $user = User::where('email', $email)->first();
        if (! $asesmen || ! $user) {
            return;
        }

        foreach ($asesmen->asesmenSoals as $soal) {
            $jawaban = match ($soal->tipe_jawaban) {
                'pilihan_ganda' => $soal->opsi[0] ?? 'A',
                'skala' => '4',
                'teks' => 'Saya memahami materi asesmen ini.',
                default => null,
            };
            $skor = match ($soal->tipe_jawaban) {
                'pilihan_ganda' => 10,
                'skala' => 4,
                'teks' => 8,
                default => null,
            };

            AsesmenJawaban::create([
                'asesmen_id' => $asesmen->id,
                'soal_id' => $soal->id,
                'user_id' => $user->id,
                'jawaban' => $jawaban,
                'skor' => $skor,
            ]);
        }
    }
}
