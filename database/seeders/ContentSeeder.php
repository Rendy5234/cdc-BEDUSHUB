<?php

namespace Database\Seeders;

use App\Models\Asesmen;
use App\Models\AsesmenSoal;
use App\Models\KategoriMinat;
use App\Models\Lowongan;
use App\Models\LowonganSkill;
use App\Models\Pelatihan;
use App\Models\PelatihanSkill;
use App\Models\Perusahaan;
use App\Models\Skill;
use App\Models\User;
use Illuminate\Database\Seeder;

class ContentSeeder extends Seeder
{
    public function run(): void
    {
        $u1 = User::where('email', 'perusahaan@bedushub.test')->first();
        $u2 = User::where('email', 'perusahaan2@bedushub.test')->first();
        $u3 = User::where('email', 'perusahaan3@bedushub.test')->first();
        $u4 = User::where('email', 'perusahaan4@bedushub.test')->first();

        $p1 = Perusahaan::create(['user_id' => $u1->id, 'nama' => 'PT Teknologi Nusantara', 'bidang_usaha' => 'Teknologi Informasi', 'alamat' => 'Jl. Sudirman No. 10, Bedus', 'kecamatan' => 'Bedus', 'no_telp' => '021-2222-0001', 'status_kerjasama' => 'aktif', 'deskripsi' => 'Perusahaan pengembang perangkat lunak dan layanan IT.']);
        $p2 = Perusahaan::create(['user_id' => $u2->id, 'nama' => 'CV Karya Mandiri', 'bidang_usaha' => 'Konstruksi & Jasa', 'alamat' => 'Jl. Ahmad Yani No. 20, Bedus', 'kecamatan' => 'Bedus', 'no_telp' => '021-2222-0002', 'status_kerjasama' => 'aktif', 'deskripsi' => 'Perusahaan konstruksi dan jasa umum.']);
        $p3 = Perusahaan::create(['user_id' => $u3->id, 'nama' => 'PT Agro Sejahtera', 'bidang_usaha' => 'Pertanian', 'alamat' => 'Jl. Raya Pertanian No. 5, Bedus', 'kecamatan' => 'Bedus', 'no_telp' => '021-2222-0003', 'status_kerjasama' => 'aktif', 'deskripsi' => 'Perusahaan agribisnis dan pengolahan hasil pertanian.']);
        $p4 = Perusahaan::create(['user_id' => $u4->id, 'nama' => 'PT Kreasi Digital', 'bidang_usaha' => 'Industri Kreatif', 'alamat' => 'Jl. Kreatif No. 8, Bedus', 'kecamatan' => 'Bedus', 'no_telp' => '021-2222-0004', 'status_kerjasama' => 'aktif', 'deskripsi' => 'Studio desain dan pemasaran digital.']);

        // Lowongan
        $this->lowongan($p1, 'Backend Developer (PHP/Laravel)', 'aktif', 'Full-time', 5000000, 8000000, 45, [['Laravel', 'menengah'], ['PHP', 'menengah'], ['MySQL', 'pemula']], 's1', ['Teknologi Informasi']);
        $this->lowongan($p1, 'Frontend Developer (React)', 'aktif', 'Full-time', 5000000, 7000000, 60, [['React', 'menengah'], ['JavaScript', 'menengah']], 's1', ['Teknologi Informasi']);
        $this->lowongan($p1, 'IT Support', 'nonaktif', 'Kontrak', 3000000, 4000000, -10, [['Jaringan Komputer', 'pemula'], ['Microsoft Office', 'pemula']], 'smk', ['Teknologi Informasi']);
        $this->lowongan($p2, 'Staff Administrasi', 'aktif', 'Full-time', 2500000, 3500000, 30, [['Microsoft Office', 'menengah'], ['Komunikasi', 'pemula']], 'smk', ['Keuangan & Akuntansi']);
        $this->lowongan($p2, 'Teknisi Lapangan', 'aktif', 'Kontrak', 2800000, 3800000, 20, [['Jaringan Komputer', 'pemula']], 'smk', ['Teknik & Rekayasa']);
        $this->lowongan($p2, 'Customer Service', 'nonaktif', 'Part-time', 2000000, 2500000, -15, [['Komunikasi', 'pemula']], 'sma', ['Bisnis & Wirausaha']);
        $this->lowongan($p3, 'Agronomis', 'aktif', 'Full-time', 4500000, 6500000, 40, [['Analisis Data', 'menengah']], 's1', ['Agribisnis']);
        $this->lowongan($p3, 'Quality Control Pertanian', 'aktif', 'Full-time', 4000000, 5500000, 35, [['Microsoft Office', 'pemula']], 'd3', ['Agribisnis']);
        $this->lowongan($p3, 'Marketing Produk Pertanian', 'nonaktif', 'Full-time', 3500000, 5000000, -5, [['Digital Marketing', 'pemula'], ['Komunikasi', 'pemula']], 's1', ['Pemasaran Digital', 'Agribisnis']);
        $this->lowongan($p4, 'UI/UX Designer', 'aktif', 'Full-time', 5000000, 7500000, 50, [['UI/UX Design', 'menengah']], 's1', ['Desain & Kreatif']);
        $this->lowongan($p4, 'Content Creator', 'aktif', 'Kontrak', 3000000, 5000000, 25, [['Digital Marketing', 'pemula']], 'smk', ['Desain & Kreatif', 'Pemasaran Digital']);
        $this->lowongan($p4, 'Digital Marketing Specialist', 'aktif', 'Full-time', 4500000, 6500000, 45, [['Digital Marketing', 'menengah'], ['Analisis Data', 'pemula']], 's1', ['Pemasaran Digital']);

        // Pelatihan
        $this->pelatihan('Bootcamp Laravel Dasar', 'published', 'Web Development', 'pemula', 'Budi Santoso', 7, 21, 30, ['Laravel', 'PHP', 'MySQL'], ['Teknologi Informasi']);
        $this->pelatihan('Pelatihan Digital Marketing', 'published', 'Pemasaran', 'pemula', 'Siti Rahma', 10, 17, 40, ['Digital Marketing'], ['Pemasaran Digital']);
        $this->pelatihan('UI/UX Design Fundamentals', 'published', 'Desain', 'menengah', 'Agus Wijaya', 14, 21, 25, ['UI/UX Design', 'React'], ['Desain & Kreatif']);
        $this->pelatihan('Data Analysis dengan Python', 'published', 'Data', 'menengah', 'Dina Putri', 21, 28, 20, ['Python', 'Analisis Data', 'PostgreSQL'], ['Teknologi Informasi']);
        $this->pelatihan('Soft Skill & Kepemimpinan', 'draft', 'Pengembangan Diri', 'pemula', 'Rudi Hartono', 30, 31, 50, ['Leadership', 'Komunikasi'], ['Bisnis & Wirausaha']);
        $this->pelatihan('Jaringan Komputer Dasar', 'draft', 'Infrastruktur', 'pemula', 'Fajar Nugroho', 15, 22, 30, ['Jaringan Komputer'], ['Teknik & Rekayasa']);

        // Asesmen + soal
        $asesmenMinat = Asesmen::create(['judul' => 'Asesmen Minat Karir', 'deskripsi' => 'Mengukur minat karir peserta.', 'tipe' => 'minat']);
        $soalSkala = ['1 - Sangat Tidak Setuju', '2 - Tidak Setuju', '3 - Netral', '4 - Setuju', '5 - Sangat Setuju'];
        $soalMinat = [
            ['pertanyaan' => 'Saya tertarik bekerja di bidang teknologi informasi.', 'kategori_minat' => 'Teknologi Informasi'],
            ['pertanyaan' => 'Saya senang menyelesaikan masalah yang membutuhkan analisis.', 'kategori_minat' => 'Teknologi Informasi'],
            ['pertanyaan' => 'Saya tertarik pada pekerjaan yang berhubungan dengan orang banyak.', 'kategori_minat' => 'Bisnis & Wirausaha'],
            ['pertanyaan' => 'Saya menyukai aktivitas kreatif seperti desain atau menulis.', 'kategori_minat' => 'Desain & Kreatif'],
            ['pertanyaan' => 'Saya tertarik membangun usaha sendiri (wirausaha).', 'kategori_minat' => 'Bisnis & Wirausaha'],
        ];
        foreach ($soalMinat as $s) {
            AsesmenSoal::create([
                'asesmen_id' => $asesmenMinat->id,
                'pertanyaan' => $s['pertanyaan'],
                'tipe_jawaban' => 'skala',
                'opsi' => $soalSkala,
                'kategori_minat_id' => KategoriMinat::where('nama', $s['kategori_minat'])->value('id'),
            ]);
        }

        $asesmenBakat = Asesmen::create(['judul' => 'Asesmen Bakat & Potensi', 'deskripsi' => 'Mengukur bakat dan potensi peserta.', 'tipe' => 'bakat']);
        $soalBakat = [
            ['pertanyaan' => 'Kemampuan yang paling menonjol pada diri saya adalah...', 'opsi' => ['Logika & pemecahan masalah', 'Komunikasi & sosial', 'Kreativitas & seni', 'Kepemimpinan & organisasi']],
            ['pertanyaan' => 'Saat mengerjakan tugas kelompok, saya biasanya...', 'opsi' => ['Mengerjakan bagian teknis', 'Menjadi koordinator', 'Menyusun ide kreatif', 'Menyajikan hasil']],
            ['pertanyaan' => 'Saya paling menikmati kegiatan...', 'opsi' => ['Membaca & menganalisis data', 'Berinteraksi dengan orang', 'Membuat desain/karya', 'Mengatur acara']],
            ['pertanyaan' => 'Gaya belajar saya lebih condong ke...', 'opsi' => ['Praktik langsung', 'Membaca teori', 'Diskusi kelompok', 'Audio/visual']],
            ['pertanyaan' => 'Dalam situasi tertekan, saya cenderung...', 'opsi' => ['Tetap tenang & logis', 'Meminta bantuan tim', 'Mencari solusi kreatif', 'Mengambil alih kendali']],
        ];
        foreach ($soalBakat as $s) {
            AsesmenSoal::create(['asesmen_id' => $asesmenBakat->id, 'pertanyaan' => $s['pertanyaan'], 'tipe_jawaban' => 'pilihan_ganda', 'opsi' => $s['opsi']]);
        }

        $asesmenSkill = Asesmen::create(['judul' => 'Asesmen Skill Teknis', 'deskripsi' => 'Mengukur keterampilan teknis peserta.', 'tipe' => 'skill']);
        $this->soalSkill($asesmenSkill, 'Manakah yang merupakan framework PHP?', 'pilihan_ganda', ['Laravel', 'React', 'Django', 'Flutter'], 'Laravel');
        $this->soalSkill($asesmenSkill, 'Perintah SQL untuk mengambil data adalah...', 'pilihan_ganda', ['SELECT', 'INSERT', 'UPDATE', 'DELETE'], 'MySQL');
        $this->soalSkill($asesmenSkill, 'Saya mampu mengelola basis data dengan baik.', 'skala', $soalSkala, 'MySQL');
        $this->soalSkill($asesmenSkill, 'Bahasa pemrograman untuk analisis data yang populer adalah...', 'pilihan_ganda', ['Python', 'HTML', 'CSS', 'Bash'], 'Python');
        $this->soalSkill($asesmenSkill, 'Saya mampu bekerja dalam tim pengembangan perangkat lunak.', 'skala', $soalSkala, 'Komunikasi');
        $this->soalSkill($asesmenSkill, 'Jelaskan pengalaman proyek teknologi yang pernah Anda kerjakan.', 'teks', null, null);
    }

