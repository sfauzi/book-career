<?php

namespace App\Filament\Widgets;

use App\Models\Task;
use App\Models\User;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends BaseWidget
{
    protected static ?int $sort = -2;

    protected function getStats(): array
    {

        $user = auth()->user();

        if ($user->isAdmin()) {
            return [
                Stat::make('Tasks Applied', Task::where('status', 'applied')->count())
                    ->description('Total tasks dengan status applied')
                    ->descriptionIcon('heroicon-m-check-circle')
                    ->color('success'),

                Stat::make('Admin Users', User::count())
                    ->description('Jumlah user terdaftar')
                    ->descriptionIcon('heroicon-m-user-group')
                    ->color('primary'),
            ];
        } else {
            return [
                Stat::make('Tasks Applied', Task::where('status', 'applied')->count())
                    ->description('Total tasks dengan status applied')
                    ->descriptionIcon('heroicon-m-check-circle')
                    ->color('success'),
            ];
        }
    }
}
