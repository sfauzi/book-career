<?php

namespace App\Filament\Resources\TaskResource\RelationManagers;

use App\Models\History;
use Carbon\Carbon;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class HistoriesRelationManager extends RelationManager
{
    protected static string $relationship = 'histories';

    protected static ?string $title = 'Riwayat Perubahan';

    protected static ?string $modelLabel = 'Riwayat';

    protected static ?string $pluralModelLabel = 'Riwayat Perubahan';


    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Placeholder::make('note')
                    ->label('Catatan')
                    ->content('Riwayat perubahan tidak dapat diubah secara manual.'),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('id')
            ->columns([
                Tables\Columns\TextColumn::make('action')
                    ->label('Aksi')
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'created' => 'success',
                        'updated' => 'warning',
                        'deleted' => 'danger',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn(string $state): string => match ($state) {
                        'created' => 'Dibuat',
                        'updated' => 'Diperbarui',
                        'deleted' => 'Dihapus',
                        default => ucfirst($state),
                    }),

                Tables\Columns\TextColumn::make('change_description')
                    ->label('Perubahan')
                    ->html()
                    ->wrap()
                    ->formatStateUsing(function (History $record): string {
                        $changes = [];

                        foreach ($record->changed_fields as $field => $change) {
                            $label = $change['field_label'] ?? ucfirst(str_replace('_', ' ', $field));
                            $oldValue = $change['old_value'] ?? '<em>kosong</em>';
                            $newValue = $change['new_value'] ?? '<em>kosong</em>';

                            // Format nilai berdasarkan field type
                            if ($field === 'applied_date') {
                                $oldValue = $oldValue ? date('d/m/Y', strtotime($oldValue)) : '<em>kosong</em>';
                                $newValue = $newValue ? date('d/m/Y', strtotime($newValue)) : '<em>kosong</em>';
                            }

                            // Handle rich editor content dengan gambar
                            if ($field === 'notes' || $this->isRichEditorField($field)) {
                                $oldValue = $this->formatRichEditorContent($oldValue);
                                $newValue = $this->formatRichEditorContent($newValue);
                            }

                            $changes[] = "<strong>{$label}:</strong><br>
                                         <div style='margin-left: 10px;'>
                                             <strong>Dari:</strong> {$oldValue}<br>
                                             <strong>Ke:</strong> {$newValue}
                                         </div>";
                        }

                        return implode('<br><br>', $changes);
                    }),

                Tables\Columns\TextColumn::make('changedBy.name')
                    ->label('Diubah Oleh')
                    ->placeholder('System')
                    ->sortable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Waktu Perubahan')
                    ->dateTime('d/m/Y H:i:s')
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('action')
                    ->options([
                        'created' => 'Dibuat',
                        'updated' => 'Diperbarui',
                        'deleted' => 'Dihapus',
                    ])
                    ->label('Aksi'),

                Tables\Filters\Filter::make('created_at')
                    ->form([
                        Forms\Components\DatePicker::make('from')
                            ->label('Dari Tanggal'),
                        Forms\Components\DatePicker::make('until')
                            ->label('Sampai Tanggal'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['from'],
                                fn(Builder $query, $date): Builder => $query->whereDate('created_at', '>=', $date),
                            )
                            ->when(
                                $data['until'],
                                fn(Builder $query, $date): Builder => $query->whereDate('created_at', '<=', $date),
                            );
                    })
                    ->label('Tanggal Perubahan'),
            ])
            ->headerActions([
                // Tidak ada action untuk create karena history auto-generated
            ])
            ->actions([
                Tables\Actions\ViewAction::make()
                    ->modalHeading('Detail Riwayat Perubahan')
                    ->form([
                        Forms\Components\TextInput::make('action')
                            ->label('Aksi')
                            ->disabled()
                            ->formatStateUsing(fn(string $state): string => match ($state) {
                                'created' => 'Dibuat',
                                'updated' => 'Diperbarui',
                                'deleted' => 'Dihapus',
                                default => ucfirst($state),
                            }),

                        Forms\Components\Textarea::make('changes_detail')
                            ->label('Detail Perubahan')
                            ->disabled()
                            ->rows(6)
                            ->formatStateUsing(function (History $record): string {
                                $details = [];
                                foreach ($record->changed_fields as $field => $change) {
                                    $label = $change['field_label'] ?? ucfirst(str_replace('_', ' ', $field));

                                    // Format old value
                                    $oldValue = $change['old_value'] ?? 'kosong';
                                    if ($oldValue !== 'kosong' && (str_contains($oldValue, '<') || str_contains($oldValue, '>'))) {
                                        $oldValue = strip_tags($oldValue);
                                        $oldValue = preg_replace('/\s+/', ' ', trim($oldValue));
                                    }

                                    // Format new value
                                    $newValue = $change['new_value'] ?? 'kosong';
                                    if ($newValue !== 'kosong' && (str_contains($newValue, '<') || str_contains($newValue, '>'))) {
                                        // Convert HTML lists to readable format
                                        $newValue = preg_replace_callback('/<ol>(.*?)<\/ol>/s', function ($matches) {
                                            $content = $matches[1];
                                            $items = [];
                                            preg_match_all('/<li>(.*?)<\/li>/s', $content, $listMatches);
                                            foreach ($listMatches[1] as $index => $item) {
                                                $items[] = ($index + 1) . '. ' . strip_tags($item);
                                            }
                                            return implode('; ', $items);
                                        }, $newValue);

                                        $newValue = preg_replace_callback('/<ul>(.*?)<\/ul>/s', function ($matches) {
                                            $content = $matches[1];
                                            $items = [];
                                            preg_match_all('/<li>(.*?)<\/li>/s', $content, $listMatches);
                                            foreach ($listMatches[1] as $item) {
                                                $items[] = '• ' . strip_tags($item);
                                            }
                                            return implode('; ', $items);
                                        }, $newValue);

                                        $newValue = strip_tags($newValue);
                                        $newValue = preg_replace('/\s+/', ' ', trim($newValue));
                                    }

                                    $details[] = "{$label}:\n  Dari: {$oldValue}\n  Ke: {$newValue}";
                                }
                                return implode("\n\n", $details);
                            }),

                        // Forms\Components\TextInput::make('changedBy.name')
                        //     ->label('Diubah Oleh')
                        //     ->disabled()
                        //     ->default('System'),

                        Forms\Components\TextInput::make('updated_at')
                            ->label('Waktu Perubahan')
                            ->disabled()
                            ->formatStateUsing(fn($state) => $state ? Carbon::parse($state)->format('d/m/Y H:i:s') : null),
                    ])
                    ->slideOver(),
            ])
            ->bulkActions([
                // Tidak ada bulk actions untuk history
            ])
            ->defaultSort('created_at', 'desc')
            ->poll('30s') // Auto refresh setiap 30 detik
            ->emptyStateHeading('Belum Ada Riwayat Perubahan')
            ->emptyStateDescription('Riwayat perubahan akan muncul ketika task ini dimodifikasi.')
            ->emptyStateIcon('heroicon-o-clock');
    }

    // Method helper untuk check rich editor field
    private function isRichEditorField($field): bool
    {
        // Daftar field yang menggunakan rich editor
        $richEditorFields = ['notes', 'description', 'content', 'body']; // sesuaikan dengan field Anda
        return in_array($field, $richEditorFields);
    }

    // Method helper untuk format rich editor content
    private function formatRichEditorContent($content): string
    {
        if (empty($content) || $content === '<em>kosong</em>') {
            return '<em>kosong</em>';
        }

        // Jika bukan HTML, return as is
        if (!str_contains($content, '<') && !str_contains($content, '>')) {
            return $content;
        }

        // Process images - pastikan path absolut
        $content = preg_replace_callback('/<img([^>]+)>/i', function ($matches) {
            $imgTag = $matches[0];
            $attributes = $matches[1];

            // Extract src attribute
            if (preg_match('/src=[\'"](.*?)[\'"]/', $attributes, $srcMatch)) {
                $src = $srcMatch[1];

                // Convert relative path to absolute URL jika diperlukan
                if (!str_starts_with($src, 'http') && !str_starts_with($src, '/')) {
                    $src = asset('storage/' . $src);
                } elseif (str_starts_with($src, '/storage/')) {
                    $src = asset($src);
                }

                // Rebuild img tag dengan styling untuk responsive
                return '<img src="' . $src . '" style="max-width: 100%; height: auto; max-height: 200px; border-radius: 4px; margin: 5px 0;" alt="Image">';
            }

            return $imgTag;
        }, $content);

        // Process lists dengan styling yang lebih baik
        $content = preg_replace('/<ol>/', '<ol style="margin: 10px 0; padding-left: 20px;">', $content);
        $content = preg_replace('/<ul>/', '<ul style="margin: 10px 0; padding-left: 20px;">', $content);

        // Process paragraphs
        $content = preg_replace('/<p>/', '<p style="margin: 5px 0;">', $content);

        // Wrap content in div untuk styling
        return '<div style="max-width: 100%; word-wrap: break-word;">' . $content . '</div>';
    }
}
