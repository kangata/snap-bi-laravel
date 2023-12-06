<?php

namespace QuetzalStudio\SnapBi;

use Illuminate\Support\ServiceProvider;

class SnapBiServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__.'/../config/snap.php', 'snap');
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        $this->publishes([
            __DIR__.'/../config/snap.php' => config_path('snap.php'),
        ], 'snap-bi-config');
    }
}
