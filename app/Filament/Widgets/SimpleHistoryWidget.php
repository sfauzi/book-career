<?php

namespace App\Filament\Widgets;

use App\Models\History;
use Filament\Widgets\Widget;

class SimpleHistoryWidget extends Widget
{
    protected static string $view = 'filament.widgets.simple-history-widget';

    protected static ?string $heading = 'Riwayat Perubahan Terbaru';

    protected static ?int $sort = 1;

    // protected int | string | array $columnSpan = 'full';

    protected static ?string $pollingInterval = '30s';

    public function getViewData(): array
    {
        $histories = History::query()
            ->where('action', 'updated')
            ->with(['task', 'changedBy'])
            ->latest()
            ->limit(8)
            ->get();

        return [
            'histories' => $histories,
        ];
    }

    public static function canView(): bool
    {
        return auth()->check();
    }
}
