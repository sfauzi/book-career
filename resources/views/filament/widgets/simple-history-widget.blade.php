<x-filament-widgets::widget>
    <x-filament::section>
        {{-- Widget content --}}

        {{-- resources/views/filament/widgets/simple-history.blade.php --}}
        <div class="fi-widget-simple-history">
            <div class="fi-widget-simple-history-content">
                @if ($histories->count() > 0)
                    <div class="space-y-3">
                        @foreach ($histories as $history)
                            <div
                                class="flex items-start space-x-3 p-3 bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-none hover:bg-gray-50 transition-colors">
                                {{-- Icon --}}
                                <div class="flex-shrink-0">
                                    <div class="flex items-center justify-center w-8 h-8 bg-yellow-100 rounded-full">
                                        <svg class="w-4 h-4 text-yellow-600" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                            </path>
                                        </svg>
                                    </div>
                                </div>

                                {{-- Content --}}
                                <div class="flex-1 min-w-0">
                                    <div class="flex items-center justify-between">
                                        <p class="text-sm font-medium text-gray-900 truncate">
                                            {{ $history->task?->position ?? 'Task Tidak Ditemukan' }}
                                        </p>
                                        <span class="text-xs text-gray-500">
                                            {{ $history->created_at->diffForHumans() }}
                                        </span>
                                    </div>

                                    <div class="mt-1">
                                        <p class="text-xs text-gray-600">
                                            Diubah oleh <span
                                                class="font-medium">{{ $history->changedBy?->name ?? 'System' }}</span>
                                        </p>
                                    </div>

                                    {{-- Changes Summary --}}
                                    <div class="mt-2">
                                        @php
                                            $changeCount = count($history->changed_fields);
                                            $firstChange = collect($history->changed_fields)->first();
                                            $fieldLabel = $firstChange['field_label'] ?? 'Field';
                                        @endphp

                                        <div class="flex items-center space-x-2">
                                            <span
                                                class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                {{ $changeCount }} {{ $changeCount == 1 ? 'perubahan' : 'perubahan' }}
                                            </span>

                                            @if ($changeCount == 1)
                                                <span class="text-xs text-gray-500">
                                                    pada {{ $fieldLabel }}
                                                </span>
                                            @else
                                                <span class="text-xs text-gray-500">
                                                    termasuk {{ $fieldLabel }}
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    {{-- View All Link --}}
                    <div class="mt-4 text-center">
                        <a href="{{ route('filament.apps.resources.histories.index') }}"
                            class="text-sm text-blue-600 hover:text-blue-800 font-medium">
                            Lihat Semua Riwayat →
                        </a>
                    </div>
                @else
                    <div class="text-center py-8">
                        <div class="mx-auto w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mb-4">
                            <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                </path>
                            </svg>
                        </div>
                        <h3 class="text-sm font-medium text-gray-900 mb-2">Tidak ada riwayat perubahan</h3>
                        <p class="text-sm text-gray-500">Belum ada perubahan task yang tercatat.</p>
                    </div>
                @endif
            </div>
        </div>
    </x-filament::section>
</x-filament-widgets::widget>