    private function lowongan(Perusahaan $perusahaan, string $judul, string $status, string $tipe, int $gajiMin, int $gajiMax, int $berakhirHari, array $skills, string $pendidikan, array $minat = []): void
    {
        $lowongan = Lowongan::create([
            'perusahaan_id' => $perusahaan->id,
            'judul' => $judul,
            'deskripsi' => 'Deskripsi pekerjaan untuk posisi '.$judul.'.',
            'kualifikasi' => 'Kualifikasi sesuai bidang '.$judul.'.',
            'tipe_pekerjaan' => $tipe,
            'lokasi' => 'Bedus',
            'gaji_min' => $gajiMin,
            'gaji_max' => $gajiMax,
            'tanggal_berakhir' => now()->addDays($berakhirHari)->toDateString(),
            'status' => $status,
            'pendidikan' => $pendidikan,
        ]);

        foreach ($skills as [$nama, $level]) {
            $skill = Skill::where('nama', $nama)->first();
            if ($skill) {
                LowonganSkill::create(['lowongan_id' => $lowongan->id, 'skill_id' => $skill->id, 'level_min' => $level]);
            }
        }

        foreach ($minat as $namaMinat) {
            $minatModel = KategoriMinat::where('nama', $namaMinat)->first();
            if ($minatModel) {
                $lowongan->minat()->attach($minatModel->id);
            }
        }
    }

