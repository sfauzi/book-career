<?php

namespace App\Filament\Resources\TaskResource\Pages;

use App\Filament\Resources\TaskResource;
use Filament\Actions;
use Filament\Resources\Components\Tab;
use Illuminate\Database\Eloquent\Builder;
use Filament\Resources\Pages\ListRecords;

class ListTasks extends ListRecords
{
    protected static string $resource = TaskResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

    public function getTabs(): array
    {
        return [
            'all' => Tab::make()
                ->modifyQueryUsing(fn($query) => $query->where('is_closed', '!=', true)),

            'Closed' => Tab::make()
                ->modifyQueryUsing(fn(Builder $query) => $query->where('is_closed', true)),

            'Applied' => Tab::make()
                ->modifyQueryUsing(fn(Builder $query) => $query->where('status', 'Applied')),

            'Interview' => Tab::make()
                ->modifyQueryUsing(fn(Builder $query) => $query->where('status', 'Interview')),

            'Test' => Tab::make()
                ->modifyQueryUsing(fn(Builder $query) => $query->where('status', 'Test')),

            'Diterima' => Tab::make()
                ->modifyQueryUsing(fn(Builder $query) => $query->where('status', 'Diterima')),

            'Ditolak' => Tab::make()
                ->modifyQueryUsing(fn(Builder $query) => $query->where('status', 'Ditolak')),

        ];
    }
}
