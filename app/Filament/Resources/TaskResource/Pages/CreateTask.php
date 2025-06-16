<?php

namespace App\Filament\Resources\TaskResource\Pages;

use App\Filament\Resources\TaskResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateTask extends CreateRecord
{
    protected static string $resource = TaskResource::class;

    protected function afterCreate(): void
    {
        // Create initial history record untuk task baru
        $this->record->histories()->create([
            'user_id' => $this->record->user_id,
            'changed_fields' => [
                'initial' => [
                    'field_label' => 'Task Dibuat',
                    'old_value' => null,
                    'new_value' => 'Task baru dengan status ' . $this->record->status,
                ]
            ],
            'action' => 'created',
            'changed_by' => auth()->id(),
        ]);
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('view', ['record' => $this->getRecord()]);
    }
}
