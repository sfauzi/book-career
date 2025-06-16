<?php

namespace App\Filament\Resources\TaskResource\Pages;

use App\Filament\Resources\TaskResource;
use App\Models\Task;
use App\Models\History;
use Filament\Resources\Pages\Page;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Forms;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Contracts\View\View;

class TaskHistory extends Page implements HasTable
{
    use InteractsWithTable;

    protected static string $resource = TaskResource::class;

    protected static string $view = 'filament.resources.task-resource.pages.task-history';

    protected static ?string $title = 'Riwayat Perubahan Task';

    public Task $record;

    public function mount(int | string $record): void
    {
        $this->record = $this->resolveRecord($record);

        static::authorizeResourceAccess();
    }

    protected function resolveRecord(int | string $key): Task
    {
        return static::getResource()::resolveRecordRouteBinding($key);
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(
                History::query()
                    ->where('task_id', $this->record->id)
                    ->with(['changedBy'])
            )
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

                Tables\Columns\TextColumn::make('change_summary')
                    ->label('Ringkasan Perubahan')
                    ->html()
                    ->wrap()
                    ->formatStateUsing(function (History $record): string {
                        $totalChanges = count($record->changed_fields);
                        $firstChange = array_key_first($record->changed_fields);

                        if ($totalChanges === 1) {
                            $change = $record->changed_fields[$firstChange];
                            $label = $change['field_label'] ?? ucfirst(str_replace('_', ' ', $firstChange));
                            return "<strong>{$label}</strong> diubah";
                        } else {
                            return "<strong>{$totalChanges} field</strong> diubah";
                        }
                    }),

                Tables\Columns\TextColumn::make('changedBy.name')
                    ->label('Diubah Oleh')
                    ->placeholder('System')
                    ->sortable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Waktu')
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
                    ->label('Periode Perubahan'),
            ])
            ->actions([
                Tables\Actions\ViewAction::make()
                    ->label('Detail')
                    ->modalHeading('Detail Riwayat Perubahan')
                    ->modalWidth('2xl')
                    ->form([
                        Forms\Components\Grid::make(2)
                            ->schema([
                                Forms\Components\TextInput::make('action')
                                    ->label('Aksi')
                                    ->disabled()
                                    ->formatStateUsing(fn(string $state): string => match ($state) {
                                        'created' => 'Dibuat',
                                        'updated' => 'Diperbarui',
                                        'deleted' => 'Dihapus',
                                        default => ucfirst($state),
                                    }),

                                Forms\Components\TextInput::make('created_at')
                                    ->label('Waktu Perubahan')
                                    ->disabled()
                                    ->formatStateUsing(fn($state) => $state?->format('d/m/Y H:i:s')),

                                Forms\Components\TextInput::make('changedBy.name')
                                    ->label('Diubah Oleh')
                                    ->disabled()
                                    ->default('System'),
                            ]),

                        Forms\Components\Repeater::make('changed_fields')
                            ->label('Detail Perubahan')
                            ->schema([
                                Forms\Components\TextInput::make('field_label')
                                    ->label('Field')
                                    ->disabled(),
                                Forms\Components\Textarea::make('old_value')
                                    ->label('Nilai Lama')
                                    ->disabled()
                                    ->placeholder('kosong'),
                                Forms\Components\Textarea::make('new_value')
                                    ->label('Nilai Baru')
                                    ->disabled()
                                    ->placeholder('kosong'),
                            ])
                            ->columns(3)
                            ->disabled()
                            ->formatStateUsing(function (History $record): array {
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
            ])
            ->defaultSort('created_at', 'desc')
            ->poll('30s')
            ->emptyStateHeading('Belum Ada Riwayat Perubahan')
            ->emptyStateDescription('Riwayat perubahan akan muncul ketika task ini dimodifikasi.')
            ->emptyStateIcon('heroicon-o-clock');
    }

    protected function getHeaderActions(): array
    {
        return [
            Tables\Actions\Action::make('back')
                ->label('Kembali ke Task')
                ->url(TaskResource::getUrl('view', ['record' => $this->record]))
                ->icon('heroicon-o-arrow-left')
                ->color('gray'),
        ];
    }

    public function getTitle(): string
    {
        return "Riwayat Perubahan: {$this->record->company_name} - {$this->record->position}";
    }

    protected function getHeaderWidgets(): array
    {
        return [
            // TaskResource\Widgets\TaskHistoryStats::class,
        ];
    }
}
