<?php

namespace Database\Seeders;

use App\Models\Fakultas;
use App\Models\Institusi;
use App\Models\Jurusan;
use App\Models\KategoriMinat;
use App\Models\Pengaturan;
use App\Models\PotensiDaerah;
use App\Models\ProgramStudi;
use App\Models\SektorUnggulan;
use App\Models\Skill;
use Illuminate\Database\Seeder;

class ReferenceDataSeeder extends Seeder
{
    public function run(): void
    {
        $smk = Institusi::create([
            'nama' => 'SMK Negeri 1 Bedus',
            'jenis' => 'smk',
            'alamat' => 'Jl. Pendidikan No. 1, Bedus',
            'kecamatan' => 'Bedus',
            'status' => 'aktif',
        ]);

        $sma = Institusi::create([
            'nama' => 'SMA Negeri 1 Bedus',
            'jenis' => 'sma',
            'alamat' => 'Jl. Merdeka No. 2, Bedus',
            'kecamatan' => 'Bedus',
            'status' => 'aktif',
        ]);

        $pt = Institusi::create([
            'nama' => 'Universitas BEDUS',
            'jenis' => 'pt',
            'alamat' => 'Jl. Raya Kampus No. 3, Bedus',
            'kecamatan' => 'Bedus',
            'status' => 'aktif',
        ]);

        $jurusan = [
            ['institusi_id' => $smk->id, 'nama' => 'Rekayasa Perangkat Lunak', 'kode' => 'RPL'],
            ['institusi_id' => $smk->id, 'nama' => 'Teknik Komputer dan Jaringan', 'kode' => 'TKJ'],
            ['institusi_id' => $smk->id, 'nama' => 'Multimedia', 'kode' => 'MM'],
            ['institusi_id' => $smk->id, 'nama' => 'Akuntansi', 'kode' => 'AK'],
            ['institusi_id' => $sma->id, 'nama' => 'IPA', 'kode' => 'IPA'],
            ['institusi_id' => $sma->id, 'nama' => 'IPS', 'kode' => 'IPS'],
        ];
        foreach ($jurusan as $j) {
            Jurusan::create($j);
        }

        $fakIlkom = Fakultas::create(['institusi_id' => $pt->id, 'nama' => 'Fakultas Ilmu Komputer']);
        $fakTeknik = Fakultas::create(['institusi_id' => $pt->id, 'nama' => 'Fakultas Teknik']);
        $fakEkonomi = Fakultas::create(['institusi_id' => $pt->id, 'nama' => 'Fakultas Ekonomi dan Bisnis']);

        $prodi = [
            ['fakultas_id' => $fakIlkom->id, 'nama' => 'Teknik Informatika', 'kode' => 'TI', 'jenjang' => 's1'],
            ['fakultas_id' => $fakIlkom->id, 'nama' => 'Sistem Informasi', 'kode' => 'SI', 'jenjang' => 's1'],
            ['fakultas_id' => $fakTeknik->id, 'nama' => 'Teknik Komputer', 'kode' => 'TK', 'jenjang' => 'd3'],
            ['fakultas_id' => $fakTeknik->id, 'nama' => 'Teknik Elektro', 'kode' => 'TE', 'jenjang' => 's1'],
            ['fakultas_id' => $fakEkonomi->id, 'nama' => 'Manajemen', 'kode' => 'MJ', 'jenjang' => 's1'],
            ['fakultas_id' => $fakEkonomi->id, 'nama' => 'Akuntansi', 'kode' => 'AKT', 'jenjang' => 's1'],
        ];
        foreach ($prodi as $p) {
            ProgramStudi::create($p);
        }

        $skills = [
            ['nama' => 'PHP', 'kategori' => 'Pemrograman'],
            ['nama' => 'Laravel', 'kategori' => 'Framework'],
            ['nama' => 'JavaScript', 'kategori' => 'Pemrograman'],
            ['nama' => 'React', 'kategori' => 'Frontend'],
            ['nama' => 'Vue.js', 'kategori' => 'Frontend'],
            ['nama' => 'MySQL', 'kategori' => 'Database'],
            ['nama' => 'PostgreSQL', 'kategori' => 'Database'],
            ['nama' => 'UI/UX Design', 'kategori' => 'Desain'],
            ['nama' => 'Digital Marketing', 'kategori' => 'Pemasaran'],
            ['nama' => 'Analisis Data', 'kategori' => 'Data'],
            ['nama' => 'Python', 'kategori' => 'Pemrograman'],
            ['nama' => 'Microsoft Office', 'kategori' => 'Perkantoran'],
            ['nama' => 'Jaringan Komputer', 'kategori' => 'Infrastruktur'],
            ['nama' => 'Komunikasi', 'kategori' => 'Soft Skill'],
            ['nama' => 'Leadership', 'kategori' => 'Soft Skill'],
        ];
        foreach ($skills as $s) {
            Skill::create($s);
        }

        $minats = [
            ['nama' => 'Teknologi Informasi', 'deskripsi' => 'Minat pada pengembangan perangkat lunak dan sistem informasi.'],
            ['nama' => 'Desain & Kreatif', 'deskripsi' => 'Minat pada desain grafis, UI/UX, dan konten kreatif.'],
            ['nama' => 'Bisnis & Wirausaha', 'deskripsi' => 'Minat pada pengembangan usaha dan kewirausahaan.'],
            ['nama' => 'Pemasaran Digital', 'deskripsi' => 'Minat pada pemasaran berbasis digital dan media sosial.'],
            ['nama' => 'Pendidikan', 'deskripsi' => 'Minat pada dunia pendidikan dan pengajaran.'],
            ['nama' => 'Kesehatan', 'deskripsi' => 'Minat pada bidang kesehatan dan pelayanan masyarakat.'],
            ['nama' => 'Teknik & Rekayasa', 'deskripsi' => 'Minat pada teknik, mesin, dan infrastruktur.'],
            ['nama' => 'Keuangan & Akuntansi', 'deskripsi' => 'Minat pada keuangan, akuntansi, dan perbankan.'],
            ['nama' => 'Seni & Budaya', 'deskripsi' => 'Minat pada seni pertunjukan dan budaya lokal.'],
            ['nama' => 'Agribisnis', 'deskripsi' => 'Minat pada pertanian, peternakan, dan perikanan.'],
        ];
        foreach ($minats as $m) {
            KategoriMinat::create($m);
        }

        $sektorPertanian = SektorUnggulan::create(['nama' => 'Pertanian', 'kategori' => 'Agribisnis', 'kecamatan' => 'Bedus', 'ikon' => 'plant', 'deskripsi' => 'Sektor pertanian dan hortikultura.']);
        $sektorPerikanan = SektorUnggulan::create(['nama' => 'Perikanan & Kelautan', 'kategori' => 'Perikanan', 'kecamatan' => 'Pesisir', 'ikon' => 'fish', 'deskripsi' => 'Sektor perikanan tangkap dan budidaya.']);
        $sektorPariwisata = SektorUnggulan::create(['nama' => 'Pariwisata', 'kategori' => 'Jasa', 'kecamatan' => 'Bedus', 'ikon' => 'map', 'deskripsi' => 'Destinasi wisata alam dan budaya.']);
        $sektorKreatif = SektorUnggulan::create(['nama' => 'Industri Kreatif', 'kategori' => 'Kreatif', 'kecamatan' => 'Bedus', 'ikon' => 'palette', 'deskripsi' => 'Kerajinan, kuliner, dan ekonomi kreatif.']);
        $sektorDagang = SektorUnggulan::create(['nama' => 'Perdagangan & Jasa', 'kategori' => 'Perdagangan', 'kecamatan' => 'Bedus', 'ikon' => 'store', 'deskripsi' => 'Perdagangan dan jasa lokal.']);
        $sektorTi = SektorUnggulan::create(['nama' => 'Teknologi Informasi', 'kategori' => 'Digital', 'kecamatan' => 'Bedus', 'ikon' => 'code', 'deskripsi' => 'Startup dan layanan digital.']);

        $potensi = [
            ['sektor_id' => $sektorPertanian->id, 'nama' => 'Budidaya Padi Organik', 'lokasi_kecamatan' => 'Bedus', 'deskripsi' => 'Lahan pertanian padi organik.', 'peluang_usaha' => 'Produksi beras organik dan pemasaran.', 'kebutuhan_skill' => ['Pertanian Organik', 'Manajemen Usaha']],
            ['sektor_id' => $sektorPertanian->id, 'nama' => 'Hortikultura Sayuran', 'lokasi_kecamatan' => 'Bedus', 'deskripsi' => 'Budidaya sayuran dataran tinggi.', 'peluang_usaha' => 'Sayuran segar untuk pasar lokal.', 'kebutuhan_skill' => ['Budidaya Tanaman', 'Pemasaran']],
            ['sektor_id' => $sektorPerikanan->id, 'nama' => 'Budidaya Ikan Air Tawar', 'lokasi_kecamatan' => 'Pesisir', 'deskripsi' => 'Kolam budidaya ikan nila dan lele.', 'peluang_usaha' => 'Ikan konsumsi dan bibit.', 'kebutuhan_skill' => ['Budidaya Perikanan', 'Manajemen Usaha']],
            ['sektor_id' => $sektorPerikanan->id, 'nama' => 'Pengolahan Hasil Laut', 'lokasi_kecamatan' => 'Pesisir', 'deskripsi' => 'Pengolahan dan pengemasan hasil laut.', 'peluang_usaha' => 'Produk olahan ikan.', 'kebutuhan_skill' => ['Pengolahan Pangan', 'Kemasan Produk']],
            ['sektor_id' => $sektorPariwisata->id, 'nama' => 'Wisata Alam Air Terjun', 'lokasi_kecamatan' => 'Bedus', 'deskripsi' => 'Objek wisata air terjun.', 'peluang_usaha' => 'Jasa pemandu dan kuliner.', 'kebutuhan_skill' => ['Hospitality', 'Pemandu Wisata']],
            ['sektor_id' => $sektorPariwisata->id, 'nama' => 'Desa Wisata Budaya', 'lokasi_kecamatan' => 'Bedus', 'deskripsi' => 'Wisata budaya dan kesenian lokal.', 'peluang_usaha' => 'Homestay dan suvenir.', 'kebutuhan_skill' => ['Kriya', 'Manajemen Homestay']],
            ['sektor_id' => $sektorKreatif->id, 'nama' => 'Kerajinan Tangan', 'lokasi_kecamatan' => 'Bedus', 'deskripsi' => 'Kerajinan anyaman dan batik.', 'peluang_usaha' => 'Produk kerajinan ekspor.', 'kebutuhan_skill' => ['Kerajinan', 'Desain Produk']],
            ['sektor_id' => $sektorKreatif->id, 'nama' => 'Kuliner Lokal', 'lokasi_kecamatan' => 'Bedus', 'deskripsi' => 'Makanan khas daerah.', 'peluang_usaha' => 'Restoran dan katering.', 'kebutuhan_skill' => ['Memasak', 'Manajemen Restoran']],
            ['sektor_id' => $sektorDagang->id, 'nama' => 'Pasar Rakyat Digital', 'lokasi_kecamatan' => 'Bedus', 'deskripsi' => 'Digitalisasi pasar tradisional.', 'peluang_usaha' => 'E-commerce produk UMKM.', 'kebutuhan_skill' => ['Digital Marketing', 'E-commerce']],
            ['sektor_id' => $sektorTi->id, 'nama' => 'Startup Digital Lokal', 'lokasi_kecamatan' => 'Bedus', 'deskripsi' => 'Pengembangan startup dan aplikasi.', 'peluang_usaha' => 'Jasa pembuatan aplikasi.', 'kebutuhan_skill' => ['Laravel', 'React', 'UI/UX Design']],
        ];
        foreach ($potensi as $p) {
            PotensiDaerah::create($p);
        }

        $pengaturan = [
            ['key' => 'nama_aplikasi', 'value' => 'CDC BEDUSHUB'],
            ['key' => 'nama_instansi', 'value' => 'Dinas Ketenagakerjaan Kabupaten Bedus'],
            ['key' => 'email_kontak', 'value' => 'cdc@bedushub.test'],
            ['key' => 'telepon', 'value' => '(021) 1234-5678'],
            ['key' => 'alamat', 'value' => 'Jl. Raya Kampus No. 3, Bedus'],
            ['key' => 'footer_text', 'value' => '© 2026 CDC BEDUSHUB. All rights reserved.'],
        ];
        foreach ($pengaturan as $p) {
            Pengaturan::create($p);
        }
    }
}
