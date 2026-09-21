@php
    use App\Enums\Jenjang;

    $gaji = null;

    if ($lowongan->gaji_min || $lowongan->gaji_max) {
        $fmt = fn ($n) => $n !== null ? 'Rp ' . number_format((float) $n, 0, ',', '.') : null;
        $min = $fmt($lowongan->gaji_min);
        $max = $fmt($lowongan->gaji_max);

        $gaji = $min && $max
            ? $min . ' - ' . $max
            : ($min ?? $max);
    }

    $pendidikan = collect((array) $lowongan->pendidikan)
        ->map(fn ($j) => Jenjang::tryFrom((string) $j)?->getLabel() ?? $j)
        ->filter()
        ->implode(', ') ?: null;
@endphp

@if ($lowongan)
    <div class="flex flex-col gap-4">
        <div style="aspect-ratio: 3 / 4; width: 12rem; max-width: 100%;" class="mx-auto overflow-hidden rounded-lg bg-gray-100 dark:bg-white/5">
            @if ($imageUrl)
                <img
                    src="{{ $imageUrl }}"
                    alt="{{ $lowongan->judul }}"
                    style="object-fit: contain;"
                    class="h-full w-full"
                >
            @else
                <div class="flex h-full w-full items-center justify-center text-gray-400 dark:text-gray-500">
                    <x-filament::icon icon="heroicon-m-briefcase" class="h-8 w-8" />
                </div>
            @endif
        </div>

        <div>
            <h3 class="text-lg font-semibold text-gray-950 dark:text-white">
                {{ $lowongan->judul }}
            </h3>
            <p class="text-sm font-medium text-primary-600 dark:text-primary-400">
                {{ $lowongan->perusahaan?->nama }}
            </p>
        </div>

        <dl style="grid-template-columns: repeat(2, minmax(0, 1fr));" class="grid gap-3 text-sm">
            <div>
                <dt class="text-xs text-gray-500 dark:text-gray-400">Tipe Pekerjaan</dt>
                <dd class="text-gray-900 dark:text-white">{{ $lowongan->tipe_pekerjaan ?: '—' }}</dd>
            </div>
            <div>
                <dt class="text-xs text-gray-500 dark:text-gray-400">Lokasi</dt>
                <dd class="text-gray-900 dark:text-white">{{ $lowongan->lokasi ?: '—' }}</dd>
            </div>
            <div>
                <dt class="text-xs text-gray-500 dark:text-gray-400">Gaji</dt>
                <dd class="text-gray-900 dark:text-white">{{ $gaji ?: '—' }}</dd>
            </div>
            <div>
                <dt class="text-xs text-gray-500 dark:text-gray-400">Batas Akhir</dt>
                <dd class="text-gray-900 dark:text-white">{{ $lowongan->tanggal_berakhir?->format('d/m/Y') ?: '—' }}</dd>
            </div>
            @if ($pendidikan)
                <div style="grid-column: span 2 / span 2;">
                    <dt class="text-xs text-gray-500 dark:text-gray-400">Pendidikan</dt>
                    <dd class="text-gray-900 dark:text-white">{{ $pendidikan }}</dd>
                </div>
            @endif
        </dl>

        @if ($lowongan->deskripsi)
            <div>
                <h4 class="text-sm font-semibold text-gray-900 dark:text-white">Deskripsi</h4>
                <p style="white-space: pre-line;" class="text-sm text-gray-600 dark:text-gray-300">
                    {{ $lowongan->deskripsi }}
                </p>
            </div>
        @endif

        @if ($lowongan->kualifikasi)
            <div>
                <h4 class="text-sm font-semibold text-gray-900 dark:text-white">Kualifikasi</h4>
                <p style="white-space: pre-line;" class="text-sm text-gray-600 dark:text-gray-300">
                    {{ $lowongan->kualifikasi }}
                </p>
            </div>
        @endif

        @if ($lowongan->benefit)
            <div>
                <h4 class="text-sm font-semibold text-gray-900 dark:text-white">Benefit</h4>
                <p style="white-space: pre-line;" class="text-sm text-gray-600 dark:text-gray-300">
                    {{ $lowongan->benefit }}
                </p>
            </div>
        @endif
    </div>
@endif
