<x-filament-widgets::widget class="fi-wi-rekomendasi">
    <x-filament::section
        heading="Rekomendasi untuk Anda"
        description="Pilihan lowongan dan pelatihan yang disesuaikan dengan profil Anda."
    >
        {{-- Section Lowongan --}}
        <div class="flex flex-col gap-3">
            <div class="flex items-center gap-x-2">
                <x-filament::icon
                    icon="heroicon-m-briefcase"
                    class="h-5 w-5 text-gray-400 dark:text-gray-500"
                />
                <h3 class="text-base font-semibold leading-6 text-gray-950 dark:text-white">
                    Lowongan
                </h3>
            </div>

            @if ($lowongan->isNotEmpty())
                <div style="padding-bottom: 0.5rem;" class="flex gap-4 overflow-x-auto">
                    @foreach ($lowongan as $rec)
                        @php $item = $rec['item']; @endphp
                        <div
                            wire:click="openLowonganDetail({{ $item->id }})"
                            style="width: 11rem;"
                            class="group flex shrink-0 cursor-pointer flex-col overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm transition hover:shadow-md dark:border-white/10 dark:bg-white/5"
                        >
                            <div style="position: relative; aspect-ratio: 3 / 4;" class="w-full overflow-hidden bg-gray-100 dark:bg-white/5">
                                <span style="position: absolute; top: 6px; left: 6px; z-index: 10; background: {{ $rec['source'] === 'ml' ? '#16a34a' : '#6b7280' }}; color: #ffffff; padding: 2px 8px; border-radius: 4px; font-size: 12px; font-weight: 600; line-height: 1.4;">
                                    {{ $rec['source'] === 'ml' ? 'ML' : 'Rule' }}
                                </span>
                                @if ($rec['imageUrl'])
                                    <img
                                        src="{{ $rec['imageUrl'] }}"
                                        alt="{{ $item->judul }}"
                                        style="object-fit: contain;"
                                        class="h-full w-full transition duration-200 group-hover:scale-105"
                                    >
                                @else
                                    <div class="flex h-full w-full items-center justify-center text-gray-400 dark:text-gray-500">
                                        <x-filament::icon icon="heroicon-m-briefcase" class="h-8 w-8" />
                                    </div>
                                @endif
                            </div>
                            <div class="p-3">
                                <p style="display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;" class="text-sm font-medium text-gray-900 dark:text-white">
                                    {{ $item->judul }}
                                </p>
                                <p style="display: -webkit-box; -webkit-line-clamp: 1; -webkit-box-orient: vertical; overflow: hidden;" class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                                    {{ $item->perusahaan?->nama }}
                                </p>

                                {{-- Gap warning on card --}}
                                @if ($rec['gap'] && $rec['gap']['has_gap'])
                                    <div style="margin-top: 0.3rem; padding: 0.4rem 0.6rem; border: 1px solid #fecaca; border-radius: 0.25rem; background: #fee2e2; font-size: 0.7rem; color: #991b1b;">
                                        <span style="font-weight: 600;">⚠ Butuh Skill:</span>
                                        @foreach ($rec['gap']['missing_skills'] as $i => $ms)
                                            @if ($i > 0), @endif
                                            {{ $ms['nama'] }}
                                        @endforeach
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <p class="text-sm text-gray-500 dark:text-gray-400">
                    Belum ada rekomendasi lowongan untuk Anda.
                </p>
            @endif
        </div>

        {{-- Section Pelatihan --}}
        <div class="mt-6 flex flex-col gap-3">
            <div class="flex items-center gap-x-2">
                <x-filament::icon
                    icon="heroicon-m-academic-cap"
                    class="h-5 w-5 text-gray-400 dark:text-gray-500"
                />
                <h3 class="text-base font-semibold leading-6 text-gray-950 dark:text-white">
                    Pelatihan
                </h3>
            </div>

            @if ($pelatihan->isNotEmpty())
                <div style="padding-bottom: 0.5rem;" class="flex gap-4 overflow-x-auto">
                    @foreach ($pelatihan as $rec)
                        @php $item = $rec['item']; @endphp
                        <div
                            wire:click="openPelatihanDetail({{ $item->id }})"
                            style="width: 11rem;"
                            class="group flex shrink-0 cursor-pointer flex-col overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm transition hover:shadow-md dark:border-white/10 dark:bg-white/5"
                        >
                            <div style="position: relative; aspect-ratio: 3 / 4;" class="w-full overflow-hidden bg-gray-100 dark:bg-white/5">
                                <span style="position: absolute; top: 6px; left: 6px; z-index: 10; background: {{ $rec['source'] === 'ml' ? '#16a34a' : '#6b7280' }}; color: #ffffff; padding: 2px 8px; border-radius: 4px; font-size: 12px; font-weight: 600; line-height: 1.4;">
                                    {{ $rec['source'] === 'ml' ? 'ML' : 'Rule' }}
                                </span>
                                @if ($rec['imageUrl'])
                                    <img
                                        src="{{ $rec['imageUrl'] }}"
                                        alt="{{ $item->judul }}"
                                        style="object-fit: contain;"
                                        class="h-full w-full transition duration-200 group-hover:scale-105"
                                    >
                                @else
                                    <div class="flex h-full w-full items-center justify-center text-gray-400 dark:text-gray-500">
                                        <x-filament::icon icon="heroicon-m-academic-cap" class="h-8 w-8" />
                                    </div>
                                @endif
                            </div>
                            <div class="p-3">
                                <p style="display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;" class="text-sm font-medium text-gray-900 dark:text-white">
                                    {{ $item->judul }}
                                </p>
                                <p style="display: -webkit-box; -webkit-line-clamp: 1; -webkit-box-orient: vertical; overflow: hidden;" class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                                    {{ $item->topik }}
                                </p>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <p class="text-sm text-gray-500 dark:text-gray-400">
                    Belum ada rekomendasi pelatihan untuk Anda.
                </p>
            @endif
        </div>
    </x-filament::section>

    <x-filament-actions::modals />
</x-filament-widgets::widget>
