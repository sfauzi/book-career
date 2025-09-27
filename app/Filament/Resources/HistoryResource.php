<?php

namespace App\Filament\Resources;

use App\Filament\Resources\HistoryResource\Pages;
use App\Filament\Resources\HistoryResource\RelationManagers;
use App\Models\History;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Infolists;
use Filament\Infolists\Infolist;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class HistoryResource extends Resource
{
    protected static ?string $model = History::class;

    protected static ?string $navigationIcon = 'heroicon-o-clock';

    protected static ?string $navigationLabel = 'Riwayat Perubahan';

    protected static ?string $modelLabel = 'Riwayat';

    protected static ?string $pluralModelLabel = 'Riwayat Perubahan';

    protected static ?string $navigationGroup = 'Monitoring';

    protected static ?int $navigationSort = 10;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Placeholder::make('note')
                    ->label('Catatan')
                    ->content('Riwayat perubahan tidak dapat dibuat atau diubah secara manual. Data ini dibuat otomatis oleh sistem.'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('task.company_name')
                    ->label('Perusahaan')
                    ->sortable()
                    ->searchable()
                    ->limit(30),

                Tables\Columns\TextColumn::make('task.position')
                    ->label('Posisi')
                    ->sortable()
                    ->searchable()
                    ->limit(30),

                Tables\Columns\TextColumn::make('user.name')
                    ->label('User')
                    ->sortable()
                    ->searchable(),

                Tables\Columns\BadgeColumn::make('action')
                    ->label('Aksi')
                    ->colors([
                        'success' => 'created',
                        'warning' => 'updated',
                        'danger' => 'deleted',
                    ])
                    ->formatStateUsing(fn(string $state): string => match ($state) {
                        'created' => 'Dibuat',
                        'updated' => 'Diperbarui',
                        'deleted' => 'Dihapus',
                        default => ucfirst($state),
                    })
                    ->sortable(),

                Tables\Columns\TextColumn::make('changes_summary')
                    ->label('Ringkasan Perubahan')
                    ->limit(50)
                    ->formatStateUsing(function (History $record): string {
                        $totalChanges = count($record->changed_fields);

                        if ($totalChanges === 1) {
                            $firstField = array_key_first($record->changed_fields);
                            $change = $record->changed_fields[$firstField];
                            $label = $change['field_label'] ?? ucfirst(str_replace('_', ' ', $firstField));
                            return "{$label} diubah";
                        } else {
                            return "{$totalChanges} field diubah";
                        }
                    }),

                Tables\Columns\TextColumn::make('changedBy.name')
                    ->label('Diubah Oleh')
                    ->placeholder('System')
                    ->sortable()
                    ->toggleable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Waktu')
                    ->dateTime('d/m/Y H:i:s')
                    ->sortable()
                    ->toggleable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('action')
                    ->options([
                        'created' => 'Dibuat',
                        'updated' => 'Diperbarui',
                        'deleted' => 'Dihapus',
                    ])
                    ->label('Aksi'),

                // Tables\Filters\SelectFilter::make('user_id')
                //     ->relationship('user', 'name')
                //     ->label('User')
                //     ->searchable()
                //     ->preload(),

                // Tables\Filters\SelectFilter::make('task_id')
                //     ->relationship('task', 'company_name')
                //     ->label('Task')
                //     ->searchable()
                //     ->preload()
                //     ->getOptionLabelFromRecordUsing(fn($record) => "{$record->company_name} - {$record->position}"),

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
                    ->label('Periode'),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),

                Tables\Actions\Action::make('view_task')
                    ->label('Lihat Task')
                    ->icon('heroicon-o-eye')
                    ->color('info')
                    ->url(fn(History $record): string => TaskResource::getUrl('view', ['record' => $record->task_id]))
                    ->openUrlInNewTab(false),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                        ->requiresConfirmation()
                        ->modalHeading('Hapus Riwayat Perubahan')
                        ->modalDescription('Apakah Anda yakin ingin menghapus riwayat perubahan yang dipilih? Tindakan ini tidak dapat dibatalkan.')
                        ->modalSubmitActionLabel('Ya, Hapus'),
                ]),
            ])
            ->defaultSort('created_at', 'desc')
            ->poll('60s');
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Infolists\Components\Section::make('Informasi Task')
                    ->schema([
                        Infolists\Components\TextEntry::make('task.company_name')
                            ->label('Perusahaan'),

                        Infolists\Components\TextEntry::make('task.position')
                            ->label('Posisi'),

                        Infolists\Components\TextEntry::make('user.name')
                            ->label('User Pemilik Task'),

                        Infolists\Components\TextEntry::make('task.status')
                            ->label('Status Task Saat Ini')
                            ->badge()
                            ->color(fn(string $state): string => match ($state) {
                                'Applied' => 'primary',
                                'Interview' => 'warning',
                                'Test' => 'info',
                                'Diterima' => 'success',
                                'Ditolak' => 'danger',
                            }),
                    ])
                    ->columns(2),

                Infolists\Components\Section::make('Informasi Perubahan')
                    ->schema([
                        Infolists\Components\TextEntry::make('action')
                            ->label('Aksi')
                            ->badge()
                            ->color(fn(string $state): string => match ($state) {
                                'created' => 'success',
                                'updated' => 'warning',
                                'deleted' => 'danger',
                            })
                            ->formatStateUsing(fn(string $state): string => match ($state) {
                                'created' => 'Dibuat',
                                'updated' => 'Diperbarui',
                                'deleted' => 'Dihapus',
                                default => ucfirst($state),
                            }),

                        Infolists\Components\TextEntry::make('changedBy.name')
                            ->label('Diubah Oleh')
                            ->placeholder('System'),

                        Infolists\Components\TextEntry::make('created_at')
                            ->label('Waktu Perubahan')
                            ->dateTime('d/m/Y H:i:s'),
                    ])
                    ->columns(3),

                Infolists\Components\Section::make('Detail Perubahan')
                    ->schema([
                        Infolists\Components\RepeatableEntry::make('changed_fields')
                            ->label('')
                            ->schema([
                                Infolists\Components\TextEntry::make('field_label')
                                    ->label('Field'),
                                Infolists\Components\TextEntry::make('old_value')
                                    ->label('Nilai Lama')
                                    ->placeholder('kosong'),
                                Infolists\Components\TextEntry::make('new_value')
                                    ->label('Nilai Baru')
                                    ->placeholder('kosong'),
                            ])
                            ->columns(3)
                            ->getStateUsing(function (History $record): array {
                                $formatted = [];
                                foreach ($record->changed_fields as $field => $change) {
                                    $formatted[] = [
                                        'field_label' => $change['field_label'] ?? ucfirst(str_replace('_', ' ', $field)),
                                        'old_value' => $change['old_value'] ?? 'kosong',
                                        'new_value' => $change['new_value'] ?? 'kosong',
                                    ];
                                }
                                return $formatted;
                            }),
                    ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function canViewAny(): bool
    {
        return auth()->user()->can('viewAny', History::class);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListHistories::route('/'),
            // 'create' => Pages\CreateHistory::route('/create'),
            // 'edit' => Pages\EditHistory::route('/{record}/edit'),
            'view' => Pages\ViewHistory::route('/{record}'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->with(['task', 'user', 'changedBy']);
    }

    public static function canCreate(): bool
    {
        return false; // Tidak bisa create manual
    }

    public static function canEdit(Model $record): bool
    {
        return false; // Tidak bisa edit
    }
}
