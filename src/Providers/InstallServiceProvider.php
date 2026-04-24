<?php

namespace Idpuniv\Setting\Providers;

use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Idpuniv\Setting\Console\Commands\InstallCommand;

class InstallServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->mergeConfigFrom(
            __DIR__.'/../../config/settings.php', 
            'settings'  // Nom de la configuration
        );
    }

    public function boot()
    {

        if ($this->app->runningInConsole()) {
            $this->commands([
                InstallCommand::class,
            ]);
        }

        // Permettre à l’app de publier les routes
        // $this->loadRoutesFrom(__DIR__ . '/../../routes/web.php');

        // Charger les vues du package
        View::addLocation(__DIR__ . '/../../resources/views');

        // Permettre à l’app de publier la config (si tu as un settings.php par ex.)
        $this->publishes([
            __DIR__ . '/../../config/settings.php' => config_path('settings.php'),
        ], 'settings-config');

        // Permettre à l’app de publier les migrations (si tu en as)
        $this->publishes([
            __DIR__ . '/../../database/migrations' => database_path('migrations'),
        ], 'settings-migrations');

    }
}
