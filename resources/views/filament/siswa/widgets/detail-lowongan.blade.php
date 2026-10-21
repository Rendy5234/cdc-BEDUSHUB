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

        {{-- Gap Analysis Warning --}}
        @if ($gap && $gap['has_gap'])
            <div style="margin-top: 1rem; padding: 0.85rem 1rem; border: 1px solid #fecaca; border-radius: 0.5rem; background: #fee2e2;">
                <p style="color: #991b1b; font-size: 0.9rem; font-weight: 600; margin-bottom: 0.4rem;">
                    ⚠ Butuh Skill:
                    @foreach ($gap['missing_skills'] as $i => $ms)
                        @if ($i > 0), @endif
                        {{ $ms['nama'] }}@if ($ms['level_min']) ({{ $ms['level_min'] }}) @endif
                    @endforeach
                </p>

                @if (! empty($gap['recommended_trainings']))
                    <p style="color: #7f1d1d; font-size: 0.85rem; margin-bottom: 0.5rem;">
                        Ikuti pelatihan ini dulu untuk menutup gap kemampuan:
                    </p>
                    {{-- Flex horizontal scrollable — kartu kontras di atas background peringatan pink (inline CSS) --}}
                    <div style="display: flex; gap: 12px; overflow-x: auto; padding-bottom: 12px; scroll-snap-type: x mandatory;">
                            @php
                                // Dukung array rekomendasi (str) maupun koleksi model Pelatihan (object)
                                $daftarPt = is_array($gap['recommended_trainings'] ?? [])
                                    ? $gap['recommended_trainings']
                                    : (is_countable($gap['recommended_trainings'] ?? null) ? $gap['recommended_trainings']->all() : []);
                            @endphp
                            @foreach ($daftarPt as $pt)
                                @php
                                    $judul = is_object($pt) ? ($pt->judul ?? '') : ($pt['judul'] ?? '');
                                    $topik = is_object($pt) ? ($pt->topik ?? '') : ($pt['topik'] ?? '');
                                    $id    = is_object($pt) ? ($pt->id ?? '') : ($pt['id'] ?? '');
                                @endphp
                                <div
                                    style="min-width: 200px; max-width: 200px; flex-shrink: 0; scroll-snap-align: start; background-color: #ffffff; border: 1px solid #d1d5db; border-radius: 8px; padding: 16px; display: flex; flex-direction: column; justify-content: space-between; flex-grow: 0;"
                                    class="dark:bg-gray-800 dark:border-gray-600"
                                >
                                    <div style="flex: 1;">
                                        <p style="font-weight: bold; color: #111827; margin-bottom: 4px; font-size: 0.9rem;">
                                            {{ $judul }}
                                        </p>
                                        @if (! empty($topik))
                                            <p style="font-size: 0.85rem; color: #6b7280; margin-bottom: 12px;">
                                                {{ $topik }}
                                            </p>
                                        @endif
                                    </div>
                                    <a href="{{ \App\Filament\Siswa\Resources\PendaftaranPelatihanResource::getUrl('create', ['pelatihan_id' => $id]) }}"
                                       style="color: #2563eb; font-weight: 600; text-decoration: none; font-size: 0.85rem;">
                                        Lihat Detail
                                    </a>
                                </div>
                            @endforeach
                    </div>
                @else
                    <p style="color: #7f1d1d; font-size: 0.85rem;">
                        Belum ada pelatihan yang cocok untuk menutup gap skill ini.
                    </p>
                @endif
            </div>
        @endif
    </div>
@endif
