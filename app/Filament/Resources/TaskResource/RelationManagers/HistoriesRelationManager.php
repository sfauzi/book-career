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

                            $changes[] = "<strong>{$label}:</strong> {$oldValue} → {$newValue}";
                        }

                        return implode('<br>', $changes);
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
                                    $oldValue = $change['old_value'] ?? 'kosong';
                                    $newValue = $change['new_value'] ?? 'kosong';

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
}
