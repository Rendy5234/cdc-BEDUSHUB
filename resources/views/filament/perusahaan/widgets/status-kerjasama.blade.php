@php
    $statusColor = match ($color) {
        'success' => '#16a34a',
        'danger' => '#dc2626',
        default => '#6b7280',
    };
@endphp

<x-filament-widgets::widget class="fi-status-kerjasama-widget">
    <x-filament::section class="h-full">
        <div class="flex h-full flex-col justify-center gap-y-1">
            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">
                Status Kerja Sama
            </p>

            <h2 class="text-base font-semibold leading-6" style="color: {{ $statusColor }};">
                {{ $label }}
            </h2>
        </div>
    </x-filament::section>
</x-filament-widgets::widget>