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
        // STEP 2b: skill diperkaya menjadi 3-4 skill spesifik + maks 1 generik.
        // Setiap item menyimpan >=1 skill lama sebagai jembatan ke skill user.
        $this->lowongan($p1, 'Backend Developer (PHP/Laravel)', 'aktif', 'Full-time', 5000000, 8000000, 45, [['Laravel', 'menengah'], ['PHP', 'menengah'], ['MySQL', 'pemula'], ['REST API', 'menengah'], ['Git', 'pemula']], 's1', ['Teknologi Informasi']);
        $this->lowongan($p1, 'Frontend Developer (React)', 'aktif', 'Full-time', 5000000, 7000000, 60, [['React', 'menengah'], ['JavaScript', 'menengah'], ['REST API', 'pemula'], ['Git', 'pemula']], 's1', ['Teknologi Informasi']);
        $this->lowongan($p1, 'IT Support', 'nonaktif', 'Kontrak', 3000000, 4000000, -10, [['Jaringan Komputer', 'pemula'], ['Linux', 'pemula'], ['Komunikasi', 'pemula']], 'smk', ['Teknologi Informasi']);
        $this->lowongan($p2, 'Staff Administrasi', 'aktif', 'Full-time', 2500000, 3500000, 30, [['Microsoft Office', 'menengah'], ['Excel Advanced', 'pemula'], ['Komunikasi', 'pemula']], 'smk', ['Keuangan & Akuntansi']);
        $this->lowongan($p2, 'Teknisi Lapangan', 'aktif', 'Kontrak', 2800000, 3800000, 20, [['Jaringan Komputer', 'pemula'], ['Linux', 'pemula'], ['Komunikasi', 'pemula']], 'smk', ['Teknik & Rekayasa']);
        $this->lowongan($p2, 'Customer Service', 'nonaktif', 'Part-time', 2000000, 2500000, -15, [['Komunikasi', 'menengah'], ['Microsoft Office', 'pemula'], ['Content Creation', 'pemula']], 'sma', ['Bisnis & Wirausaha']);
        $this->lowongan($p3, 'Agronomis', 'aktif', 'Full-time', 4500000, 6500000, 40, [['Analisis Data', 'menengah'], ['Statistik', 'menengah'], ['Budgeting', 'pemula']], 's1', ['Agribisnis']);
        $this->lowongan($p3, 'Quality Control Pertanian', 'aktif', 'Full-time', 4000000, 5500000, 35, [['Analisis Data', 'pemula'], ['Excel Advanced', 'pemula'], ['Statistik', 'pemula']], 'd3', ['Agribisnis']);
        $this->lowongan($p3, 'Marketing Produk Pertanian', 'nonaktif', 'Full-time', 3500000, 5000000, -5, [['Digital Marketing', 'pemula'], ['SEO', 'pemula'], ['Komunikasi', 'menengah']], 's1', ['Pemasaran Digital', 'Agribisnis']);
        $this->lowongan($p4, 'UI/UX Designer', 'aktif', 'Full-time', 5000000, 7500000, 50, [['UI/UX Design', 'menengah'], ['Figma', 'menengah'], ['Prototyping', 'menengah'], ['Wireframing', 'pemula']], 's1', ['Desain & Kreatif']);
        $this->lowongan($p4, 'Content Creator', 'aktif', 'Kontrak', 3000000, 5000000, 25, [['Content Creation', 'menengah'], ['Copywriting', 'pemula'], ['SEO', 'pemula'], ['Adobe Photoshop', 'pemula']], 'smk', ['Desain & Kreatif', 'Pemasaran Digital']);
        $this->lowongan($p4, 'Digital Marketing Specialist', 'aktif', 'Full-time', 4500000, 6500000, 45, [['Digital Marketing', 'menengah'], ['SEO', 'menengah'], ['Google Analytics', 'menengah'], ['Copywriting', 'pemula']], 's1', ['Pemasaran Digital']);

        // Pelatihan
        // STEP 2b: skill diperkaya menjadi 3-4 skill spesifik.
        $this->pelatihan('Bootcamp Laravel Dasar', 'published', 'Web Development', 'pemula', 'Budi Santoso', 7, 21, 30, ['Laravel', 'PHP', 'MySQL', 'REST API', 'Git'], ['Teknologi Informasi']);
        $this->pelatihan('Pelatihan Digital Marketing', 'published', 'Pemasaran', 'pemula', 'Siti Rahma', 10, 17, 40, ['Digital Marketing', 'SEO', 'Google Analytics', 'Content Creation'], ['Pemasaran Digital']);
        $this->pelatihan('UI/UX Design Fundamentals', 'published', 'Desain', 'menengah', 'Agus Wijaya', 14, 21, 25, ['UI/UX Design', 'Figma', 'Wireframing', 'Prototyping'], ['Desain & Kreatif']);
        $this->pelatihan('Data Analysis dengan Python', 'published', 'Data', 'menengah', 'Dina Putri', 21, 28, 20, ['Python', 'Analisis Data', 'PostgreSQL', 'Statistik', 'Tableau'], ['Teknologi Informasi']);
        $this->pelatihan('Soft Skill & Kepemimpinan', 'draft', 'Pengembangan Diri', 'pemula', 'Rudi Hartono', 30, 31, 50, ['Leadership', 'Komunikasi', 'Rekrutmen'], ['Bisnis & Wirausaha']);
        $this->pelatihan('Jaringan Komputer Dasar', 'draft', 'Infrastruktur', 'pemula', 'Fajar Nugroho', 15, 22, 30, ['Jaringan Komputer', 'Linux', 'Docker'], ['Teknik & Rekayasa']);

        // Lowongan tambahan (target total 20-25)
        $this->lowongan($p1, 'Full Stack Developer (Vue+Laravel)', 'aktif', 'Full-time', 6000000, 9000000, 55, [['Laravel', 'menengah'], ['Vue.js', 'menengah'], ['MySQL', 'menengah'], ['REST API', 'menengah'], ['Git', 'menengah']], 's1', ['Teknologi Informasi']);
        $this->lowongan($p1, 'Data Analyst', 'aktif', 'Full-time', 6500000, 9500000, 50, [['Python', 'menengah'], ['Analisis Data', 'menengah'], ['PostgreSQL', 'pemula'], ['Statistik', 'menengah'], ['Tableau', 'pemula']], 's1', ['Teknologi Informasi']);
        $this->lowongan($p1, 'DevOps Engineer', 'aktif', 'Kontrak', 7000000, 10000000, 40, [['Linux', 'menengah'], ['Jaringan Komputer', 'menengah'], ['Docker', 'menengah'], ['Git', 'menengah']], 's1', ['Teknik & Rekayasa']);
        $this->lowongan($p2, 'Operator Produksi', 'aktif', 'Full-time', 2500000, 3200000, 30, [['Microsoft Office', 'pemula'], ['Jaringan Komputer', 'pemula'], ['Komunikasi', 'pemula']], 'smk', ['Teknik & Rekayasa']);
        $this->lowongan($p2, 'Staf HRD', 'aktif', 'Full-time', 3000000, 4500000, 45, [['Rekrutmen', 'menengah'], ['Payroll', 'pemula'], ['Komunikasi', 'menengah'], ['Microsoft Office', 'menengah']], 's1', ['Bisnis & Wirausaha']);
        $this->lowongan($p2, 'Staf Keuangan', 'aktif', 'Full-time', 3500000, 5000000, 35, [['Akuntansi', 'menengah'], ['Excel Advanced', 'mahir'], ['Perpajakan', 'pemula'], ['Budgeting', 'pemula']], 's1', ['Keuangan & Akuntansi']);
        $this->lowongan($p3, 'Teknisi Pertanian', 'aktif', 'Kontrak', 2800000, 3800000, 25, [['Analisis Data', 'pemula'], ['Komunikasi', 'pemula'], ['Statistik', 'pemula']], 'smk', ['Agribisnis']);
        $this->lowongan($p3, 'Data Entry & Administrasi', 'aktif', 'Part-time', 2000000, 2800000, 20, [['Excel Advanced', 'menengah'], ['Microsoft Office', 'menengah'], ['Komunikasi', 'pemula']], 'smk', ['Keuangan & Akuntansi']);
        $this->lowongan($p4, 'Graphic Designer', 'aktif', 'Full-time', 4000000, 6000000, 45, [['Figma', 'menengah'], ['Adobe Photoshop', 'menengah'], ['UI/UX Design', 'menengah'], ['Prototyping', 'pemula']], 'smk', ['Desain & Kreatif']);
        $this->lowongan($p4, 'Social Media Specialist', 'aktif', 'Kontrak', 3500000, 5000000, 30, [['Content Creation', 'menengah'], ['Copywriting', 'pemula'], ['Digital Marketing', 'menengah'], ['Google Analytics', 'pemula']], 'smk', ['Pemasaran Digital', 'Desain & Kreatif']);
        $this->lowongan($p4, 'SEO & Content Writer', 'aktif', 'Part-time', 2500000, 3500000, 60, [['SEO', 'menengah'], ['Copywriting', 'menengah'], ['Content Creation', 'pemula'], ['Google Analytics', 'pemula']], 'sma', ['Pemasaran Digital']);
        $this->lowongan($p1, 'Mobile Developer (React Native)', 'aktif', 'Full-time', 6000000, 8500000, 50, [['React', 'menengah'], ['JavaScript', 'mahir'], ['REST API', 'menengah'], ['Git', 'menengah']], 's1', ['Teknologi Informasi']);

        // Pelatihan tambahan (target total 15-20)
        $this->pelatihan('Pemrograman Python untuk Pemula', 'published', 'Pemrograman', 'pemula', 'Andi Wibowo', 5, 12, 35, ['Python', 'Analisis Data', 'Statistik'], ['Teknologi Informasi']);
        $this->pelatihan('Vue.js untuk Frontend Modern', 'published', 'Web Development', 'menengah', 'Rizki Pratama', 14, 21, 25, ['Vue.js', 'JavaScript', 'REST API', 'Git'], ['Teknologi Informasi']);
        $this->pelatihan('Pelatihan Microsoft Office Profesional', 'published', 'Produktivitas', 'pemula', 'Sari Dewi', 3, 5, 50, ['Microsoft Office', 'Excel Advanced'], ['Bisnis & Wirausaha', 'Keuangan & Akuntansi']);
        $this->pelatihan('Komunikasi Bisnis & Public Speaking', 'published', 'Soft Skill', 'pemula', 'Hendra Wijaya', 10, 11, 40, ['Komunikasi', 'Leadership', 'Content Creation'], ['Bisnis & Wirausaha']);
        $this->pelatihan('Teknik Jaringan & Keamanan Siber', 'published', 'Infrastruktur', 'menengah', 'Budi Santoso', 20, 27, 20, ['Jaringan Komputer', 'Linux', 'Docker'], ['Teknik & Rekayasa']);
        $this->pelatihan('Desain Grafis dengan Figma', 'published', 'Desain', 'pemula', 'Rina Anggraini', 7, 14, 30, ['Figma', 'UI/UX Design', 'Wireframing', 'Adobe Photoshop'], ['Desain & Kreatif']);
        $this->pelatihan('E-Commerce & Digital Selling', 'published', 'Pemasaran', 'pemula', 'Maya Putri', 15, 16, 45, ['Digital Marketing', 'SEO', 'Google Analytics', 'Copywriting'], ['Pemasaran Digital', 'Bisnis & Wirausaha']);
        $this->pelatihan('Analisis Data dengan Spreadsheet', 'published', 'Data', 'pemula', 'Dinda Sari', 8, 10, 35, ['Analisis Data', 'Excel Advanced', 'Statistik'], ['Teknologi Informasi', 'Keuangan & Akuntansi']);
        $this->pelatihan('React.js Advanced', 'published', 'Web Development', 'mahir', 'Fajar Nugroho', 25, 32, 15, ['React', 'JavaScript', 'REST API', 'Git'], ['Teknologi Informasi']);
        $this->pelatihan('Copywriting & Content Strategy', 'published', 'Pemasaran', 'pemula', 'Wulandari', 12, 13, 30, ['Copywriting', 'SEO', 'Content Creation'], ['Pemasaran Digital']);

        // Eksperimen Fase B: generate lowongan & pelatihan tambahan agar katalog
        // cukup besar untuk di-scale (target: 80 lowongan aktif, 50 pelatihan published).
        $this->generateKontenTambahan([$p1, $p2, $p3, $p4]);

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
            'pendidikan' => [$pendidikan],
        ]);

        foreach ($skills as [$nama, $level]) {
            $skill = Skill::where('nama', $nama)->first();
            if ($skill) {
                LowonganSkill::create(['lowongan_id' => $lowongan->id, 'skill_id' => $skill->id, 'level_min' => $level]);
            }
        }

        // STEP 2a: perkaya minat lowongan menjadi 2-3 kategori agar sinyal item lebih tajam.
        foreach ($this->perluasMinat($minat, $skills) as $namaMinat) {
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

        // STEP 2a: perkaya minat pelatihan menjadi 2-3 kategori agar sinyal item lebih tajam.
        foreach ($this->perluasMinat($minat, $skills) as $namaMinat) {
            $minatModel = KategoriMinat::where('nama', $namaMinat)->first();
            if ($minatModel) {
                $pelatihan->minat()->attach($minatModel->id);
            }
        }
    }

    /**
     * Peta afinitas skill -> kategori minat.
     *
     * Dipakai untuk memperluas minat item secara bermakna (bukan acak), sehingga
     * item terhubung ke minat yang memang relevan dengan skillnya.
     *
     * @var array<string, array<int, string>>
     */
    private const AFINITAS_SKILL_MINAT = [
        'PHP' => ['Teknologi Informasi'],
        'Laravel' => ['Teknologi Informasi'],
        'JavaScript' => ['Teknologi Informasi'],
        'React' => ['Teknologi Informasi'],
        'Vue.js' => ['Teknologi Informasi'],
        'MySQL' => ['Teknologi Informasi', 'Keuangan & Akuntansi'],
        'PostgreSQL' => ['Teknologi Informasi', 'Keuangan & Akuntansi'],
        'Python' => ['Teknologi Informasi', 'Teknik & Rekayasa'],
        'Analisis Data' => ['Teknologi Informasi', 'Keuangan & Akuntansi'],
        'Linux' => ['Teknik & Rekayasa', 'Teknologi Informasi'],
        'Jaringan Komputer' => ['Teknik & Rekayasa', 'Teknologi Informasi'],
        'Microsoft Office' => ['Keuangan & Akuntansi', 'Bisnis & Wirausaha', 'Pendidikan'],
        'Komunikasi' => ['Bisnis & Wirausaha', 'Pendidikan'],
        'Leadership' => ['Bisnis & Wirausaha', 'Pendidikan'],
        'UI/UX Design' => ['Desain & Kreatif', 'Seni & Budaya'],
        'Figma' => ['Desain & Kreatif'],
        'Digital Marketing' => ['Pemasaran Digital', 'Bisnis & Wirausaha'],
        'Copywriting' => ['Pemasaran Digital', 'Seni & Budaya'],

        // STEP 2b: afinitas untuk 19 skill spesifik baru.
        // Tanpa entri di sini, perluasMinat() tidak akan mengenali skill baru sehingga
        // perluasan minat item berbasis skill baru jadi tidak efektif.
        'REST API' => ['Teknologi Informasi'],
        'Git' => ['Teknologi Informasi', 'Teknik & Rekayasa'],
        'Docker' => ['Teknologi Informasi', 'Teknik & Rekayasa'],
        'SEO' => ['Pemasaran Digital'],
        'Google Analytics' => ['Pemasaran Digital', 'Teknologi Informasi'],
        'Content Creation' => ['Pemasaran Digital', 'Seni & Budaya'],
        'Adobe Photoshop' => ['Desain & Kreatif', 'Seni & Budaya'],
        'Prototyping' => ['Desain & Kreatif'],
        'Wireframing' => ['Desain & Kreatif'],
        'Akuntansi' => ['Keuangan & Akuntansi'],
        'Excel Advanced' => ['Keuangan & Akuntansi', 'Bisnis & Wirausaha', 'Teknologi Informasi'],
        'Perpajakan' => ['Keuangan & Akuntansi'],
        'Audit' => ['Keuangan & Akuntansi'],
        'Budgeting' => ['Keuangan & Akuntansi', 'Bisnis & Wirausaha'],
        'Rekrutmen' => ['Bisnis & Wirausaha', 'Pendidikan'],
        'Payroll' => ['Keuangan & Akuntansi', 'Bisnis & Wirausaha'],
        'Statistik' => ['Teknologi Informasi', 'Agribisnis', 'Teknik & Rekayasa'],
        'Tableau' => ['Teknologi Informasi', 'Keuangan & Akuntansi'],
        'Machine Learning' => ['Teknologi Informasi', 'Pendidikan'],
    ];

    /**
     * Perluas daftar minat item menjadi 2-3 kategori.
     *
     * Minat asli dipertahankan sebagai prioritas; kekurangan diisi dari afinitas
     * skill item. Tanpa langkah ini rata-rata minat per item hanya ~1,15 sehingga
     * daya pisah antar item sangat lemah.
     *
     * @param  array<int, string>  $minatAsli  Kategori minat dari template.
     * @param  array<int, mixed>  $skills  Daftar skill (string atau [nama, level]).
     * @return array<int, string>
     */
    private function perluasMinat(array $minatAsli, array $skills): array
    {
        $namaSkills = array_map(
            fn ($s) => is_array($s) ? $s[0] : $s,
            $skills
        );

        // Kandidat tambahan dari afinitas skill, menjaga urutan agar deterministik.
        $kandidat = [];
        foreach ($namaSkills as $nama) {
            foreach (self::AFINITAS_SKILL_MINAT[$nama] ?? [] as $minat) {
                $kandidat[$minat] = true;
            }
        }

        $hasil = array_values(array_unique($minatAsli));

        // Targetkan 3 kategori; berhenti lebih awal bila sudah mencapai 3.
        foreach (array_keys($kandidat) as $minat) {
            if (count($hasil) >= 3) {
                break;
            }
            if (! in_array($minat, $hasil, true)) {
                $hasil[] = $minat;
            }
        }

        return array_slice($hasil, 0, 3);
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

    /**
     * Generate lowongan & pelatihan tambahan untuk memperbesar katalog item.
     *
     * Semua entri memakai skill & minat yang sudah ada di master data agar pola
     * kecocokan skill/minat pada UserInteractionSeeder tetap dapat dipelajari ALS.
     *
     * @param  array<int, Perusahaan>  $perusahaan  Daftar perusahaan penempatan.
     */
    private function generateKontenTambahan(array $perusahaan): void
    {
        // [judul, skills, minat, pendidikan, tipe]
        // STEP 2b: skill diperkaya 3-4 spesifik + maks 1 generik per item.
        $templateLowongan = [
            ['Backend Engineer (PHP)', [['Laravel', 'menengah'], ['PHP', 'menengah'], ['REST API', 'menengah'], ['Git', 'pemula']], ['Teknologi Informasi'], 's1', 'Full-time'],
            ['Frontend Engineer (Vue)', [['Vue.js', 'menengah'], ['JavaScript', 'menengah'], ['REST API', 'pemula'], ['Git', 'pemula']], ['Teknologi Informasi'], 's1', 'Full-time'],
            ['Full Stack Web Developer', [['Laravel', 'menengah'], ['React', 'pemula'], ['MySQL', 'menengah'], ['REST API', 'pemula']], ['Teknologi Informasi'], 's1', 'Full-time'],
            ['Junior Programmer', [['PHP', 'pemula'], ['MySQL', 'pemula'], ['Git', 'pemula'], ['REST API', 'pemula']], ['Teknologi Informasi'], 'smk', 'Kontrak'],
            ['Web Developer Intern', [['JavaScript', 'pemula'], ['Vue.js', 'pemula'], ['Git', 'pemula'], ['REST API', 'pemula']], ['Teknologi Informasi'], 'smk', 'Magang'],
            ['Data Engineer', [['Python', 'mahir'], ['PostgreSQL', 'menengah'], ['Statistik', 'menengah'], ['Docker', 'pemula']], ['Teknologi Informasi'], 's1', 'Full-time'],
            ['Business Intelligence Analyst', [['Analisis Data', 'mahir'], ['Tableau', 'menengah'], ['Statistik', 'menengah'], ['Excel Advanced', 'mahir']], ['Teknologi Informasi', 'Keuangan & Akuntansi'], 's1', 'Full-time'],
            ['Database Administrator', [['MySQL', 'mahir'], ['PostgreSQL', 'menengah'], ['Linux', 'pemula'], ['Docker', 'pemula']], ['Teknologi Informasi'], 's1', 'Full-time'],
            ['IT Helpdesk', [['Jaringan Komputer', 'pemula'], ['Linux', 'pemula'], ['Komunikasi', 'pemula']], ['Teknologi Informasi'], 'smk', 'Kontrak'],
            ['Network Administrator', [['Jaringan Komputer', 'menengah'], ['Linux', 'menengah'], ['Docker', 'pemula'], ['Git', 'pemula']], ['Teknik & Rekayasa'], 'd3', 'Full-time'],
            ['System Administrator Linux', [['Linux', 'mahir'], ['Jaringan Komputer', 'menengah'], ['Docker', 'menengah'], ['Git', 'pemula']], ['Teknik & Rekayasa'], 's1', 'Full-time'],
            ['Cyber Security Analyst', [['Jaringan Komputer', 'mahir'], ['Linux', 'menengah'], ['Docker', 'pemula'], ['Git', 'pemula']], ['Teknologi Informasi'], 's1', 'Full-time'],
            ['UI Designer', [['UI/UX Design', 'menengah'], ['Figma', 'menengah'], ['Wireframing', 'pemula'], ['Prototyping', 'pemula']], ['Desain & Kreatif'], 'smk', 'Full-time'],
            ['UX Researcher', [['UI/UX Design', 'mahir'], ['Prototyping', 'menengah'], ['Statistik', 'pemula'], ['Komunikasi', 'menengah']], ['Desain & Kreatif'], 's1', 'Full-time'],
            ['Product Designer', [['UI/UX Design', 'menengah'], ['Figma', 'menengah'], ['Prototyping', 'menengah'], ['Wireframing', 'pemula']], ['Desain & Kreatif'], 's1', 'Full-time'],
            ['Motion Graphic Designer', [['Adobe Photoshop', 'menengah'], ['UI/UX Design', 'menengah'], ['Content Creation', 'pemula']], ['Desain & Kreatif', 'Seni & Budaya'], 'smk', 'Kontrak'],
            ['Brand Designer', [['Adobe Photoshop', 'menengah'], ['Figma', 'menengah'], ['Wireframing', 'pemula'], ['Komunikasi', 'pemula']], ['Desain & Kreatif'], 'smk', 'Full-time'],
            ['Social Media Officer', [['Content Creation', 'pemula'], ['Copywriting', 'pemula'], ['Digital Marketing', 'pemula'], ['Google Analytics', 'pemula']], ['Pemasaran Digital'], 'smk', 'Full-time'],
            ['Digital Ads Specialist', [['Digital Marketing', 'mahir'], ['Google Analytics', 'menengah'], ['SEO', 'pemula']], ['Pemasaran Digital'], 's1', 'Full-time'],
            ['Content Writer', [['Copywriting', 'menengah'], ['SEO', 'pemula'], ['Content Creation', 'pemula']], ['Pemasaran Digital', 'Seni & Budaya'], 'sma', 'Part-time'],
            ['SEO Specialist', [['SEO', 'menengah'], ['Google Analytics', 'menengah'], ['Copywriting', 'pemula']], ['Pemasaran Digital'], 's1', 'Full-time'],
            ['E-Commerce Manager', [['Digital Marketing', 'mahir'], ['Google Analytics', 'menengah'], ['Budgeting', 'pemula'], ['Leadership', 'menengah']], ['Pemasaran Digital', 'Bisnis & Wirausaha'], 's1', 'Full-time'],
            ['Marketing Executive', [['Digital Marketing', 'menengah'], ['SEO', 'pemula'], ['Content Creation', 'pemula'], ['Komunikasi', 'menengah']], ['Pemasaran Digital'], 's1', 'Full-time'],
            ['Sales Representative', [['Komunikasi', 'mahir'], ['Microsoft Office', 'pemula'], ['Budgeting', 'pemula']], ['Bisnis & Wirausaha'], 'sma', 'Full-time'],
            ['Account Executive', [['Komunikasi', 'menengah'], ['Budgeting', 'pemula'], ['Microsoft Office', 'pemula']], ['Bisnis & Wirausaha'], 's1', 'Full-time'],
            ['Staff Akuntansi', [['Akuntansi', 'menengah'], ['Excel Advanced', 'mahir'], ['Perpajakan', 'pemula'], ['Audit', 'pemula']], ['Keuangan & Akuntansi'], 'smk', 'Full-time'],
            ['Tax Officer', [['Perpajakan', 'mahir'], ['Akuntansi', 'menengah'], ['Excel Advanced', 'menengah']], ['Keuangan & Akuntansi'], 's1', 'Full-time'],
            ['Payroll Administrator', [['Payroll', 'mahir'], ['Excel Advanced', 'menengah'], ['Akuntansi', 'pemula'], ['Komunikasi', 'pemula']], ['Keuangan & Akuntansi'], 'd3', 'Full-time'],
            ['Auditor Internal', [['Audit', 'menengah'], ['Akuntansi', 'menengah'], ['Excel Advanced', 'mahir']], ['Keuangan & Akuntansi'], 's1', 'Full-time'],
            ['Finance Analyst', [['Akuntansi', 'menengah'], ['Budgeting', 'menengah'], ['Statistik', 'pemula'], ['Excel Advanced', 'mahir']], ['Keuangan & Akuntansi', 'Teknologi Informasi'], 's1', 'Full-time'],
            ['HR Officer', [['Rekrutmen', 'menengah'], ['Payroll', 'pemula'], ['Komunikasi', 'menengah'], ['Microsoft Office', 'pemula']], ['Bisnis & Wirausaha', 'Pendidikan'], 's1', 'Full-time'],
            ['Recruitment Staff', [['Rekrutmen', 'mahir'], ['Komunikasi', 'mahir'], ['Microsoft Office', 'pemula']], ['Bisnis & Wirausaha'], 's1', 'Full-time'],
            ['Training Officer', [['Rekrutmen', 'pemula'], ['Komunikasi', 'mahir'], ['Content Creation', 'pemula'], ['Leadership', 'menengah']], ['Pendidikan'], 's1', 'Full-time'],
            ['Customer Relation Officer', [['Komunikasi', 'mahir'], ['Microsoft Office', 'pemula'], ['Content Creation', 'pemula']], ['Bisnis & Wirausaha'], 'sma', 'Full-time'],
            ['Public Relations Officer', [['Komunikasi', 'mahir'], ['Copywriting', 'menengah'], ['Content Creation', 'menengah'], ['Adobe Photoshop', 'pemula']], ['Bisnis & Wirausaha', 'Seni & Budaya'], 's1', 'Full-time'],
            ['Operator Produksi Pabrik', [['Microsoft Office', 'pemula'], ['Jaringan Komputer', 'pemula'], ['Analisis Data', 'pemula']], ['Teknik & Rekayasa'], 'sma', 'Full-time'],
            ['Quality Control Officer', [['Statistik', 'menengah'], ['Analisis Data', 'pemula'], ['Excel Advanced', 'pemula']], ['Teknik & Rekayasa'], 'smk', 'Full-time'],
            ['Teknisi Maintenance', [['Jaringan Komputer', 'pemula'], ['Linux', 'pemula'], ['Komunikasi', 'pemula']], ['Teknik & Rekayasa'], 'smk', 'Kontrak'],
            ['Teknisi Mesin Industri', [['Analisis Data', 'pemula'], ['Microsoft Office', 'pemula'], ['Statistik', 'pemula']], ['Teknik & Rekayasa'], 'smk', 'Full-time'],
            ['Supervisor Produksi', [['Leadership', 'mahir'], ['Excel Advanced', 'menengah'], ['Analisis Data', 'pemula']], ['Teknik & Rekayasa'], 's1', 'Full-time'],
            ['Agronomis Lapangan', [['Analisis Data', 'menengah'], ['Statistik', 'menengah'], ['Budgeting', 'pemula']], ['Agribisnis'], 's1', 'Full-time'],
            ['Penyuluh Pertanian', [['Komunikasi', 'menengah'], ['Analisis Data', 'pemula'], ['Content Creation', 'pemula']], ['Agribisnis', 'Pendidikan'], 's1', 'Kontrak'],
            ['Staff Agribisnis', [['Excel Advanced', 'pemula'], ['Analisis Data', 'pemula'], ['Komunikasi', 'pemula']], ['Agribisnis'], 'smk', 'Full-time'],
            ['Pengelola Kebun Produksi', [['Analisis Data', 'pemula'], ['Budgeting', 'pemula'], ['Leadership', 'pemula']], ['Agribisnis'], 'smk', 'Kontrak'],
            ['QC Pertanian Lapangan', [['Statistik', 'pemula'], ['Excel Advanced', 'menengah'], ['Analisis Data', 'pemula']], ['Agribisnis'], 'd3', 'Full-time'],
            ['Guru Pengajar Privat', [['Komunikasi', 'mahir'], ['Content Creation', 'pemula'], ['Leadership', 'pemula']], ['Pendidikan'], 's1', 'Part-time'],
            ['Tenaga Administrasi Sekolah', [['Microsoft Office', 'menengah'], ['Excel Advanced', 'pemula'], ['Komunikasi', 'pemula']], ['Pendidikan'], 'smk', 'Full-time'],
            ['Pustakawan Digital', [['Microsoft Office', 'menengah'], ['Analisis Data', 'pemula'], ['Content Creation', 'pemula']], ['Pendidikan', 'Seni & Budaya'], 's1', 'Full-time'],
            ['Asisten Tenaga Kesehatan', [['Komunikasi', 'menengah'], ['Microsoft Office', 'pemula'], ['Analisis Data', 'pemula']], ['Kesehatan'], 'smk', 'Kontrak'],
            ['Administrasi Klinik', [['Microsoft Office', 'mahir'], ['Excel Advanced', 'menengah'], ['Komunikasi', 'menengah'], ['Statistik', 'pemula']], ['Kesehatan', 'Keuangan & Akuntansi'], 'd3', 'Full-time'],
            ['Perawat Pendamping', [['Komunikasi', 'mahir'], ['Analisis Data', 'pemula'], ['Content Creation', 'pemula']], ['Kesehatan'], 'smk', 'Kontrak'],
            ['Operator Radio Komunikasi', [['Komunikasi', 'menengah'], ['Jaringan Komputer', 'pemula'], ['Linux', 'pemula']], ['Teknik & Rekayasa'], 'smk', 'Full-time'],
            ['Illustrator Digital', [['Adobe Photoshop', 'mahir'], ['UI/UX Design', 'menengah'], ['Content Creation', 'pemula'], ['Copywriting', 'pemula']], ['Seni & Budaya', 'Desain & Kreatif'], 'smk', 'Kontrak'],
            ['Video Editor', [['Adobe Photoshop', 'menengah'], ['Content Creation', 'menengah'], ['Copywriting', 'pemula']], ['Seni & Budaya', 'Desain & Kreatif'], 'smk', 'Part-time'],
            ['Content Creator Multimedia', [['Content Creation', 'mahir'], ['Digital Marketing', 'menengah'], ['SEO', 'pemula'], ['Wireframing', 'pemula']], ['Desain & Kreatif', 'Pemasaran Digital'], 'sma', 'Part-time'],
            ['Machine Learning Engineer', [['Machine Learning', 'menengah'], ['Python', 'mahir'], ['Statistik', 'menengah'], ['Tableau', 'pemula']], ['Teknologi Informasi'], 's1', 'Full-time'],
            ['Data Scientist', [['Machine Learning', 'mahir'], ['Python', 'mahir'], ['Statistik', 'mahir'], ['Tableau', 'menengah']], ['Teknologi Informasi', 'Pendidikan'], 's1', 'Full-time'],
        ];

        foreach ($templateLowongan as $idx => [$judul, $skills, $minat, $pendidikan, $tipe]) {
            if ($idx % 3 === 2) {
                continue; // sisakan sebagian agar total lowongan aktif mendekati target.
            }
            $p = $perusahaan[$idx % count($perusahaan)];
            $this->lowongan($p, $judul, random_int(0, 9) === 0 ? 'nonaktif' : 'aktif', $tipe, random_int(25, 70) * 100000, random_int(75, 120) * 100000, random_int(10, 60), $skills, $pendidikan, $minat);
        }

        // [judul, topik, level, skill, minat]
        $templatePelatihan = [
            ['Bootcamp Laravel Lanjutan', 'Web Development', 'mahir', ['Laravel', 'PHP', 'MySQL', 'REST API', 'Git'], ['Teknologi Informasi']],
            ['REST API Design', 'Web Development', 'menengah', ['REST API', 'Laravel', 'JavaScript'], ['Teknologi Informasi']],
            ['Dasar Pemrograman PHP', 'Pemrograman', 'pemula', ['PHP', 'MySQL', 'Git'], ['Teknologi Informasi']],
            ['JavaScript Modern (ES6+)', 'Web Development', 'menengah', ['JavaScript', 'Vue.js', 'REST API'], ['Teknologi Informasi']],
            ['React Fundamental', 'Web Development', 'pemula', ['React', 'JavaScript', 'Git'], ['Teknologi Informasi']],
            ['Vue.js Composition API', 'Web Development', 'mahir', ['Vue.js', 'JavaScript', 'REST API'], ['Teknologi Informasi']],
            ['SQL untuk Analis Data', 'Data', 'pemula', ['MySQL', 'PostgreSQL', 'Analisis Data', 'Statistik'], ['Teknologi Informasi']],
            ['PostgreSQL Administration', 'Data', 'menengah', ['PostgreSQL', 'Linux', 'Docker'], ['Teknologi Informasi']],
            ['Machine Learning Dasar', 'Data', 'menengah', ['Machine Learning', 'Python', 'Statistik'], ['Teknologi Informasi']],
            ['Statistik untuk Data Analyst', 'Data', 'pemula', ['Statistik', 'Analisis Data', 'Excel Advanced'], ['Teknologi Informasi']],
            ['Visualisasi Data dengan Python', 'Data', 'menengah', ['Python', 'Tableau', 'Statistik'], ['Teknologi Informasi']],
            ['Administrasi Server Linux', 'Infrastruktur', 'menengah', ['Linux', 'Jaringan Komputer', 'Docker'], ['Teknik & Rekayasa']],
            ['Keamanan Jaringan Dasar', 'Infrastruktur', 'pemula', ['Jaringan Komputer', 'Linux', 'Docker'], ['Teknik & Rekayasa']],
            ['DevOps Fundamental', 'Infrastruktur', 'menengah', ['Docker', 'Linux', 'Git'], ['Teknik & Rekayasa']],
            ['Konfigurasi Router & Switch', 'Infrastruktur', 'menengah', ['Jaringan Komputer', 'Linux'], ['Teknik & Rekayasa']],
            ['UI Design System', 'Desain', 'menengah', ['UI/UX Design', 'Figma', 'Wireframing'], ['Desain & Kreatif']],
            ['UX Research Methods', 'Desain', 'menengah', ['UI/UX Design', 'Prototyping', 'Statistik'], ['Desain & Kreatif']],
            ['Prototyping dengan Figma', 'Desain', 'pemula', ['Figma', 'Prototyping', 'Wireframing'], ['Desain & Kreatif']],
            ['Design Thinking Workshop', 'Desain', 'pemula', ['UI/UX Design', 'Wireframing', 'Komunikasi'], ['Desain & Kreatif']],
            ['Illustrasi Digital Dasar', 'Desain', 'pemula', ['Adobe Photoshop', 'Content Creation'], ['Seni & Budaya', 'Desain & Kreatif']],
            ['Search Engine Optimization', 'Pemasaran', 'menengah', ['SEO', 'Google Analytics', 'Copywriting'], ['Pemasaran Digital']],
            ['Social Media Advertising', 'Pemasaran', 'menengah', ['Digital Marketing', 'Google Analytics', 'Content Creation'], ['Pemasaran Digital']],
            ['Content Marketing Strategy', 'Pemasaran', 'pemula', ['Content Creation', 'Copywriting', 'SEO'], ['Pemasaran Digital']],
            ['Copywriting untuk Landing Page', 'Pemasaran', 'pemula', ['Copywriting', 'SEO', 'Content Creation'], ['Pemasaran Digital']],
            ['Google Analytics untuk Pemula', 'Pemasaran', 'pemula', ['Google Analytics', 'Digital Marketing', 'Statistik'], ['Pemasaran Digital']],
            ['Personal Branding Digital', 'Pemasaran', 'pemula', ['Digital Marketing', 'Content Creation', 'Komunikasi'], ['Pemasaran Digital', 'Bisnis & Wirausaha']],
            ['Public Speaking Tingkat Lanjut', 'Soft Skill', 'menengah', ['Komunikasi', 'Rekrutmen', 'Leadership'], ['Bisnis & Wirausaha']],
            ['Negosiasi Bisnis', 'Soft Skill', 'menengah', ['Komunikasi', 'Budgeting', 'Leadership'], ['Bisnis & Wirausaha']],
            ['Manajemen Waktu & Produktivitas', 'Pengembangan Diri', 'pemula', ['Leadership', 'Excel Advanced', 'Microsoft Office'], ['Pendidikan']],
            ['Team Leadership Dasar', 'Soft Skill', 'pemula', ['Leadership', 'Rekrutmen', 'Komunikasi'], ['Bisnis & Wirausaha']],
            ['Berpikir Kritis & Problem Solving', 'Pengembangan Diri', 'pemula', ['Analisis Data', 'Statistik', 'Komunikasi'], ['Pendidikan']],
            ['Akuntansi Dasar untuk UMKM', 'Keuangan', 'pemula', ['Akuntansi', 'Excel Advanced', 'Budgeting'], ['Keuangan & Akuntansi']],
            ['Penyusunan Laporan Keuangan', 'Keuangan', 'menengah', ['Akuntansi', 'Excel Advanced', 'Audit'], ['Keuangan & Akuntansi']],
            ['Perpajakan Praktis', 'Keuangan', 'menengah', ['Perpajakan', 'Akuntansi', 'Excel Advanced'], ['Keuangan & Akuntansi']],
            ['Excel Tingkat Mahir', 'Produktivitas', 'mahir', ['Excel Advanced', 'Statistik', 'Tableau'], ['Keuangan & Akuntansi', 'Teknologi Informasi']],
            ['Analisis Data dengan Excel', 'Data', 'menengah', ['Excel Advanced', 'Analisis Data', 'Statistik'], ['Keuangan & Akuntansi']],
            ['Kewirausahaan Digital', 'Kewirausahaan', 'pemula', ['Digital Marketing', 'Budgeting', 'Leadership'], ['Bisnis & Wirausaha']],
            ['Manajemen Operasional Bisnis', 'Manajemen', 'menengah', ['Leadership', 'Budgeting', 'Excel Advanced'], ['Bisnis & Wirausaha']],
            ['Dasar Agribisnis Modern', 'Agribisnis', 'pemula', ['Analisis Data', 'Statistik', 'Excel Advanced'], ['Agribisnis']],
            ['Teknologi Pertanian Presisi', 'Agribisnis', 'menengah', ['Python', 'Analisis Data', 'Statistik'], ['Agribisnis', 'Teknologi Informasi']],
            ['Pemasaran Produk Pertanian', 'Agribisnis', 'pemula', ['Digital Marketing', 'SEO', 'Content Creation'], ['Agribisnis', 'Pemasaran Digital']],
            ['Pendidikan Karakter untuk Pengajar', 'Pendidikan', 'pemula', ['Komunikasi', 'Rekrutmen', 'Leadership'], ['Pendidikan']],
            ['Media Pembelajaran Interaktif', 'Pendidikan', 'menengah', ['Content Creation', 'UI/UX Design', 'Wireframing'], ['Pendidikan']],
            ['Komunikasi Kesehatan', 'Kesehatan', 'pemula', ['Komunikasi', 'Content Creation', 'Statistik'], ['Kesehatan']],
            ['Pertolongan Pertama Dasar', 'Kesehatan', 'pemula', ['Komunikasi', 'Analisis Data'], ['Kesehatan']],
            ['Literasi Digital & Etika Data', 'Teknologi Informasi', 'pemula', ['Microsoft Office', 'Statistik', 'Analisis Data'], ['Teknologi Informasi', 'Pendidikan']],
            ['Manajemen Proyek Perangkat Lunak', 'Manajemen', 'menengah', ['Leadership', 'Budgeting', 'Git'], ['Teknologi Informasi', 'Bisnis & Wirausaha']],
            ['Editing Video Profesional', 'Desain', 'menengah', ['Adobe Photoshop', 'Content Creation', 'Copywriting'], ['Seni & Budaya', 'Desain & Kreatif']],
            ['Payroll & Administrasi HR', 'Manajemen', 'menengah', ['Payroll', 'Rekrutmen', 'Excel Advanced'], ['Keuangan & Akuntansi', 'Bisnis & Wirausaha']],
            ['Audit Internal Praktis', 'Keuangan', 'menengah', ['Audit', 'Akuntansi', 'Excel Advanced'], ['Keuangan & Akuntansi']],
            ['Machine Learning Terapan', 'Data', 'mahir', ['Machine Learning', 'Python', 'Statistik', 'Tableau'], ['Teknologi Informasi']],
        ];

        $instruktur = ['Budi Santoso', 'Siti Rahma', 'Agus Wijaya', 'Dina Putri', 'Rudi Hartono', 'Fajar Nugroho', 'Andi Wibowo', 'Rizki Pratama', 'Sari Dewi', 'Hendra Wijaya', 'Rina Anggraini', 'Maya Putri', 'Dinda Sari', 'Wulandari'];
        foreach ($templatePelatihan as $idx => [$judul, $topik, $level, $skills, $minat]) {
            if ($idx % 4 === 3) {
                continue; // sisakan sebagian agar total pelatihan published mendekati target.
            }
            $mulai = random_int(3, 40);
            $this->pelatihan($judul, random_int(0, 9) === 0 ? 'draft' : 'published', $topik, $level, $instruktur[$idx % count($instruktur)], $mulai, $mulai + random_int(3, 14), random_int(15, 60), $skills, $minat);
        }
    }
}
