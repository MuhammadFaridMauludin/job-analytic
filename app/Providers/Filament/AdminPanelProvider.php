<?php

namespace App\Providers\Filament;

use App\Filament\Admin\Pages\Dashboard;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Widgets;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\AuthenticateSession;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\Support\Facades\Blade;
use Illuminate\View\Middleware\ShareErrorsFromSession;

class AdminPanelProvider extends PanelProvider
{
    
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->id('admin')
            ->path('admin')
            ->login()
            ->colors([
                'primary' => Color::Blue,
            ])
            
            ->renderHook(
                'panels::head.end',
                fn () => Blade::render('
                    <style>
                        /* bg */
                        body,
                        .fi-body,
                        .fi-layout,
                        .fi-main,
                        .fi-page,
                        .fi-dashboard-page,
                        .fi-simple-layout {
                            background-color: #080c14 !important;
                        }

                        /* konten */
                        .fi-main-ctn {
                            padding: 0 !important;
                        }

                        .fi-page-header {
                            padding: 12px 20px 0 !important;
                        }

                        .fi-dashboard-page .fi-wi-stats-overview,
                        .fi-page .fi-widgets-container {
                            padding: 16px 20px !important;
                            gap: 12px !important;
                        }

                        .fi-widgets-container {
                            gap: 12px !important;
                            padding: 16px 20px 20px !important;
                        }

                        /* sidebar */
                        .fi-sidebar {
                            background-color: #060a10 !important;
                            border-right: 1px solid #111827 !important;
                        }

                        .fi-sidebar-header {
                            background-color: #060a10 !important;
                            border-bottom: 1px solid #111827 !important;
                            padding: 14px 16px !important;
                        }

                        .fi-sidebar-header .fi-logo,
                        .fi-sidebar-header a,
                        .fi-sidebar-header span {
                            color: #f1f5f9 !important;
                            font-weight: 700 !important;
                            font-size: 13px !important;
                            line-height: 1.35 !important;
                        }

                        /* Nav */
                        .fi-sidebar-nav-groups .fi-sidebar-item-button {
                            color: #64748b !important;
                            border-radius: 0 !important;
                            border-left: 2px solid transparent !important;
                            margin: 0 !important;
                            padding: 8px 16px !important;
                            font-size: 13px !important;
                        }

                        .fi-sidebar-nav-groups .fi-sidebar-item-button:hover {
                            background-color: #0d1420 !important;
                            color: #94a3b8 !important;
                        }

                        .fi-sidebar-nav-groups .fi-sidebar-item-button.fi-active {
                            background-color: #0d1829 !important;
                            color: #60a5fa !important;
                            border-left: 2px solid #3b82f6 !important;
                            font-weight: 500 !important;
                        }

                        .fi-sidebar-group-label {
                            color: #334155 !important;
                            font-size: 10px !important;
                            letter-spacing: 1px !important;
                            text-transform: uppercase !important;
                            font-weight: 600 !important;
                            padding: 10px 16px 4px !important;
                        }

                        .fi-sidebar-footer {
                            background-color: #060a10 !important;
                            border-top: 1px solid #111827 !important;
                        }

                        /* topbar */
                        .fi-topbar {
                            background-color: #080c14 !important;
                            border-bottom: 1px solid #111827 !important;
                        }

                        /* widgets card */
                        .fi-wi-chart,
                        .fi-wi-stats-overview,
                        .fi-wi-table {
                            background-color: #0d1420 !important;
                            border: 1px solid #111827 !important;
                            border-radius: 10px !important;
                        }

                        .fi-wi-chart-header-heading,
                        .fi-section-header-heading {
                            color: #e2e8f0 !important;
                            font-size: 13px !important;
                            font-weight: 600 !important;
                        }

                        .fi-wi-chart-header-description,
                        .fi-section-header-description {
                            color: #475569 !important;
                            font-size: 11px !important;
                        }

                        /* stat overview */
                        .fi-wi-stats-overview-stat {
                            background-color: #0d1420 !important;
                            border: 1px solid #111827 !important;
                            border-radius: 10px !important;
                            padding: 16px 18px !important;
                        }

                        .fi-wi-stats-overview-stat-label {
                            color: #475569 !important;
                            font-size: 10.5px !important;
                            font-weight: 500 !important;
                            text-transform: uppercase !important;
                            letter-spacing: 0.7px !important;
                        }

                        .fi-wi-stats-overview-stat-value {
                            color: #f1f5f9 !important;
                            font-size: 28px !important;
                            font-weight: 700 !important;
                            line-height: 1.1 !important;
                        }

                        .fi-wi-stats-overview-stat-description {
                            color: #475569 !important;
                            font-size: 11px !important;
                        }

                        /* tabel */
                        .fi-ta-table { background-color: #0d1420 !important; }
                        .fi-ta-header-cell {
                            background-color: #0d1420 !important;
                            color: #475569 !important;
                            font-size: 10.5px !important;
                            text-transform: uppercase !important;
                            letter-spacing: 0.6px !important;
                            border-bottom: 1px solid #111827 !important;
                        }
                        .fi-ta-cell { color: #94a3b8 !important; font-size: 12px !important; }
                        .fi-ta-row { border-bottom: 1px solid #0f1724 !important; }
                    </style>
                ')
            ) 

            ->brandLogo(fn () => view('filament.brand'))
            // ->renderHook(
            //     'panels::topbar.end',
            //     fn () => view('filament.topbar-right')
            // )
            // ->renderHook(
            //     'panels::header.start',
            //     fn () => view('filament.topbar-right')
            // )
            // ->renderHook(
            //     'panels::sidebar.footer',
            //     fn () => view('filament.sidebar-footer')
            // )
            ->discoverResources(in: app_path('Filament/Admin/Resources'), for: 'App\\Filament\\Admin\\Resources')
            ->discoverPages(in: app_path('Filament/Admin/Pages'), for: 'App\\Filament\\Admin\\Pages')
            ->pages([
                Dashboard::class, 
            ])
            ->discoverWidgets(in: app_path('Filament/Admin/Widgets'), for: 'App\\Filament\\Admin\\Widgets')
            ->widgets([
            ])
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
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