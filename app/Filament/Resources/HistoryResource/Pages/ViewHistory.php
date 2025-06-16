<?php

namespace App\Filament\Resources\HistoryResource\Pages;

use App\Filament\Resources\HistoryResource;
use App\Filament\Resources\TaskResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewHistory extends ViewRecord
{
    protected static string $resource = HistoryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('view_task')
                ->label('Lihat Task Terkait')
                ->icon('heroicon-o-arrow-top-right-on-square')
                ->url(fn(): string => TaskResource::getUrl('view', ['record' => $this->record->task_id]))
                ->color('info'),
        ];
    }
}
