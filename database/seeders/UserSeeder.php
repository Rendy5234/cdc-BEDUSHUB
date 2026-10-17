<?php

namespace Database\Seeders;

use App\Models\Institusi;
use App\Models\Jurusan;
use App\Models\KategoriMinat;
use App\Models\Profile;
use App\Models\ProgramStudi;
use App\Models\Skill;
use App\Models\User;
use App\Models\UserMinat;
use App\Models\UserSkill;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::create(['name' => 'Admin CDC', 'email' => 'admin@bedushub.test', 'password' => 'password', 'role' => User::ROLE_ADMIN]);
        foreach ([['PT Teknologi Nusantara', 'perusahaan@bedushub.test'], ['CV Karya Mandiri', 'perusahaan2@bedushub.test'], ['PT Agro Sejahtera', 'perusahaan3@bedushub.test'], ['PT Kreasi Digital', 'perusahaan4@bedushub.test']] as [$nm,$em]) {
            User::create(['name' => $nm, 'email' => $em, 'password' => 'password', 'role' => User::ROLE_PERUSAHAAN]);
        }
        $smk = Institusi::where('nama', 'SMK Negeri 1 Bedus')->first();
        $sma = Institusi::where('nama', 'SMA Negeri 1 Bedus')->first();
        $pt = Institusi::where('nama', 'Universitas BEDUS')->first();
        $jRpl = Jurusan::where('kode', 'RPL')->first();
        $jTkj = Jurusan::where('kode', 'TKJ')->first();
        $jMm = Jurusan::where('kode', 'MM')->first();
        $jAk = Jurusan::where('kode', 'AK')->first();
        $jIpa = Jurusan::where('kode', 'IPA')->first();
        $jIps = Jurusan::where('kode', 'IPS')->first();
        $pTi = ProgramStudi::where('kode', 'TI')->first();
        $pSi = ProgramStudi::where('kode', 'SI')->first();
        $pTk = ProgramStudi::where('kode', 'TK')->first();
        $pMj = ProgramStudi::where('kode', 'MJ')->first();
        $pAkt = ProgramStudi::where('kode', 'AKT')->first();
        // Siswa SMK 10 orang
        $sSmk = [
            ['Andi Saputra', 'siswa@bedushub.test', 'l', '2007-03-12', 'Bojonegoro', $jRpl, ['PHP:menengah', 'MySQL:pemula', 'JavaScript:pemula', 'Laravel:pemula'], ['Teknologi Informasi']],
            ['Budi Hartono', 'siswa2@bedushub.test', 'l', '2006-11-20', 'Kalitidu', $jTkj, ['Jaringan Komputer:menengah', 'Microsoft Office:pemula', 'Linux:pemula'], ['Teknik & Rekayasa']],
            ['Citra Lestari', 'siswa3@bedushub.test', 'p', '2007-07-05', 'Trucuk', $jMm, ['UI/UX Design:pemula', 'Digital Marketing:pemula', 'Copywriting:pemula'], ['Desain & Kreatif', 'Pemasaran Digital']],
            ['Dewi Anggraini', 'siswa4@bedushub.test', 'p', '2007-01-15', 'Padangan', $jAk, ['Microsoft Office:menengah', 'Komunikasi:pemula', 'Analisis Data:pemula'], ['Keuangan & Akuntansi']],
            ['Eko Prasetyo', 'siswa5@bedushub.test', 'l', '2007-05-22', 'Bojonegoro', $jRpl, ['PHP:pemula', 'JavaScript:pemula', 'MySQL:pemula', 'Vue.js:pemula'], ['Teknologi Informasi', 'Bisnis & Wirausaha']],
            ['Fitri Handayani', 'siswa6@bedushub.test', 'p', '2006-09-30', 'Balen', $jMm, ['UI/UX Design:menengah', 'Figma:pemula', 'Digital Marketing:menengah'], ['Desain & Kreatif']],
            ['Gilang Permana', 'siswa7@bedushub.test', 'l', '2007-02-08', 'Ngasem', $jTkj, ['Jaringan Komputer:menengah', 'Linux:menengah', 'Microsoft Office:menengah'], ['Teknik & Rekayasa']],
            ['Hesti Rahayu', 'siswa8@bedushub.test', 'p', '2006-12-14', 'Kedungadem', $jAk, ['Microsoft Office:mahir', 'Analisis Data:pemula', 'Komunikasi:menengah'], ['Keuangan & Akuntansi', 'Bisnis & Wirausaha']],
            ['Irvan Maulana', 'siswa9@bedushub.test', 'l', '2007-08-19', 'Gayam', $jRpl, ['PHP:menengah', 'Laravel:menengah', 'MySQL:menengah', 'PostgreSQL:pemula'], ['Teknologi Informasi']],
            ['Juliana Putri', 'siswa10@bedushub.test', 'p', '2006-04-27', 'Dander', $jMm, ['Digital Marketing:menengah', 'Copywriting:pemula', 'UI/UX Design:pemula'], ['Pemasaran Digital', 'Desain & Kreatif']],
        ];
        foreach ($sSmk as $i => [$nm,$em,$jk,$lb,$kec,$jur,$sk,$mn]) {
            $this->peserta(['name' => $nm, 'email' => $em, 'role' => User::ROLE_SISWA],
                ['jenis_kelamin' => $jk, 'tanggal_lahir' => $lb, 'domisili_kecamatan' => $kec, 'jenjang' => 'smk',
                    'institusi_id' => $smk->id, 'jurusan_id' => $jur->id, 'status' => 'aktif', 'no_telp' => '0812-111-'.str_pad($i + 1, 4, '0', STR_PAD_LEFT)], $sk, $mn);
        }
        // Siswa SMA 3 orang
        $sSma = [
            ['Kevin Ardiansyah', 'siswa11@bedushub.test', 'l', '2007-06-03', 'Bojonegoro', $jIpa, ['Microsoft Office:pemula', 'Analisis Data:pemula', 'Python:pemula'], ['Teknologi Informasi', 'Bisnis & Wirausaha']],
            ['Laila Sari', 'siswa12@bedushub.test', 'p', '2007-10-11', 'Trucuk', $jIps, ['Komunikasi:menengah', 'Microsoft Office:pemula', 'Leadership:pemula'], ['Bisnis & Wirausaha']],
            ['Muhamad Rizal', 'siswa13@bedushub.test', 'l', '2006-07-17', 'Malo', $jIpa, ['Python:pemula', 'Analisis Data:pemula', 'Microsoft Office:menengah'], ['Teknologi Informasi']],
        ];
        foreach ($sSma as $i => [$nm,$em,$jk,$lb,$kec,$jur,$sk,$mn]) {
            $this->peserta(['name' => $nm, 'email' => $em, 'role' => User::ROLE_SISWA],
                ['jenis_kelamin' => $jk, 'tanggal_lahir' => $lb, 'domisili_kecamatan' => $kec, 'jenjang' => 'sma',
                    'institusi_id' => $sma->id, 'jurusan_id' => $jur->id, 'status' => 'aktif', 'no_telp' => '0812-222-'.str_pad($i + 1, 4, '0', STR_PAD_LEFT)], $sk, $mn);
        }
        // Mahasiswa 8 orang
        $mhs = [
            ['Erna Febriani', 'mahasiswa@bedushub.test', 'p', '2005-06-18', 'Bojonegoro', $pSi->id, 's1', ['Laravel:pemula', 'PHP:menengah', 'MySQL:pemula'], ['Teknologi Informasi']],
            ['Fahmi Rizqullah', 'mahasiswa2@bedushub.test', 'l', '2004-09-25', 'Malo', $pTi->id, 's1', ['React:menengah', 'JavaScript:menengah', 'Analisis Data:pemula'], ['Teknologi Informasi']],
            ['Galih Ramadhan', 'mahasiswa3@bedushub.test', 'l', '2005-02-10', 'Bojonegoro', $pTk->id, 'd3', ['Jaringan Komputer:menengah', 'Python:pemula', 'Linux:pemula'], ['Teknik & Rekayasa']],
            ['Hana Safira', 'mahasiswa4@bedushub.test', 'p', '2004-12-01', 'Padangan', $pMj->id, 's1', ['Digital Marketing:menengah', 'Leadership:pemula', 'Komunikasi:menengah'], ['Bisnis & Wirausaha', 'Pemasaran Digital']],
            ['Ivan Setiawan', 'mahasiswa5@bedushub.test', 'l', '2004-04-15', 'Balen', $pTi->id, 's1', ['Python:menengah', 'Analisis Data:menengah', 'PostgreSQL:pemula', 'MySQL:pemula'], ['Teknologi Informasi']],
            ['Jasmine Kirana', 'mahasiswa6@bedushub.test', 'p', '2005-08-22', 'Ngasem', $pSi->id, 's1', ['UI/UX Design:menengah', 'Figma:menengah', 'React:pemula'], ['Desain & Kreatif', 'Teknologi Informasi']],
            ['Kemal Fauzan', 'mahasiswa7@bedushub.test', 'l', '2003-11-07', 'Bojonegoro', $pMj->id, 's1', ['Komunikasi:menengah', 'Leadership:menengah', 'Microsoft Office:mahir'], ['Bisnis & Wirausaha']],
            ['Luthfia Amana', 'mahasiswa8@bedushub.test', 'p', '2004-03-30', 'Dander', $pAkt->id, 's1', ['Analisis Data:menengah', 'Microsoft Office:mahir', 'Python:pemula'], ['Keuangan & Akuntansi']],
        ];
        foreach ($mhs as $i => [$nm,$em,$jk,$lb,$kec,$prodiId,$jenj,$sk,$mn]) {
            $this->peserta(['name' => $nm, 'email' => $em, 'role' => User::ROLE_MAHASISWA],
                ['jenis_kelamin' => $jk, 'tanggal_lahir' => $lb, 'domisili_kecamatan' => $kec, 'jenjang' => $jenj,
                    'institusi_id' => $pt->id, 'prodi_id' => $prodiId, 'status' => 'aktif', 'no_telp' => '0813-111-'.str_pad($i + 1, 4, '0', STR_PAD_LEFT)], $sk, $mn);
        }
        // Alumni 7 orang
        // format: [nm,em,jk,lb,kec,institasi_obj,ref_id,jenjang,tahun_lulus,skills,minats]
        $alm = [
            ['Indra Gunawan', 'alumni@bedushub.test', 'l', '2001-04-14', 'Bojonegoro', $pt, $pTi->id, 's1', 2023, ['Laravel:mahir', 'React:menengah', 'PostgreSQL:menengah'], ['Teknologi Informasi']],
            ['Joko Susilo', 'alumni2@bedushub.test', 'l', '2002-08-09', 'Kalitidu', $smk, $jRpl->id, 'smk', 2022, ['PHP:mahir', 'JavaScript:menengah'], ['Teknologi Informasi']],
            ['Kartika Sari', 'alumni3@bedushub.test', 'p', '2001-11-30', 'Bojonegoro', $pt, $pAkt->id, 's1', 2023, ['Microsoft Office:mahir', 'Analisis Data:menengah'], ['Keuangan & Akuntansi']],
            ['Lina Marlina', 'alumni4@bedushub.test', 'p', '2002-03-17', 'Padangan', $pt, $pTk->id, 'd3', 2022, ['Jaringan Komputer:mahir', 'Python:menengah'], ['Teknik & Rekayasa']],
            ['Manda Pratiwi', 'alumni5@bedushub.test', 'p', '2001-07-08', 'Balen', $pt, $pMj->id, 's1', 2023, ['Digital Marketing:mahir', 'Komunikasi:mahir', 'Leadership:menengah'], ['Pemasaran Digital', 'Bisnis & Wirausaha']],
            ['Nanda Kurnia', 'alumni6@bedushub.test', 'l', '2002-01-25', 'Ngasem', $pt, $pTi->id, 's1', 2024, ['Python:menengah', 'Analisis Data:menengah', 'MySQL:menengah'], ['Teknologi Informasi']],
            ['Okta Fitriani', 'alumni7@bedushub.test', 'p', '2001-09-14', 'Gayam', $smk, $jMm->id, 'smk', 2022, ['UI/UX Design:mahir', 'Digital Marketing:menengah', 'Figma:menengah'], ['Desain & Kreatif', 'Pemasaran Digital']],
        ];
        foreach ($alm as $i => [$nm,$em,$jk,$lb,$kec,$ins,$refId,$jenj,$lulus,$sk,$mn]) {
            $profile = ['jenis_kelamin' => $jk, 'tanggal_lahir' => $lb, 'domisili_kecamatan' => $kec,
                'jenjang' => $jenj, 'institusi_id' => $ins->id, 'tahun_lulus' => $lulus, 'status' => 'lulus',
                'no_telp' => '0814-111-'.str_pad($i + 1, 4, '0', STR_PAD_LEFT)];
            if ($ins->jenis === 'smk') {
                $profile['jurusan_id'] = $refId;
            } else {
                $profile['prodi_id'] = $refId;
            }
            $this->peserta(['name' => $nm, 'email' => $em, 'role' => User::ROLE_ALUMNI], $profile, $sk, $mn);
        }

        // Eksperimen Fase B: generate peserta tambahan agar dataset dapat di-scale.
        $this->generatePesertaTambahan($sSmk, $jRpl, $jTkj, $jMm, $jAk, $jIpa, $jIps, $pTk, $pMj, $pAkt);
    }

    /**
     * Generate 77 peserta tambahan (37 siswa, 22 mahasiswa, 18 alumni) agar
     * tersedia cukup pasangan (user x item) untuk training ALS dengan dataset besar.
     *
     * Nomor urut email dipilih di atas nomor yang sudah dipakai peserta eksplisit
     * (siswa 1-13, mahasiswa 1-8, alumni 1-7) agar tidak melanggar unique:users.email.
     *
     * Skill & minat dibuat saling konsisten (profil minat -> skill relevan) supaya
     * pola yang dipelajari ALS tetap bermakna dan bukan noise.
     *
     * @param  array<int, array<int, mixed>>  $sSmk  Baris siswa SMK eksplisit (dipakai untuk ambil institusi).
     * @param  mixed  $jRpl  Jurusan RPL.
     * @param  mixed  $jTkj  Jurusan TKJ.
     * @param  mixed  $jMm  Jurusan MM.
     * @param  mixed  $jAk  Jurusan AK.
     * @param  mixed  $jIpa  Jurusan IPA.
     * @param  mixed  $jIps  Jurusan IPS.
     * @param  mixed  $pTk  Prodi TK.
     * @param  mixed  $pMj  Prodi MJ.
     * @param  mixed  $pAkt  Prodi AKT.
     */
    private function generatePesertaTambahan(array $sSmk, $jRpl, $jTkj, $jMm, $jAk, $jIpa, $jIps, $pTk, $pMj, $pAkt): void
    {
        $kecamatan = ['Bojonegoro', 'Kalitidu', 'Trucuk', 'Padangan', 'Balen', 'Ngasem', 'Kedungadem', 'Gayam', 'Dander', 'Malo', 'Sumberrejo', 'Baureno'];
        $namaDepan = ['Ahmad', 'Bella', 'Cahyo', 'Dian', 'Eka', 'Fajar', 'Gita', 'Hendra', 'Indah', 'Joko', 'Kirana', 'Lukman', 'Maya', 'Nadia', 'Oscar', 'Putri', 'Rafi', 'Sinta', 'Taufik', 'Umi', 'Vina', 'Wahyu', 'Yanti', 'Zaki'];
        $namaBelakang = ['Pratama', 'Safitri', 'Nugroho', 'Anggraini', 'Setiawan', 'Rahmawati', 'Kurniawan', 'Wulandari', 'Firmansyah', 'Handayani'];
        $levels = ['pemula', 'menengah', 'mahir'];
        $jurusanSmk = [$jRpl, $jTkj, $jMm, $jAk, $jIpa, $jIps];
        $prodiPt = [$pTk, $pMj, $pAkt];

        // Pemetaan minat -> skill relevan (menjaga konsistensi pola untuk ALS).
        $profilMinat = [
            ['Teknologi Informasi', ['PHP', 'Laravel', 'JavaScript', 'React', 'Vue.js', 'MySQL', 'PostgreSQL', 'Python']],
            ['Desain & Kreatif', ['UI/UX Design', 'Digital Marketing', 'Komunikasi']],
            ['Pemasaran Digital', ['Digital Marketing', 'Komunikasi', 'Analisis Data']],
            ['Keuangan & Akuntansi', ['Microsoft Office', 'Analisis Data', 'Komunikasi']],
            ['Teknik & Rekayasa', ['Jaringan Komputer', 'Microsoft Office', 'Python']],
            ['Bisnis & Wirausaha', ['Komunikasi', 'Leadership', 'Digital Marketing']],
            ['Agribisnis', ['Analisis Data', 'Microsoft Office', 'Komunikasi']],
            ['Pendidikan', ['Komunikasi', 'Leadership', 'Microsoft Office']],
            ['Kesehatan', ['Komunikasi', 'Analisis Data']],
            ['Seni & Budaya', ['UI/UX Design', 'Komunikasi']],
        ];

        // 37 siswa SMK tambahan (total 50, karena siswa eksplisit sudah memakai nomor 1-13).
        for ($i = 0; $i < 37; $i++) {
            $n = $i + 14;
            [$skills, $minats] = $this->skillMinatAcak($profilMinat, $levels);
            $this->peserta(
                ['name' => $namaDepan[array_rand($namaDepan)].' '.$namaBelakang[array_rand($namaBelakang)], 'email' => "siswa{$n}@bedushub.test", 'role' => User::ROLE_SISWA],
                ['jenis_kelamin' => random_int(0, 1) ? 'l' : 'p',
                    'tanggal_lahir' => '2006-'.str_pad((string) random_int(1, 12), 2, '0', STR_PAD_LEFT).'-'.str_pad((string) random_int(1, 28), 2, '0', STR_PAD_LEFT),
                    'domisili_kecamatan' => $kecamatan[array_rand($kecamatan)], 'jenjang' => 'smk',
                    'institusi_id' => $sSmk[0][5]->id, 'jurusan_id' => $jurusanSmk[array_rand($jurusanSmk)]->id,
                    'status' => 'aktif', 'no_telp' => '0815-200-'.str_pad((string) $n, 4, '0', STR_PAD_LEFT)],
                $skills, $minats
            );
        }

        // 22 mahasiswa tambahan (total 30).
        for ($i = 0; $i < 22; $i++) {
            $n = $i + 9;
            [$skills, $minats] = $this->skillMinatAcak($profilMinat, $levels);
            $prodi = $prodiPt[array_rand($prodiPt)];
            $this->peserta(
                ['name' => $namaDepan[array_rand($namaDepan)].' '.$namaBelakang[array_rand($namaBelakang)], 'email' => "mahasiswa{$n}@bedushub.test", 'role' => User::ROLE_MAHASISWA],
                ['jenis_kelamin' => random_int(0, 1) ? 'l' : 'p',
                    'tanggal_lahir' => '2003-'.str_pad((string) random_int(1, 12), 2, '0', STR_PAD_LEFT).'-'.str_pad((string) random_int(1, 28), 2, '0', STR_PAD_LEFT),
                    'domisili_kecamatan' => $kecamatan[array_rand($kecamatan)], 'jenjang' => random_int(0, 4) ? 's1' : 'd3',
                    'institusi_id' => $prodi->institusi_id, 'prodi_id' => $prodi->id,
                    'status' => 'aktif', 'no_telp' => '0816-200-'.str_pad((string) $n, 4, '0', STR_PAD_LEFT)],
                $skills, $minats
            );
        }

        // 18 alumni tambahan (total 25).
        for ($i = 0; $i < 18; $i++) {
            $n = $i + 8;
            [$skills, $minats] = $this->skillMinatAcak($profilMinat, $levels);
            $jenjangAlumni = random_int(0, 1) ? 'smk' : 's1';
            $prodi = $prodiPt[array_rand($prodiPt)];
            $profil = ['jenis_kelamin' => random_int(0, 1) ? 'l' : 'p',
                'tanggal_lahir' => '2001-'.str_pad((string) random_int(1, 12), 2, '0', STR_PAD_LEFT).'-'.str_pad((string) random_int(1, 28), 2, '0', STR_PAD_LEFT),
                'domisili_kecamatan' => $kecamatan[array_rand($kecamatan)], 'jenjang' => $jenjangAlumni,
                'institusi_id' => $jenjangAlumni === 'smk' ? $sSmk[0][5]->id : $prodi->institusi_id,
                'tahun_lulus' => random_int(2022, 2025), 'status' => 'lulus',
                'no_telp' => '0817-200-'.str_pad((string) $n, 4, '0', STR_PAD_LEFT)];
            if ($jenjangAlumni === 'smk') {
                $profil['jurusan_id'] = $jurusanSmk[array_rand($jurusanSmk)]->id;
            } else {
                $profil['prodi_id'] = $prodi->id;
            }
            $this->peserta(['name' => $namaDepan[array_rand($namaDepan)].' '.$namaBelakang[array_rand($namaBelakang)], 'email' => "alumni{$n}@bedushub.test", 'role' => User::ROLE_ALUMNI], $profil, $skills, $minats);
        }
    }

    /**
     * Pilih kombinasi skill (2-5) & minat (1-3) acak yang saling konsisten.
     *
     * @param  array<int, array{0: string, 1: array<int, string>}>  $profilMinat
     * @param  array<int, string>  $levels
     * @return array{0: array<int, string>, 1: array<int, string>} [skills, minats]
     */
    private function skillMinatAcak(array $profilMinat, array $levels): array
    {
        $profil = $profilMinat[array_rand($profilMinat)];
        $skillPool = array_values(array_unique($profil[1]));
        $jmlSkill = random_int(2, 5);
        $dipilih = (array) array_rand(array_flip($skillPool), min($jmlSkill, count($skillPool)));
        $skills = [];
        foreach ($dipilih as $s) {
            $skills[] = $s.':'.$levels[array_rand($levels)];
        }
        $minats = [$profil[0]];
        if (random_int(1, 3) === 1) {
            $minatKedua = $profilMinat[array_rand($profilMinat)][0];
            if ($minatKedua !== $profil[0]) {
                $minats[] = $minatKedua;
            }
        }

        return [$skills, $minats];
    }

    private function peserta(array $user, array $profile, array $skills, array $minats): void
    {
        $u = User::create(array_merge(['password' => 'password'], $user));
        Profile::create(array_merge(['user_id' => $u->id], $profile));
        foreach ($skills as $s) {
            if (is_string($s)) {
                [$nm,$lv] = explode(':', $s, 2);
            } else {
                [$nm,$lv] = $s;
            }
            $sk = Skill::where('nama', $nm)->first();
            if ($sk) {
                UserSkill::create(['user_id' => $u->id, 'skill_id' => $sk->id, 'level' => $lv]);
            }
        }
        foreach ($minats as $nm) {
            $mn = KategoriMinat::where('nama',$nm)->first();
            if ($mn) {
                UserMinat::create(['user_id' => $u->id, 'kategori_minat_id' => $mn->id]);
            }
        }
    }
}
