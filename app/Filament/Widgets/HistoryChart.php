<?php

namespace App\Filament\Widgets;

use App\Models\History;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

class HistoryChart extends ChartWidget
{
    protected static ?string $heading = 'User History Overview';


    protected function getData(): array
    {
        // Ambil data group by tanggal & user
        $histories = History::select(
            DB::raw('DATE(histories.created_at) as date'),
            'user_id',
            'users.name as user_name',
            DB::raw('count(*) as total')
        )
            ->join('users', 'users.id', '=', 'histories.user_id')
            ->groupBy('date', 'user_id', 'users.name')
            ->orderBy('date')
            ->get();

        // Ambil daftar user unik
        $users = $histories->pluck('user_name', 'user_id')->unique();

        // Buat dataset per user
        $datasets = [];
        foreach ($users as $userId => $userName) {
            $userData = $histories->where('user_id', $userId);

            $datasets[] = [
                'label' => $userName,
                'data' => $userData->pluck('total', 'date'),
                'fill' => false,
                'borderColor' => sprintf('#%06X', mt_rand(0, 0xFFFFFF)), // random color
            ];
        }

        // Ambil label tanggal (x-axis)
        $labels = $histories->pluck('date')->unique()->values();

        return [
            'datasets' => $datasets,
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}
