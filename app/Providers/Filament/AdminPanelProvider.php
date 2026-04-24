<?php

namespace App\Providers\Filament;

use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Pages;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\View\PanelsRenderHook;
use Filament\Widgets;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\AuthenticateSession;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\Support\Facades\Blade;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use App\Filament\Admin\Widgets\TopCompanyChart;
use App\Filament\Admin\Widgets\SalaryChart;
use App\Filament\Admin\Widgets\ExperienceChart;
use Filament\Navigation\MenuItem;
use Illuminate\Support\Facades\Auth;

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
            ->darkMode(false)
            ->brandName('Job-Analytic')
            // css sidebar
->renderHook(
    'panels::head.end',
    fn () => Blade::render('
        <style>
            /* bg */
            .fi-sidebar {
                background-color: #1f2937 !important;
                border-right: 1px solid rgba(255,255,255,0.05) !important;
            }

            .fi-sidebar-header {
                background: #1f2937 !important;
                border-bottom: 1px solid rgba(255,255,255,0.05) !important;
                justify-content: center !important;
            }

            /* brand */
            .fi-sidebar-header *,
            .fi-brand-name {
                color: #ffffff !important;
                text-align: center !important;
            }

            .fi-sidebar-nav-groups {
                margin-left: 0 !important;
                margin-right: 0 !important;
            }

            /* menu off */
            .fi-sidebar-item-button {
                color: #ffffff !important;
                border-radius: 12px !important;
                transition: all 0.2s ease;
            }

            .fi-sidebar-item-button .fi-sidebar-item-icon {
                color: #ffffff !important;
            }

            .fi-sidebar-item-button .fi-sidebar-item-label {
                color: #ffffff !important;
            }

            /* override tailwind */
            .fi-sidebar-item-button .text-gray-700,
            .fi-sidebar-item-button .dark\:text-gray-200,
            .fi-sidebar-item-button .text-gray-400,
            .fi-sidebar-item-button .dark\:text-gray-500 {
                color: #ffffff !important;
            }

            /* hover */
            .fi-sidebar-item-button:hover,
            .fi-sidebar-item-button.hover\:bg-gray-100:hover {
                background-color: rgba(255,255,255,0.08) !important;
            }

            /* menu on */
            .fi-sidebar-item-active .fi-sidebar-item-button,
            .fi-sidebar-item-button.bg-gray-100 {
                background-color: #ffffff !important;
                border-radius: 12px !important;
                width: 100% !important;
                margin: 0 !important;
                padding-left: 12px !important;
                padding-right: 12px !important;
            }

            /* icon, teks aktif */
            .fi-sidebar-item-active .fi-sidebar-item-icon,
            .fi-sidebar-item-active .fi-sidebar-item-label,
            .fi-sidebar-item-active .text-primary-600,
            .fi-sidebar-item-button.bg-gray-100 .fi-sidebar-item-icon,
            .fi-sidebar-item-button.bg-gray-100 .fi-sidebar-item-label,
            .fi-sidebar-item-button .text-primary-600,
            .fi-sidebar-item-button .dark\:text-primary-400 {
                color: #206bc4 !important;
            }

            /* topbar */
            .fi-topbar {
                background-color: #ffffff !important;
                border-bottom: 1px solid #e5e7eb !important;
            }

            /* bg konten */
            .fi-main {
                background-color: #f9fafb !important;
            }

            /* card */
            .fi-card {
                background: #ffffff !important;
                border-radius: 12px !important;
                border: 1px solid #e5e7eb !important;
                box-shadow: 0 1px 3px rgba(0,0,0,0.05) !important;
            }

            body {
                color: #111827 !important;
            }
        </style>
    ')
)

            ->discoverResources(in: app_path('Filament/Admin/Resources'), for: 'App\\Filament\\Admin\\Resources')
            ->discoverPages(in: app_path('Filament/Admin/Pages'), for: 'App\\Filament\\Admin\\Pages')
            ->pages([
                Pages\Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Admin/Widgets'), for: 'App\\Filament\\Admin\\Widgets')
            ->widgets([
                Widgets\AccountWidget::class,
                Widgets\FilamentInfoWidget::class,
            ])
            ->renderHook(
                    'panels::sidebar.footer',
                    fn () => view('filament.sidebar-footer')
                )
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