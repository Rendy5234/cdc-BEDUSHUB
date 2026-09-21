@php
    use App\Enums\JenisPelatihan;
    use App\Enums\SkillLevel;

    $jenis = JenisPelatihan::tryFrom((string) $pelatihan->jenis)?->getLabel();
    $level = SkillLevel::tryFrom((string) $pelatihan->level)?->getLabel();

    $jadwal = collect([
        $pelatihan->tanggal_mulai?->format('d/m/Y'),
        $pelatihan->tanggal_selesai?->format('d/m/Y'),
    ])->filter()->implode(' - ');

    $jam = collect([
        $pelatihan->jam_mulai,
        $pelatihan->jam_selesai,
    ])->filter()->implode(' - ');

    $sisa = $pelatihan->sisaKuota();
    $kuotaText = $sisa === null ? 'Tanpa batas' : (string) $sisa;
@endphp

@if ($pelatihan)
    <div class="flex flex-col gap-4">
        <div style="aspect-ratio: 3 / 4; width: 12rem; max-width: 100%;" class="mx-auto overflow-hidden rounded-lg bg-gray-100 dark:bg-white/5">
            @if ($imageUrl)
                <img
                    src="{{ $imageUrl }}"
                    alt="{{ $pelatihan->judul }}"
                    style="object-fit: contain;"
                    class="h-full w-full"
                >
            @else
                <div class="flex h-full w-full items-center justify-center text-gray-400 dark:text-gray-500">
                    <x-filament::icon icon="heroicon-m-academic-cap" class="h-8 w-8" />
                </div>
            @endif
        </div>

        <div>
            <h3 class="text-lg font-semibold text-gray-950 dark:text-white">
                {{ $pelatihan->judul }}
            </h3>
            <p class="text-sm font-medium text-primary-600 dark:text-primary-400">
                {{ $pelatihan->topik }}
            </p>
        </div>

        <dl style="grid-template-columns: repeat(2, minmax(0, 1fr));" class="grid gap-3 text-sm">
            <div>
                <dt class="text-xs text-gray-500 dark:text-gray-400">Jenis</dt>
                <dd class="text-gray-900 dark:text-white">{{ $jenis ?: '—' }}</dd>
            </div>
            <div>
                <dt class="text-xs text-gray-500 dark:text-gray-400">Level</dt>
                <dd class="text-gray-900 dark:text-white">{{ $level ?: '—' }}</dd>
            </div>
            <div>
                <dt class="text-xs text-gray-500 dark:text-gray-400">Instruktur</dt>
                <dd class="text-gray-900 dark:text-white">{{ $pelatihan->instruktur ?: '—' }}</dd>
            </div>
            <div>
                <dt class="text-xs text-gray-500 dark:text-gray-400">Tempat</dt>
                <dd class="text-gray-900 dark:text-white">{{ $pelatihan->tempat ?: '—' }}</dd>
            </div>
            @if ($jadwal)
                <div>
                    <dt class="text-xs text-gray-500 dark:text-gray-400">Jadwal</dt>
                    <dd class="text-gray-900 dark:text-white">{{ $jadwal }}</dd>
                </div>
            @endif
            @if ($jam)
                <div>
                    <dt class="text-xs text-gray-500 dark:text-gray-400">Jam</dt>
                    <dd class="text-gray-900 dark:text-white">{{ $jam }}</dd>
                </div>
            @endif
            <div>
                <dt class="text-xs text-gray-500 dark:text-gray-400">Sisa Kuota</dt>
                <dd class="text-gray-900 dark:text-white">{{ $kuotaText }}</dd>
            </div>
        </dl>

        @if ($pelatihan->deskripsi)
            <div>
                <h4 class="text-sm font-semibold text-gray-900 dark:text-white">Deskripsi</h4>
                <p style="white-space: pre-line;" class="text-sm text-gray-600 dark:text-gray-300">
                    {{ $pelatihan->deskripsi }}
                </p>
            </div>
        @endif
    </div>
@endif
