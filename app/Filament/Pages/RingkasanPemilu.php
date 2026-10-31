<?php

namespace App\Filament\Pages;

use BackedEnum;
use UnitEnum;

use App\Filament\Widgets\CandidatesBarChart;
use App\Filament\Widgets\JurusanDoughnutChart;
use App\Filament\Widgets\VotesLineChart;
use Filament\Pages\Page;

class RingkasanPemilu extends Page
{
    protected static string | BackedEnum | null $navigationIcon = 'heroicon-o-chart-pie';

    protected static string | UnitEnum | null $navigationGroup = 'Pemilu';

    protected static ?string $navigationLabel = 'Ringkasan';

    protected static ?int $navigationSort = 0;

    protected static ?string $title = 'Ringkasan Pemilu';

    public static function canAccess(): bool
    {
        return auth()->user()?->isAdmin() ?? false;
    }

    // Dashboard mini pemilu: chart suara hidup di sini.
    protected function getFooterWidgets(): array
    {
        return [VotesLineChart::class, CandidatesBarChart::class, JurusanDoughnutChart::class];
    }

    protected string $view = 'filament.pages.ringkasan-pemilu';
}
