<?php

namespace App\Filament\Resources\TaskResource\Pages;

use App\Filament\Resources\TaskResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditTask extends EditRecord
{
    protected static string $resource = TaskResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
            Actions\Action::make('view_history')
                ->label('Lihat History')
                ->icon('heroicon-o-clock')
                ->color('info')
                ->url(fn(): string => TaskResource::getUrl('history', ['record' => $this->record])),
        ];
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('view', ['record' => $this->getRecord()]);
    }
}
