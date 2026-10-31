<?php

namespace App\Filament\Widgets;

use App\Models\Candidate;
use App\Models\Election;
use App\Models\Vote;
use Filament\Widgets\ChartWidget as BaseWidget;

class CandidatesBarChart extends BaseWidget
{
    protected ?string $heading = 'Suara per kandidat — pemilihan terbaru';

    protected static ?int $sort = 3;

    protected ?string $maxHeight = '260px';

    protected function getType(): string
    {
        return 'bar';
    }

    protected function getData(): array
    {
        $election = Election::published()->orderByDesc('selesai')->first();

        if (! $election) {
            return ['datasets' => [['label' => 'Suara', 'data' => []]], 'labels' => []];
        }

        $votes = Vote::where('pemilu_id', (string) $election->id)->get();

        $kandidat = $election->candidates()->orderBy('no_urut')->get()
            ->map(fn ($k) => [
                'label' => $k->no_urut.'. '.$k->title,
                'suara' => $votes->where('kandidat_id', (string) $k->id)->count(),
            ])->sortByDesc('suara')->values();

        return [
            'datasets' => [[
                'label' => $election->title,
                'data' => $kandidat->pluck('suara')->all(),
                'backgroundColor' => '#2a78d6',
                'borderRadius' => 6,
                'maxBarThickness' => 42,
            ]],
            'labels' => $kandidat->pluck('label')->all(),
        ];
    }
}