    private function pelatihan(string $judul, string $status, string $topik, string $level, string $instruktur, int $mulaiHari, int $selesaiHari, int $kuota, array $skills, array $minat = []): void
    {
        $pelatihan = Pelatihan::create([
            'judul' => $judul,
            'deskripsi' => 'Deskripsi pelatihan '.$judul.'.',
            'topik' => $topik,
            'level' => $level,
            'instruktur' => $instruktur,
            'tanggal_mulai' => now()->addDays($mulaiHari)->toDateString(),
            'tanggal_selesai' => now()->addDays($selesaiHari)->toDateString(),
            'kuota' => $kuota,
            'status' => $status,
        ]);

        foreach ($skills as $nama) {
            $skill = Skill::where('nama', $nama)->first();
            if ($skill) {
                PelatihanSkill::create(['pelatihan_id' => $pelatihan->id, 'skill_id' => $skill->id]);
            }
        }

        foreach ($minat as $namaMinat) {
            $minatModel = KategoriMinat::where('nama', $namaMinat)->first();
            if ($minatModel) {
                $pelatihan->minat()->attach($minatModel->id);
            }
        }
    }

    private function soalSkill(Asesmen $asesmen, string $pertanyaan, string $tipeJawaban, ?array $opsi, ?string $namaSkill): void
    {
        AsesmenSoal::create([
            'asesmen_id' => $asesmen->id,
            'pertanyaan' => $pertanyaan,
            'tipe_jawaban' => $tipeJawaban,
            'opsi' => $opsi,
            'skill_id' => $namaSkill ? Skill::where('nama', $namaSkill)->value('id') : null,
        ]);
    }
}
