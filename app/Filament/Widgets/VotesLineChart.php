<?php

namespace App\Filament\Widgets;

use App\Models\Vote;
use Carbon\Carbon;
use Filament\Widgets\ChartWidget as BaseWidget;

class VotesLineChart extends BaseWidget
{
    protected ?string $heading = 'Suara masuk per hari (14 hari terakhir)';

    protected static ?int $sort = 2;

    protected ?string $maxHeight = '260px';

    protected function getType(): string
    {
        return 'line';
    }

    protected function getData(): array
    {
        $perHari = Vote::query()
            ->where('created_at', '>=', Carbon::today()->subDays(13))
            ->get()
            ->groupBy(fn ($v) => $v->created_at->format('Y-m-d'))
            ->map->count();

        $labels = [];
        $data = [];
        for ($d = 13; $d >= 0; $d--) {
            $hari = Carbon::today()->subDays($d);
            $labels[] = $hari->format('j/n');
            $data[] = $perHari[$hari->format('Y-m-d')] ?? 0;
        }

        return [
            'datasets' => [[
                'label' => 'Suara',
                'data' => $data,
                'borderColor' => '#F77F00',
                'backgroundColor' => 'rgba(247,127,0,.15)',
                'fill' => true,
                'tension' => .35,
                'pointRadius' => 2.5,
            ]],
            'labels' => $labels,
        ];
    }
}
