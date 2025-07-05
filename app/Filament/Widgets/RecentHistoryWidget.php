<?php

namespace App\Filament\Widgets;

use App\Models\History;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class RecentHistoryWidget extends BaseWidget
{
    protected static ?string $heading = 'Riwayat Perubahan Terbaru';

    protected static ?int $sort = 2;

    protected int | string | array $columnSpan = 'full';

    protected static ?string $maxHeight = '300px';

    public function table(Table $table): Table
    {
        return $table
            ->query(
                History::query()
                    ->where('action', 'updated') // Filter hanya action 'updated'
                    ->with(['task', 'user', 'changedBy'])
                    ->latest()
                    ->limit(10)
            )
            ->columns([
                TextColumn::make('task.company_name')
                    ->label('Company')
                    ->limit(30)
                    ->tooltip(function (History $record): ?string {
                        return $record->task?->company_name;
                    })
                    ->searchable()
                    ->sortable(),
                TextColumn::make('task.position')
                    ->label('Task')
                    ->limit(30)
                    ->tooltip(function (History $record): ?string {
                        return $record->task?->position;
                    })
                    ->searchable()
                    ->sortable(),

                TextColumn::make('change_description')
                    ->label('Perubahan')
                    ->html()
                    ->limit(50)
                    ->tooltip(function (History $record): ?string {
                        return strip_tags($record->change_description);
                    })
                    ->formatStateUsing(function (History $record): string {
                        $changes = [];
                        $maxChanges = 2; // Batasi tampilan maksimal 2 perubahan
                        $count = 0;

                        foreach ($record->changed_fields as $field => $change) {
                            if ($count >= $maxChanges) {
                                $remaining = count($record->changed_fields) - $maxChanges;
                                $changes[] = "<small class='text-gray-500'>... dan {$remaining} perubahan lainnya</small>";
                                break;
                            }

                            $label = $change['field_label'] ?? ucfirst(str_replace('_', ' ', $field));
                            $oldValue = $change['old_value'] ?? 'kosong';
                            $newValue = $change['new_value'] ?? 'kosong';

                            // Format untuk rich editor content
                            if (str_contains($oldValue, '<') || str_contains($newValue, '<')) {
                                $oldValue = strip_tags($oldValue);
                                $newValue = strip_tags($newValue);

                                // Batasi panjang teks
                                $oldValue = strlen($oldValue) > 20 ? substr($oldValue, 0, 20) . '...' : $oldValue;
                                $newValue = strlen($newValue) > 20 ? substr($newValue, 0, 20) . '...' : $newValue;
                            }

                            $changes[] = "<strong>{$label}:</strong> {$oldValue} → {$newValue}";
                            $count++;
                        }

                        return implode('<br>', $changes);
                    }),

                TextColumn::make('action_label')
                    ->badge()
                    ->label('Aksi')
                    ->color(function (string $state): string {
                        return match ($state) {
                            'Dibuat' => 'success',
                            'Diperbarui' => 'warning',
                            'Dihapus' => 'danger',
                            default => 'gray',
                        };
                    })
                    ->icon(function (string $state): string {
                        return match ($state) {
                            'Dibuat' => 'heroicon-o-plus-circle',
                            'Diperbarui' => 'heroicon-o-pencil-square',
                            'Dihapus' => 'heroicon-o-trash',
                            default => 'heroicon-o-information-circle',
                        };
                    }),

                TextColumn::make('changedBy.name')
                    ->label('Diubah Oleh')
                    ->default('System')
                    ->badge()
                    ->color('info'),

                TextColumn::make('created_at')
                    ->label('Waktu')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->since()
                    ->tooltip(function (History $record): string {
                        return $record->created_at->format('d/m/Y H:i:s');
                    }),
            ])
            ->actions([
                Tables\Actions\ViewAction::make()
                    ->label('Detail')
                    ->icon('heroicon-o-eye')
                    ->modalHeading('Detail Perubahan')
                    ->modalContent(function (History $record): \Illuminate\Contracts\View\View {
                        $details = [];
                        foreach ($record->changed_fields as $field => $change) {
                            $label = $change['field_label'] ?? ucfirst(str_replace('_', ' ', $field));
                            $oldValue = $change['old_value'] ?? 'kosong';
                            $newValue = $change['new_value'] ?? 'kosong';

                            // Format HTML content
                            if (str_contains($oldValue, '<') || str_contains($newValue, '<')) {
                                $oldValue = $this->formatHtmlContent($oldValue);
                                $newValue = $this->formatHtmlContent($newValue);
                            }

                            $details[] = [
                                'label' => $label,
                                'old' => $oldValue,
                                'new' => $newValue
                            ];
                        }

                        return view('filament.widgets.history-detail', [
                            'details' => $details,
                            'record' => $record
                        ]);
                    })
                    ->modalWidth('4xl'),
            ])
            ->defaultSort('created_at', 'desc')
            ->paginated(false)
            ->striped()
            ->emptyStateHeading('Tidak ada riwayat perubahan')
            ->emptyStateDescription('Belum ada perubahan task yang tercatat.')
            ->emptyStateIcon('heroicon-o-document-text');
    }

    /**
     * Format HTML content untuk tampilan
     */
    private function formatHtmlContent(string $content): string
    {
        if (empty($content) || $content === 'kosong') {
            return 'kosong';
        }

        // Jika bukan HTML, return as is
        if (!str_contains($content, '<') && !str_contains($content, '>')) {
            return $content;
        }

        // Count images
        $imageCount = substr_count($content, '<img');

        // Convert HTML lists to readable format
        $content = preg_replace_callback('/<ol>(.*?)<\/ol>/s', function ($matches) {
            $listContent = $matches[1];
            $items = [];
            preg_match_all('/<li>(.*?)<\/li>/s', $listContent, $listMatches);
            foreach ($listMatches[1] as $index => $item) {
                $items[] = ($index + 1) . '. ' . strip_tags($item);
            }
            return implode("\n", $items);
        }, $content);

        $content = preg_replace_callback('/<ul>(.*?)<\/ul>/s', function ($matches) {
            $listContent = $matches[1];
            $items = [];
            preg_match_all('/<li>(.*?)<\/li>/s', $listContent, $listMatches);
            foreach ($listMatches[1] as $item) {
                $items[] = '• ' . strip_tags($item);
            }
            return implode("\n", $items);
        }, $content);

        // Remove images from text content
        $content = preg_replace('/<img[^>]*>/i', '', $content);

        // Clean up text
        $content = strip_tags($content);
        $content = preg_replace('/\s+/', ' ', trim($content));

        // Add image info if any
        if ($imageCount > 0) {
            $content .= "\n\n[Terdapat {$imageCount} gambar]";
        }

        return $content;
    }

    /**
     * Check if can view widget
     */
    public static function canView(): bool
    {
        return auth()->check();
    }

    /**
     * Get polling interval
     */
    protected static ?string $pollingInterval = '30s';
}
