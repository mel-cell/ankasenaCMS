<?php

namespace App\Providers\Filament;

use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Panel;
use Filament\PanelProvider;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('admin')
            ->path('ankasena/panel')
            ->login()
            ->pages([\Filament\Pages\Dashboard::class])
            ->discoverResources(in: base_path('app/Filament/Resources'), for: 'App\\Filament\\Resources')
            ->discoverPages(in: base_path('app/Filament/Pages'), for: 'App\\Filament\\Pages')
            // Dasbor global: hanya widget umum. Chart pemilu hidup di Ringkasan Pemilu.
            ->widgets([\App\Filament\Widgets\StatsOverview::class])
            ->colors(['primary' => '#F77F00'])
            ->defaultThemeMode(\Filament\Enums\ThemeMode::Light)
            ->favicon(asset('favicon.svg'))
            ->font('Plus Jakarta Sans Variable', asset('fonts/pjk.css'), \Filament\FontProviders\LocalFontProvider::class)
            ->maxContentWidth('7xl')
            ->brandName('Ankasena')
            ->brandLogo(fn () => view('brand-mark'))
            ->brandLogoHeight('36px')
            ->navigationGroups(['Konten', 'Organisasi', 'Pemilu', 'Twibon', 'Pengaturan'])
            ->collapsibleNavigationGroups()
            ->sidebarCollapsibleOnDesktop()
            ->renderHook(
                'panels::styles.end',
                fn () => '<style>
                    /* Bump radius ala UI lama (rounded): kartu, input, tombol, avatar. */
                    .fi-card,.fi-section,.fi-input-wrp,.fi-btn,.fi-avatar,.fi-dropdown-list,.fi-modal-window,.fi-select,.fi-tt,.fi-tables{border-radius:14px!important}
                    .fi-input,.fi-select,.fi-tt,.fi-tn{border-radius:10px!important}
                </style>'
            )
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                ShareErrorsFromSession::class,
                VerifyCsrfToken::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
            ])
            ->authMiddleware([
                Authenticate::class,
            ]);
    }
}
