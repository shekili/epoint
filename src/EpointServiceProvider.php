<?php

namespace Shekili\Epoint;

use Illuminate\Support\ServiceProvider;

class EpointServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(
            __DIR__ . '/../config/epoint.php',
            'epoint'
        );

        $this->app->singleton(Epoint::class, function ($app) {
            return new Epoint($app['config']['epoint']);
        });

        $this->app->alias(Epoint::class, 'epoint');
    }

    public function boot(): void
    {
        if ($this->app->runningInConsole()) {
            // Config publish
            $this->publishes([
                __DIR__ . '/../config/epoint.php' => config_path('epoint.php'),
            ], 'epoint-config');

            // Migration publish
            $this->publishes([
                __DIR__ . '/../database/migrations/' => database_path('migrations'),
            ], 'epoint-migrations');
        }

        // Migration-u avtomatik yüklə (publish etmədən işləsin)
        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');
    }
}
