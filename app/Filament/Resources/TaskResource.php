<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TaskResource\Pages;
use App\Filament\Resources\TaskResource\RelationManagers;
use App\Models\Task;
use Filament\Forms;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Infolists;
use Filament\Infolists\Infolist;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class TaskResource extends Resource
{
    protected static ?string $model = Task::class;

    protected static ?string $navigationIcon = 'heroicon-o-briefcase';

    protected static ?string $navigationLabel = 'Tasks';

    protected static ?string $modelLabel = 'Task';

    protected static ?string $pluralModelLabel = 'Tasks';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Informasi Task')
                    ->schema([
                        Hidden::make('user_id')
                            ->default(auth()->id()),
                        // Forms\Components\TextInput::make('user.name')
                        //     ->label('Name')
                        //     ->required()
                        //     ->default(auth()->user()->name)
                        //     ->disabled(),
                        // Forms\Components\Select::make('user_id')
                        //     ->relationship('user', 'name')
                        //     ->required()
                        //     ->searchable()
                        //     ->preload()
                        //     ->label('User'),

                        Forms\Components\TextInput::make('company_name')
                            ->required()
                            ->maxLength(255)
                            ->label('Nama Perusahaan'),

                        Forms\Components\TextInput::make('position')
                            ->required()
                            ->maxLength(255)
                            ->label('Posisi'),

                        Forms\Components\DatePicker::make('applied_date')
                            ->required()
                            ->label('Tanggal Melamar')
                            ->default(now()),

                        Forms\Components\Select::make('status')
                            ->options([
                                'Applied' => 'Applied',
                                'Interview' => 'Interview',
                                'Test' => 'Test',
                                'Diterima' => 'Diterima',
                                'Ditolak' => 'Ditolak',
                            ])
                            ->required()
                            ->default('Applied')
                            ->label('Status'),

                        Forms\Components\TextInput::make('platform')
                            ->maxLength(255)
                            ->label('Platform')
                            ->placeholder('LinkedIn, JobStreet, dll.'),
                    ])
                    ->columns(2),

                Forms\Components\Section::make('Catatan')
                    ->schema([
                        Forms\Components\Textarea::make('notes')
                            ->label('Catatan')
                            ->rows(4)
                            ->columnSpanFull(),
                    ])
                    ->collapsible(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('user.name')
                    ->label('User')
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('company_name')
                    ->label('Perusahaan')
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('position')
                    ->label('Posisi')
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('applied_date')
                    ->label('Tanggal Melamar')
                    ->date()
                    ->sortable(),

                Tables\Columns\BadgeColumn::make('status')
                    ->label('Status')
                    ->colors([
                        'primary' => 'Applied',
                        'warning' => 'Interview',
                        'info' => 'Test',
                        'success' => 'Diterima',
                        'danger' => 'Ditolak',
                    ])
                    ->sortable(),

                Tables\Columns\TextColumn::make('platform')
                    ->label('Platform')
                    ->sortable()
                    ->toggleable(),

                Tables\Columns\TextColumn::make('histories_count')
                    ->label('Jumlah Perubahan')
                    ->counts('histories')
                    ->sortable()
                    ->toggleable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Dibuat')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Diperbarui')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'Applied' => 'Applied',
                        'Interview' => 'Interview',
                        'Test' => 'Test',
                        'Diterima' => 'Diterima',
                        'Ditolak' => 'Ditolak',
                    ])
                    ->label('Status'),

                Tables\Filters\SelectFilter::make('user_id')
                    ->relationship('user', 'name')
                    ->label('User')
                    ->searchable()
                    ->preload(),

                Tables\Filters\Filter::make('applied_date')
                    ->form([
                        Forms\Components\DatePicker::make('applied_from')
                            ->label('Tanggal Melamar Dari'),
                        Forms\Components\DatePicker::make('applied_until')
                            ->label('Tanggal Melamar Sampai'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['applied_from'],
                                fn(Builder $query, $date): Builder => $query->whereDate('applied_date', '>=', $date),
                            )
                            ->when(
                                $data['applied_until'],
                                fn(Builder $query, $date): Builder => $query->whereDate('applied_date', '<=', $date),
                            );
                    })
                    ->label('Tanggal Melamar'),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),

                Tables\Actions\Action::make('view_history')
                    ->label('Lihat History')
                    ->icon('heroicon-o-clock')
                    ->color('info')
                    ->url(fn(Task $record): string => route('filament.app.resources.tasks.history', $record))
                    ->openUrlInNewTab(false),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('created_at', 'desc');
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Infolists\Components\Section::make('Informasi Task')
                    ->schema([
                        Infolists\Components\TextEntry::make('user.name')
                            ->label('User'),

                        Infolists\Components\TextEntry::make('company_name')
                            ->label('Nama Perusahaan'),

                        Infolists\Components\TextEntry::make('position')
                            ->label('Posisi'),

                        Infolists\Components\TextEntry::make('applied_date')
                            ->label('Tanggal Melamar')
                            ->date(),

                        Infolists\Components\TextEntry::make('status')
                            ->label('Status')
                            ->badge()
                            ->color(fn(string $state): string => match ($state) {
                                'Applied' => 'primary',
                                'Interview' => 'warning',
                                'Test' => 'info',
                                'Diterima' => 'success',
                                'Ditolak' => 'danger',
                            }),

                        Infolists\Components\TextEntry::make('platform')
                            ->label('Platform')
                            ->placeholder('Tidak ada'),
                    ])
                    ->columns(2),

                Infolists\Components\Section::make('Catatan')
                    ->schema([
                        Infolists\Components\TextEntry::make('notes')
                            ->label('Catatan')
                            ->placeholder('Tidak ada catatan')
                            ->markdown(),
                    ])
                    ->collapsible(),

                Infolists\Components\Section::make('Informasi Tambahan')
                    ->schema([
                        Infolists\Components\TextEntry::make('created_at')
                            ->label('Dibuat')
                            ->dateTime(),

                        Infolists\Components\TextEntry::make('updated_at')
                            ->label('Terakhir Diperbarui')
                            ->dateTime(),

                        Infolists\Components\TextEntry::make('histories_count')
                            ->label('Jumlah Perubahan')
                            ->state(fn(Task $record): int => $record->histories()->count()),
                    ])
                    ->columns(3)
                    ->collapsible(),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            RelationManagers\HistoriesRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTasks::route('/'),
            'create' => Pages\CreateTask::route('/create'),
            'edit' => Pages\EditTask::route('/{record}/edit'),
            'view' => Pages\ViewTask::route('/{record}'),
            'history' => Pages\TaskHistory::route('/{record}/history'),

        ];
    }
}
