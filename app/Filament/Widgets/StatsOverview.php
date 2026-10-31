<?php

namespace App\Filament\Widgets;

use App\Models\Article;
use App\Models\Division;
use App\Models\Official;
use App\Models\Suggestion;
use App\Models\User;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends BaseWidget
{
    protected ?string $heading = 'Ringkasan';

    protected int | string | array $columnSpan = 'full';

    protected static ?int $sort = 0;

    protected function getStats(): array
    {
        return [
            Stat::make('Pengguna', User::count())->description('Akun terdaftar')->color('gray')->icon('heroicon-o-identification'),
            Stat::make('Berita', Article::count())->color('info')->icon('heroicon-o-newspaper'),
            Stat::make('Pengurus', Official::count())->color('warning')->icon('heroicon-o-users'),
            Stat::make('Sekbid & Divisi', Division::count())->color('gray')->icon('heroicon-o-squares-2x2'),
            Stat::make('Saran masuk', Suggestion::count())->color('gray')->icon('heroicon-o-chat-bubble-left-ellipsis'),
        ];
    }
}
