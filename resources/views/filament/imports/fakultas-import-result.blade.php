@php
    $total = $import?->total_rows ?? 0;
    $successful = $import?->successful_rows ?? 0;
    $failed = $import?->getFailedRowsCount() ?? 0;
@endphp

<div class="space-y-6">
    @if ($import)
        <div class="grid grid-cols-3 gap-4">
            <div class="rounded-xl bg-gray-100 px-4 py-3 dark:bg-gray-800">
                <div class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Baris</div>
                <div class="mt-1 text-2xl font-semibold text-gray-900 dark:text-white">{{ number_format($total) }}</div>
            </div>
            <div class="rounded-xl bg-success-50 px-4 py-3 dark:bg-success-500/10">
                <div class="text-sm font-medium text-success-600 dark:text-success-400">Berhasil</div>
                <div class="mt-1 text-2xl font-semibold text-success-600 dark:text-success-400">{{ number_format($successful) }}</div>
            </div>
            <div class="rounded-xl bg-danger-50 px-4 py-3 dark:bg-danger-500/10">
                <div class="text-sm font-medium text-danger-600 dark:text-danger-400">Gagal</div>
                <div class="mt-1 text-2xl font-semibold text-danger-600 dark:text-danger-400">{{ number_format($failed) }}</div>
            </div>
        </div>

        <div class="text-sm text-gray-500 dark:text-gray-400">
            File: <span class="font-medium text-gray-900 dark:text-white">{{ $import->file_name }}</span>
            &middot; Selesai: {{ $import->completed_at ? \Illuminate\Support\Carbon::createFromTimestamp($import->completed_at)->format('d/m/Y H:i') : '-' }}
        </div>

        <div>
            <h3 class="mb-2 text-base font-semibold text-gray-900 dark:text-white">
                Data berhasil diimpor ({{ $successfulRows->count() }})
            </h3>
            @if ($successfulRows->isEmpty())
                <p class="text-sm text-gray-500 dark:text-gray-400">Tidak ada data yang berhasil diimpor.</p>
            @else
                <div class="max-h-64 overflow-y-auto rounded-lg border border-gray-200 dark:border-gray-700">
                    <table class="w-full text-left text-sm">
                        <thead class="sticky top-0 bg-gray-50 text-xs uppercase text-gray-500 dark:bg-gray-800 dark:text-gray-400">
                            <tr>
                                <th class="px-4 py-2">Nama Fakultas</th>
                                <th class="px-4 py-2">Institusi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                            @foreach ($successfulRows as $row)
                                <tr class="bg-white dark:bg-gray-900">
                                    <td class="px-4 py-2 text-gray-900 dark:text-white">{{ $row->data['nama'] ?? '-' }}</td>
                                    <td class="px-4 py-2 text-gray-900 dark:text-white">{{ $row->data['institusi'] ?? '-' }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>

        <div>
            <h3 class="mb-2 text-base font-semibold text-danger-600 dark:text-danger-400">
                Data gagal diimpor ({{ $failedRows->count() }})
            </h3>
            @if ($failedRows->isEmpty())
                <p class="text-sm text-gray-500 dark:text-gray-400">Tidak ada data yang gagal diimpor.</p>
            @else
                <div class="max-h-64 overflow-y-auto rounded-lg border border-gray-200 dark:border-gray-700">
                    <table class="w-full text-left text-sm">
                        <thead class="sticky top-0 bg-gray-50 text-xs uppercase text-gray-500 dark:bg-gray-800 dark:text-gray-400">
                            <tr>
                                <th class="px-4 py-2">Nama Fakultas</th>
                                <th class="px-4 py-2">Institusi</th>
                                <th class="px-4 py-2">Alasan Gagal</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                            @foreach ($failedRows as $row)
                                @php
                                    $data = is_array($row->data) ? $row->data : [];
                                    $failedNama = $data['nama'] ?? (count($data) ? implode(' | ', array_values($data)) : '-');
                                    $failedInstitusi = $data['institusi'] ?? '-';
                                @endphp
                                <tr class="bg-white dark:bg-gray-900">
                                    <td class="px-4 py-2 text-gray-900 dark:text-white">{{ $failedNama }}</td>
                                    <td class="px-4 py-2 text-gray-900 dark:text-white">{{ $failedInstitusi }}</td>
                                    <td class="px-4 py-2 text-danger-600 dark:text-danger-400">{{ $row->validation_error ?? '-' }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    @else
        <div class="rounded-xl border border-gray-200 bg-gray-50 px-4 py-6 text-center text-sm text-gray-500 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400">
            Belum ada riwayat import. Silakan lakukan import terlebih dahulu.
        </div>
    @endif
</div>
