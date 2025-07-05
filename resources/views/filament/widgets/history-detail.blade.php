{{-- resources/views/filament/widgets/history-detail.blade.php --}}
<div class="space-y-6">
    {{-- Header Info --}}
    <div class="bg-gray-50 dark:bg-gray-800 p-4 rounded-lg">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <h3 class="text-sm font-medium text-gray-500">Task</h3>
                <p class="text-base text-gray-900">{{ $record->task?->title ?? 'Task Tidak Ditemukan' }}</p>
            </div>
            <div>
                <h3 class="text-sm font-medium text-gray-500">Diubah Oleh</h3>
                <p class="text-base text-gray-900">{{ $record->changedBy?->name ?? 'System' }}</p>
            </div>
            <div>
                <h3 class="text-sm font-medium text-gray-500">Waktu</h3>
                <p class="text-base text-gray-900">{{ $record->created_at->format('d/m/Y H:i:s') }}</p>
            </div>
            <div>
                <h3 class="text-sm font-medium text-gray-500">Aksi</h3>
                <span
                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                    {{ $record->action_label }}
                </span>
            </div>
        </div>
    </div>

    {{-- Changes Details --}}
    <div class="space-y-4">
        <h3 class="text-lg font-medium text-gray-900">Detail Perubahan</h3>

        @forelse($details as $detail)
            <div class="rounded-lg p-4 bg-white dark:bg-gray-800">
                <div class="font-semibold text-gray-800 mb-3">{{ $detail['label'] }}</div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    {{-- Old Value --}}
                    <div>
                        <div class="text-sm font-medium text-red-600 mb-2">Sebelum:</div>
                        <div class="bg-red-50 p-3 rounded">
                            @if (str_contains($detail['old'], '<img') || str_contains($detail['old'], '[Terdapat'))
                                <div class="space-y-2">
                                    @if (str_contains($detail['old'], '[Terdapat'))
                                        @php
                                            $parts = explode('[Terdapat', $detail['old']);
                                            $text = trim($parts[0]);
                                            $imageInfo = '[Terdapat' . ($parts[1] ?? '');
                                        @endphp
                                        @if (!empty($text))
                                            <div class="text-gray-800 whitespace-pre-wrap">{{ $text }}</div>
                                        @endif
                                        <div class="text-sm text-blue-600 italic">{{ $imageInfo }}</div>
                                    @else
                                        <div class="text-gray-800 whitespace-pre-wrap">{{ $detail['old'] }}</div>
                                    @endif
                                </div>
                            @else
                                <div class="text-gray-800 whitespace-pre-wrap">{{ $detail['old'] }}</div>
                            @endif
                        </div>
                    </div>

                    {{-- New Value --}}
                    <div>
                        <div class="text-sm font-medium text-green-600 mb-2">Sesudah:</div>
                        <div class="bg-green-50 p-3 rounded">
                            @if (str_contains($detail['new'], '<img') || str_contains($detail['new'], '[Terdapat'))
                                <div class="space-y-2">
                                    @if (str_contains($detail['new'], '[Terdapat'))
                                        @php
                                            $parts = explode('[Terdapat', $detail['new']);
                                            $text = trim($parts[0]);
                                            $imageInfo = '[Terdapat' . ($parts[1] ?? '');
                                        @endphp
                                        @if (!empty($text))
                                            <div class="text-gray-800 whitespace-pre-wrap">{{ $text }}</div>
                                        @endif
                                        <div class="text-sm text-blue-600 italic">{{ $imageInfo }}</div>
                                    @else
                                        <div class="text-gray-800 whitespace-pre-wrap">{{ $detail['new'] }}</div>
                                    @endif
                                </div>
                            @else
                                <div class="text-gray-800 whitespace-pre-wrap">{{ $detail['new'] }}</div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="text-center py-8">
                <div class="text-gray-500">Tidak ada detail perubahan yang tersedia.</div>
            </div>
        @endforelse
    </div>
</div>
