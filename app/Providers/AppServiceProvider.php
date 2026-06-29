<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL; // Import necessário para gerir os links

/**
 * Service Provider principal da aplicação
 */
class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Força o uso de HTTPS se a aplicação estiver em produção (no Render)
        if (config('app.env') === 'production') {
            URL::forceScheme('https');
        }
    }
}