<?php

namespace App\Filament\Widgets;

use Filament\Widgets\Widget;

/**
 * Embed halaman analitik penuh (/analitik-cp, superadmin only) di dashboard.
 * Iframe dipakai karena Chart.js + CSS custom tidak bisa disuntik ke Livewire widget.
 */
class AnalitikEmbed extends Widget
{
    protected static string $view = 'filament.widgets.analitik-embed';

    protected int | string | array $columnSpan = 'full';

    protected static ?int $sort = 10;

    protected function getViewData(): array
    {
        return ['url' => url('/analitik-cp')];
    }
}
