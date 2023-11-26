<?php

namespace DualityStudio\LaraSecurity;

use Illuminate\Support\Facades\{Blade, Vite};
use Illuminate\Support\ServiceProvider;

class LaraSecurityServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton('lara-security-nonce', NonceGenerator::class);

        if (config('lara-security.uses_vite')) {
            Vite::useCspNonce(app('lara-security-nonce')->generate(Directives::SCRIPT));
        }
    }

    public function boot(): void
    {
        $this->publishes([
            __DIR__.'/../config/lara-security.php' => config_path('lara-security.php'),
        ]);

        Blade::directive('nonce', function ($directive) {
            return "nonce=\"<?php echo app('lara-security-nonce')->generate($directive); ?>\"";
        });
    }
}
