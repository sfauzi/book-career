{{-- resources/views/filament/resources/task-resource/pages/task-history.blade.php --}}

<x-filament-panels::page>
    <div class="space-y-6">
        {{-- Task Information Card --}}
        <x-filament::section>
            <x-slot name="heading">
                Informasi Task
            </x-slot>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <x-filament::section.description class="font-semibold">
                        Perusahaan
                    </x-filament::section.description>
                    <p class="text-sm">{{ $record->company_name }}</p>
                </div>
                
                <div>
                    <x-filament::section.description class="font-semibold">
                        Posisi
                    </x-filament::section.description>
                    <p class="text-sm">{{ $record->position }}</p>
                </div>
                
                <div>
                    <x-filament::section.description class="font-semibold">
                        Status Saat Ini
                    </x-filament::section.description>
                    <x-filament::badge 
                        :color="match($record->status) {
                            'Applied' => 'primary',
                            'Interview' => 'warning', 
                            'Test' => 'info',
                            'Diterima' => 'success',
                            'Ditolak' => 'danger',
                            default => 'gray'
                        }"
                    >
                        {{ $record->status }}
                    </x-filament::badge>
                </div>
                
                <div>
                    <x-filament::section.description class="font-semibold">
                        User
                    </x-filament::section.description>
                    <p class="text-sm">{{ $record->user->name }}</p>
                </div>
                
                <div>
                    <x-filament::section.description class="font-semibold">
                        Platform
                    </x-filament::section.description>
                    <p class="text-sm">{{ $record->platform ?: 'Tidak disebutkan' }}</p>
                </div>
                
                <div>
                    <x-filament::section.description class="font-semibold">
                        Tanggal Melamar
                    </x-filament::section.description>
                    <p class="text-sm">{{ $record->applied_date->format('d/m/Y') }}</p>
                </div>
            </div>
            
            @if($record->notes)
                <div class="mt-4">
                    <x-filament::section.description class="font-semibold">
                        Catatan
                    </x-filament::section.description>
                    <div class="text-sm mt-1 p-3 bg-gray-50 dark:bg-gray-800 rounded-lg">
                        {{ $record->notes }}
                    </div>
                </div>
            @endif
        </x-filament::section>

        {{-- Statistics Cards --}}
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <x-filament::section class="text-center">
                <div class="text-2xl font-bold text-primary-600">
                    {{ $record->histories()->count() }}
                </div>
                <div class="text-sm text-gray-600 dark:text-gray-400">
                    Total Perubahan
                </div>
            </x-filament::section>
            
            <x-filament::section class="text-center">
                <div class="text-2xl font-bold text-warning-600">
                    {{ $record->histories()->where('action', 'updated')->count() }}
                </div>
                <div class="text-sm text-gray-600 dark:text-gray-400">
                    Pembaruan
                </div>
            </x-filament::section>
            
            <x-filament::section class="text-center">
                <div class="text-2xl font-bold text-info-600">
                    {{ $record->histories()->whereDate('created_at', today())->count() }}
                </div>
                <div class="text-sm text-gray-600 dark:text-gray-400">
                    Hari Ini
                </div>
            </x-filament::section>
            
            <x-filament::section class="text-center">
                <div class="text-2xl font-bold text-success-600">
                    {{ $record->histories()->latest()->first()?->created_at?->diffForHumans() ?: 'Tidak ada' }}
                </div>
                <div class="text-sm text-gray-600 dark:text-gray-400">
                    Terakhir Diubah
                </div>
            </x-filament::section>
        </div>

        {{-- History Timeline (Alternative view) --}}
        <x-filament::section>
            <x-slot name="heading">
                Timeline Perubahan
            </x-slot>
            
            <div class="space-y-4">
                @forelse($record->histories()->latest()->limit(5)->get() as $history)
                    <div class="flex items-start space-x-4 p-4 border border-gray-200 dark:border-gray-700 rounded-lg">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 rounded-full flex items-center justify-center
                                {{ match($history->action) {
                                    'created' => 'bg-success-100 text-success-600',
                                    'updated' => 'bg-warning-100 text-warning-600', 
                                    'deleted' => 'bg-danger-100 text-danger-600',
                                    default => 'bg-gray-100 text-gray-600'
                                } }}
                            ">
                                @switch($history->action)
                                    @case('created')
                                        <x-heroicon-o-plus class="w-4 h-4" />
                                        @break
                                    @case('updated')
                                        <x-heroicon-o-pencil class="w-4 h-4" />
                                        @break
                                    @case('deleted')
                                        <x-heroicon-o-trash class="w-4 h-4" />
                                        @break
                                    @default
                                        <x-heroicon-o-clock class="w-4 h-4" />
                                @endswitch
                            </div>
                        </div>
                        
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center justify-between">
                                <h4 class="text-sm font-medium text-gray-900 dark:text-gray-100">
                                    {{ match($history->action) {
                                        'created' => 'Task Dibuat',
                                        'updated' => 'Task Diperbarui',
                                        'deleted' => 'Task Dihapus',
                                        default => ucfirst($history->action)
                                    } }}
                                </h4>
                                <span class="text-xs text-gray-500">
                                    {{ $history->created_at->format('d/m/Y H:i:s') }}
                                </span>
                            </div>
                            
                            <div class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                                @if($history->changedBy)
                                    Oleh: {{ $history->changedBy->name }}
                                @else
                                    Oleh: System
                                @endif
                            </div>
                            
                            @if($history->changed_fields)
                                <div class="mt-2 space-y-1">
                                    @foreach($history->changed_fields as $field => $change)
                                        <div class="text-xs bg-gray-50 dark:bg-gray-800 p-2 rounded">
                                            <span class="font-medium">{{ $change['field_label'] ?? ucfirst(str_replace('_', ' ', $field)) }}:</span>
                                            <span class="text-gray-500">{{ $change['old_value'] ?: 'kosong' }}</span>
                                            <span class="mx-1">→</span>
                                            <span class="text-gray-900 dark:text-gray-100">{{ $change['new_value'] ?: 'kosong' }}</span>
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    </div>
                @empty
                    <div class="text-center py-8 text-gray-500">
                        <x-heroicon-o-clock class="w-12 h-12 mx-auto mb-2" />
                        <p>Belum ada riwayat perubahan</p>
                    </div>
                @endforelse
                
                @if($record->histories()->count() > 5)
                    <div class="text-center">
                        <p class="text-sm text-gray-500">
                            Menampilkan 5 perubahan terbaru dari {{ $record->histories()->count() }} total perubahan
                        </p>
                    </div>
                @endif
            </div>
        </x-filament::section>

        {{-- Full History Table --}}
        <x-filament::section>
            <x-slot name="heading">
                Semua Riwayat Perubahan
            </x-slot>
            
            {{ $this->table }}
        </x-filament::section>
    </div>
</x-filament-panels::page>