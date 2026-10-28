<?php

namespace App\Filament\Widgets;

use App\Models\Election;
use App\Models\User;
use App\Models\Vote;
use Filament\Widgets\ChartWidget as BaseWidget;

class JurusanDoughnutChart extends BaseWidget
{
    protected static ?string $heading = 'Persebaran suara per jurusan — pemilihan terbaru';

    protected static ?int $sort = 4;

    protected static ?string $maxHeight = '260px';

    protected function getType(): string
    {
        return 'doughnut';
    }

    protected function getData(): array
    {
        $election = Election::published()->orderByDesc('selesai')->first();

        if (! $election) {
            return ['datasets' => [['data' => []]], 'labels' => []];
        }

        $perJurusan = [];
        foreach (Vote::where('pemilu_id', (string) $election->id)->get() as $v) {
            $nama = User::find($v->user_id)?->jurusan?->title ?: 'Profil tidak diisi';
            $perJurusan[$nama] = ($perJurusan[$nama] ?? 0) + 1;
        }
        arsort($perJurusan);

        $top = array_slice($perJurusan, 0, 5, true);
        if (($sisanya = array_sum($perJurusan) - array_sum($top)) > 0) {
            $top['Lainnya'] = $sisanya;
        }

        return [
            'datasets' => [[
                'data' => array_values($top),
                'backgroundColor' => ['#2a78d6', '#eb6834', '#1baf7a', '#eda100', '#e87ba4', '#898781'],
                'borderWidth' => 2,
            ]],
            'labels' => array_keys($top),
        ];
    }
}
