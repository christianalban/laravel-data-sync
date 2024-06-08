<?php

namespace Alban\LaravelDataSync;

use Alban\LaravelDataSync\Support\DataSync;
use Illuminate\Support\ServiceProvider;

class LaravelDataSyncServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        $this->publishConfig();

        $this->registerFacade();
    }

    private function publishConfig(): void
    {
        $this->publishes([
            __DIR__.'/../config/data-sync.php' => config_path('data-sync.php'),
        ], 'data-sync-config');
    }

    private function registerFacade(): void
    {
        $this->app->bind('data-sync', function () {
            return new DataSync();
        });
    }
}
