<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Pilih Login - BEDUSHUB</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gradient-to-br from-slate-50 via-gray-50 to-slate-100 text-gray-900 antialiased font-sans min-h-screen flex items-center justify-center">

    <!-- ===== BACK TO HOME ===== -->
    <div class="absolute top-6 left-6 z-20">
        <a href="{{ url('/') }}" class="inline-flex items-center gap-2 text-sm font-medium text-gray-600 hover:text-blue-700 transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M15.75 19l-7.5-7.5 7.5-7.5"></path>
            </svg>
            Kembali ke Beranda
        </a>
    </div>

    <!-- ===== CARD CONTAINER ===== -->
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-12">

        <!-- Header -->
        <div class="text-center mb-12">
            <h1 class="text-4xl sm:text-5xl font-extrabold text-gray-900 mb-4">
                Pilih Login
            </h1>
            <p class="text-lg text-gray-600 max-w-2xl mx-auto">
                Silakan pilih jenis akun yang sesuai untuk masuk ke sistem BEDUSHUB.
            </p>
        </div>

    <!-- ===== CARDS GRID ===== -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-8 max-w-3xl mx-auto">

        <!-- Siswa/Alumni -->
        <a href="{{ url('/dashboard/login') }}"
           class="group block bg-white rounded-2xl p-8 shadow-sm hover:shadow-xl hover:-translate-y-1 focus:outline-none focus:ring-2 focus:ring-blue-500/50 transition-all duration-300 border border-gray-100 text-center">

            <div class="flex-shrink-0 flex items-center justify-center w-16 h-16 rounded-xl bg-blue-50 text-blue-600 mb-6 group-hover:scale-110 transition-transform duration-300">
                <!-- Heroicon: academic-cap -->
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M12 14l9-5-9-5-9 5 9 5z"></path>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M12 14v10"></path>
                </svg>
            </div>

            <h2 class="text-2xl font-bold text-gray-900 mb-3 group-hover:text-blue-700 transition-colors">
                Siswa/Alumni
            </h2>
            <p class="text-gray-600 text-sm leading-relaxed">
                Cari pelatihan, lowongan, atau lanjutkan pencarian karier.
            </p>
        </a>

        <!-- Perusahaan -->
        <a href="{{ url('/perusahaan/login') }}"
           class="group block bg-white rounded-2xl p-8 shadow-sm hover:shadow-xl hover:-translate-y-1 focus:outline-none focus:ring-2 focus:ring-green-500/50 transition-all duration-300 border border-gray-100 text-center">

            <div class="flex-shrink-0 flex items-center justify-center w-16 h-16 rounded-xl bg-green-50 text-green-600 mb-6 group-hover:scale-110 transition-transform duration-300">
                <!-- Heroicon: briefcase -->
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <rect x="2" y="7" width="20" height="14" rx="2" ry="2"
                          stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></rect>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M16 3h-8v4h8z"></path>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M12 17h.01M12 13h.01"></path>
                </svg>
            </div>

            <h2 class="text-2xl font-bold text-gray-900 mb-3 group-hover:text-green-700 transition-colors">
                Perusahaan
            </h2>
            <p class="text-gray-600 text-sm leading-relaxed">
                Temukan tenaga kerja, posting lowongan, dan kelola rekrutmen.
            </p>
        </a>
    </div>

        <!-- Footer -->
        <div class="mt-16 text-center">
            <p class="text-sm text-gray-500">
                &copy; {{ date('Y') }} BEDUSHUB. Hak Cipta Dilindungi.
            </p>
        </div>

    </div>

</body>
</html>
