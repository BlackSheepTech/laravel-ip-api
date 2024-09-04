<?php

namespace BlackSheepTech\IpApi;

use Illuminate\Support\ServiceProvider;

/**
 * @internal
 */
class IpApiServiceProvider extends ServiceProvider
{
    /**
     * Register any package services.
     */
    public function register(): void
    {
        $this->mergeConfigFrom(
            __DIR__.'/../config/ip-api.php', 'ip-api'
        );
    }

    /**
     * Bootstrap any package services.
     */
    public function boot(): void
    {

        $this->registerPublishing();
        $this->registerCommands();
    }

    /**
     * Register the package's publishable resources.
     */
    protected function registerPublishing(): void
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../config/ip-api.php' => config_path('ip-api.php'),
            ], ['ip-api', 'ip-api-config']);
        }
    }

    /**
     * Register the package's commands.
     */
    protected function registerCommands(): void
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                //
            ]);
        }
    }
}
