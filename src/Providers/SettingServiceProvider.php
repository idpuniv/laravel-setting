<?php

namespace Idpuniv\Setting\Providers;

use Illuminate\Support\ServiceProvider;
use Idpuniv\Setting\Services\SettingsService;

class SettingServiceProvider extends ServiceProvider
{
    public function register()
    {

    }

    public function boot()
    {

        $this->loadRoutesFrom(__DIR__ . '/../../routes/web.php');
        $this->loadViewsFrom(__DIR__ . '/../../resources/views', 'settings');
    }
}
