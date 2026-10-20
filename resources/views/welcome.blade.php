<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>BEDUSHUB - Bojonegoro EduSmart Hub</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-white text-gray-900 antialiased font-sans">

    <!-- ===== NAVBAR ===== -->
    <header class="bg-white/70 backdrop-blur-md shadow-sm sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center h-16">
                <div class="flex-shrink-0 flex items-center">
                    <span class="text-2xl font-bold text-blue-700">BEDUSHUB</span>
                </div>
            </div>
        </div>
    </header>

    <!-- ===== HERO SECTION ===== -->
    @php
        // Foto hero di storage/app/public/logo — diakses publik lewat symlink storage:link
        $heroBg = asset('storage/logo/Gemini_Generated_Image_ybxjfqybxjfqybxj.jpg');
    @endphp
    <section class="relative py-20 sm:py-24 lg:py-32 bg-cover bg-center bg-fixed"
             style="background-image: url('{{ $heroBg }}');">

        <!--
             Overlay hitam pekat (60%) agar teks putih jelas kontras
             dan hero terasa elegan — bukan menumpuk langsung di atas gambar.
        -->
        <div class="absolute inset-0 bg-black/60 pointer-events-none"></div>

        <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <!-- Judul hero: besar, tebal, drop shadow agar nyolong di atas overlay -->
            <h1 class="text-5xl sm:text-6xl md:text-7xl font-extrabold text-white mb-6 drop-shadow-lg">
                BEDUSHUB: Bojonegoro EduSmart Hub
            </h1>

            <!-- Subjudul: abu-terang, legan, cukup jauh dari judul -->
            <p class="text-xl text-gray-200 mb-12 max-w-3xl mx-auto drop-shadow-md mt-6">
                Satu pintu untuk Pendidikan, Pelatihan, dan Karier Terpadu Kabupaten Bojonegoro.
            </p>

            <!-- Tombol CTA: gradien mencolok + animasi scale hover -->
            <div class="flex flex-col sm:flex-row gap-4 justify-center items-center">
                <a href="{{ url('/login') }}"
                   class="w-full sm:w-auto inline-flex items-center justify-center gap-2 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white font-bold py-4 px-10 rounded-lg shadow-lg hover:shadow-xl hover:scale-105 transition-all duration-300 text-lg">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M11 16l7-4-7-4v8zM4 4v16h16a2 2 0 002-2V6a2 2 0 00-2-2H4z"></path>
                    </svg>
                    Masuk ke Sistem
                </a>
            </div>
        </div>
    </section>
    <!-- ===== SECTION: GARIS BESAR SISTEM ===== -->
    <section class="py-24 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h2 class="text-3xl font-bold text-center text-gray-900 mb-16">
                Garis Besar Sistem
            </h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <!-- Kartu 1: Pendidikan & Pelatihan -->
                <div class="group bg-white rounded-2xl p-10 shadow-md hover:shadow-xl hover:-translate-y-1 transition-all duration-300 text-center border border-gray-100">
                    <div class="flex-shrink-0 flex items-center justify-center w-16 h-16 rounded-xl bg-blue-50 text-blue-700 mb-8 group-hover:scale-110 transition-transform duration-300">
                        <!-- Heroicon: academic-cap -->
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M12 14l9-5-9-5-9 5 9 5z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M12 14v10"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-4 group-hover:text-blue-700 transition-colors">
                        Pendidikan & Pelatihan
                    </h3>
                    <p class="text-gray-600 text-sm leading-relaxed">
                        Rekomendasi pelatihan untuk menutup kesenjangan skill Anda.
                    </p>
                </div>
                <!-- Kartu 2: Karier & Lowongan -->
                <div class="group bg-white rounded-2xl p-10 shadow-md hover:shadow-xl hover:-translate-y-1 transition-all duration-300 text-center border border-gray-100">
                    <div class="flex-shrink-0 flex items-center justify-center w-16 h-16 rounded-xl bg-green-50 text-green-700 mb-8 group-hover:scale-110 transition-transform duration-300">
                        <!-- Heroicon: briefcase -->
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M21 13.659A9 9 0 1110.94 4a9 9 0 0110.06 9.659z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M12 14V4m0 0l4 4m-4-4l-4 4"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-4 group-hover:text-green-700 transition-colors">
                        Karier & Lowongan
                    </h3>
                    <p class="text-gray-600 text-sm leading-relaxed">
                        Rekomendasi lowongan kerja personal berbasis AI.
                    </p>
                </div>
                <!-- Kartu 3: Potensi Daerah -->
                <div class="group bg-white rounded-2xl p-10 shadow-md hover:shadow-xl hover:-translate-y-1 transition-all duration-300 text-center border border-gray-100">
                    <div class="flex-shrink-0 flex items-center justify-center w-16 h-16 rounded-xl bg-orange-50 text-orange-700 mb-8 group-hover:scale-110 transition-transform duration-300">
                        <!-- Heroicon: map-pin -->
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M12 15V3m0 0l6 6m-6-6L6 9"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-4 group-hover:text-orange-700 transition-colors">
                        Potensi Daerah
                    </h3>
                    <p class="text-gray-600 text-sm leading-relaxed">
                        Pemetaan sektor unggulan dan peluang usaha di Bojonegoro.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- ===== FOOTER ===== -->
    <footer class="bg-gray-900 text-gray-400 py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center text-sm">
            <p>&copy; {{ date('Y') }} BEDUSHUB. Hak Cipta Dilindungi.</p>
        </div>
    </footer>

</body>
</html>