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
        User::create([
            'name' => 'Admin CDC',
            'email' => 'admin@bedushub.test',
            'password' => 'password',
            'role' => User::ROLE_ADMIN,
        ]);

        $perusahaanUsers = [
            ['name' => 'PT Teknologi Nusantara', 'email' => 'perusahaan@bedushub.test'],
            ['name' => 'CV Karya Mandiri', 'email' => 'perusahaan2@bedushub.test'],
            ['name' => 'PT Agro Sejahtera', 'email' => 'perusahaan3@bedushub.test'],
            ['name' => 'PT Kreasi Digital', 'email' => 'perusahaan4@bedushub.test'],
        ];
        foreach ($perusahaanUsers as $pu) {
            User::create([
                'name' => $pu['name'],
                'email' => $pu['email'],
                'password' => 'password',
                'role' => User::ROLE_PERUSAHAAN,
            ]);
        }

        $smk = Institusi::where('nama', 'SMK Negeri 1 Bedus')->first();
        $pt = Institusi::where('nama', 'Universitas BEDUS')->first();

        $jurusanRpl = Jurusan::where('kode', 'RPL')->first();
        $jurusanTkj = Jurusan::where('kode', 'TKJ')->first();
        $jurusanMm = Jurusan::where('kode', 'MM')->first();
        $jurusanAk = Jurusan::where('kode', 'AK')->first();

        $prodiTi = ProgramStudi::where('kode', 'TI')->first();
        $prodiSi = ProgramStudi::where('kode', 'SI')->first();
        $prodiTk = ProgramStudi::where('kode', 'TK')->first();
        $prodiMj = ProgramStudi::where('kode', 'MJ')->first();
        $prodiAkt = ProgramStudi::where('kode', 'AKT')->first();

        // Siswa (SMK)
        $this->peserta(
            ['name' => 'Andi Saputra', 'email' => 'siswa@bedushub.test', 'role' => User::ROLE_SISWA],
            ['jenis_kelamin' => 'l', 'tanggal_lahir' => '2007-03-12', 'domisili_kecamatan' => 'Bedus', 'jenjang' => 'smk', 'institusi_id' => $smk->id, 'jurusan_id' => $jurusanRpl->id, 'status' => 'aktif', 'no_telp' => '0812-1111-0001'],
            [['PHP', 'menengah'], ['MySQL', 'pemula'], ['JavaScript', 'pemula']],
            ['Teknologi Informasi']
        );
        $this->peserta(
            ['name' => 'Budi Hartono', 'email' => 'siswa2@bedushub.test', 'role' => User::ROLE_SISWA],
            ['jenis_kelamin' => 'l', 'tanggal_lahir' => '2006-11-20', 'domisili_kecamatan' => 'Bedus', 'jenjang' => 'smk', 'institusi_id' => $smk->id, 'jurusan_id' => $jurusanTkj->id, 'status' => 'aktif', 'no_telp' => '0812-1111-0002'],
            [['Jaringan Komputer', 'menengah'], ['Microsoft Office', 'pemula']],
            ['Teknik & Rekayasa']
        );
        $this->peserta(
            ['name' => 'Citra Lestari', 'email' => 'siswa3@bedushub.test', 'role' => User::ROLE_SISWA],
            ['jenis_kelamin' => 'p', 'tanggal_lahir' => '2007-07-05', 'domisili_kecamatan' => 'Bedus', 'jenjang' => 'smk', 'institusi_id' => $smk->id, 'jurusan_id' => $jurusanMm->id, 'status' => 'aktif', 'no_telp' => '0812-1111-0003'],
            [['UI/UX Design', 'pemula'], ['Digital Marketing', 'pemula']],
            ['Desain & Kreatif']
        );
        $this->peserta(
            ['name' => 'Dewi Anggraini', 'email' => 'siswa4@bedushub.test', 'role' => User::ROLE_SISWA],
            ['jenis_kelamin' => 'p', 'tanggal_lahir' => '2007-01-15', 'domisili_kecamatan' => 'Bedus', 'jenjang' => 'smk', 'institusi_id' => $smk->id, 'jurusan_id' => $jurusanAk->id, 'status' => 'aktif', 'no_telp' => '0812-1111-0004'],
            [['Microsoft Office', 'menengah'], ['Komunikasi', 'pemula']],
            ['Keuangan & Akuntansi']
        );

        // Mahasiswa (PT)
        $this->peserta(
            ['name' => 'Eko Prasetyo', 'email' => 'mahasiswa@bedushub.test', 'role' => User::ROLE_MAHASISWA],
            ['jenis_kelamin' => 'l', 'tanggal_lahir' => '2004-06-18', 'domisili_kecamatan' => 'Bedus', 'jenjang' => 's1', 'institusi_id' => $pt->id, 'prodi_id' => $prodiTi->id, 'status' => 'aktif', 'no_telp' => '0813-2222-0001'],
            [['Laravel', 'menengah'], ['MySQL', 'menengah'], ['Python', 'pemula']],
            ['Teknologi Informasi', 'Bisnis & Wirausaha']
        );
        $this->peserta(
            ['name' => 'Fitri Handayani', 'email' => 'mahasiswa2@bedushub.test', 'role' => User::ROLE_MAHASISWA],
            ['jenis_kelamin' => 'p', 'tanggal_lahir' => '2004-09-22', 'domisili_kecamatan' => 'Bedus', 'jenjang' => 's1', 'institusi_id' => $pt->id, 'prodi_id' => $prodiSi->id, 'status' => 'aktif', 'no_telp' => '0813-2222-0002'],
            [['Analisis Data', 'pemula'], ['MySQL', 'pemula']],
            ['Teknologi Informasi']
        );
        $this->peserta(
            ['name' => 'Galih Ramadhan', 'email' => 'mahasiswa3@bedushub.test', 'role' => User::ROLE_MAHASISWA],
            ['jenis_kelamin' => 'l', 'tanggal_lahir' => '2005-02-10', 'domisili_kecamatan' => 'Bedus', 'jenjang' => 'd3', 'institusi_id' => $pt->id, 'prodi_id' => $prodiTk->id, 'status' => 'aktif', 'no_telp' => '0813-2222-0003'],
            [['Jaringan Komputer', 'menengah'], ['Python', 'pemula']],
            ['Teknik & Rekayasa']
        );
        $this->peserta(
            ['name' => 'Hana Safira', 'email' => 'mahasiswa4@bedushub.test', 'role' => User::ROLE_MAHASISWA],
            ['jenis_kelamin' => 'p', 'tanggal_lahir' => '2004-12-01', 'domisili_kecamatan' => 'Bedus', 'jenjang' => 's1', 'institusi_id' => $pt->id, 'prodi_id' => $prodiMj->id, 'status' => 'aktif', 'no_telp' => '0813-2222-0004'],
            [['Digital Marketing', 'menengah'], ['Leadership', 'pemula'], ['Komunikasi', 'menengah']],
            ['Bisnis & Wirausaha', 'Pemasaran Digital']
        );

        // Alumni
        $this->peserta(
            ['name' => 'Indra Gunawan', 'email' => 'alumni@bedushub.test', 'role' => User::ROLE_ALUMNI],
            ['jenis_kelamin' => 'l', 'tanggal_lahir' => '2001-04-14', 'domisili_kecamatan' => 'Bedus', 'jenjang' => 's1', 'institusi_id' => $pt->id, 'prodi_id' => $prodiTi->id, 'tahun_lulus' => 2023, 'status' => 'lulus', 'no_telp' => '0814-3333-0001'],
            [['Laravel', 'mahir'], ['React', 'menengah'], ['PostgreSQL', 'menengah']],
            ['Teknologi Informasi']
        );
        $this->peserta(
            ['name' => 'Joko Susilo', 'email' => 'alumni2@bedushub.test', 'role' => User::ROLE_ALUMNI],
            ['jenis_kelamin' => 'l', 'tanggal_lahir' => '2002-08-09', 'domisili_kecamatan' => 'Bedus', 'jenjang' => 'smk', 'institusi_id' => $smk->id, 'jurusan_id' => $jurusanRpl->id, 'tahun_lulus' => 2022, 'status' => 'lulus', 'no_telp' => '0814-3333-0002'],
            [['PHP', 'mahir'], ['JavaScript', 'menengah']],
            ['Teknologi Informasi']
        );
        $this->peserta(
            ['name' => 'Kartika Sari', 'email' => 'alumni3@bedushub.test', 'role' => User::ROLE_ALUMNI],
            ['jenis_kelamin' => 'p', 'tanggal_lahir' => '2001-11-30', 'domisili_kecamatan' => 'Bedus', 'jenjang' => 's1', 'institusi_id' => $pt->id, 'prodi_id' => $prodiAkt->id, 'tahun_lulus' => 2023, 'status' => 'lulus', 'no_telp' => '0814-3333-0003'],
            [['Microsoft Office', 'mahir'], ['Analisis Data', 'menengah']],
            ['Keuangan & Akuntansi']
        );
        $this->peserta(
            ['name' => 'Lina Marlina', 'email' => 'alumni4@bedushub.test', 'role' => User::ROLE_ALUMNI],
            ['jenis_kelamin' => 'p', 'tanggal_lahir' => '2002-03-17', 'domisili_kecamatan' => 'Bedus', 'jenjang' => 'd3', 'institusi_id' => $pt->id, 'prodi_id' => $prodiTk->id, 'tahun_lulus' => 2022, 'status' => 'lulus', 'no_telp' => '0814-3333-0004'],
            [['Jaringan Komputer', 'mahir'], ['Python', 'menengah']],
            ['Teknik & Rekayasa']
        );
    }

    private function peserta(array $user, array $profile, array $skills, array $minats): void
    {
        $u = User::create(array_merge(['password' => 'password'], $user));

        Profile::create(array_merge(['user_id' => $u->id], $profile));

        foreach ($skills as [$nama, $level]) {
            $skill = Skill::where('nama', $nama)->first();
            if ($skill) {
                UserSkill::create(['user_id' => $u->id, 'skill_id' => $skill->id, 'level' => $level]);
            }
        }

        foreach ($minats as $nama) {
            $minat = KategoriMinat::where('nama', $nama)->first();
            if ($minat) {
                UserMinat::create(['user_id' => $u->id, 'kategori_minat_id' => $minat->id]);
            }
        }
    }
}
