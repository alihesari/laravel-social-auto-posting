<?php

namespace Toolkito\Larasap;

use Illuminate\Support\ServiceProvider;

class LarasapServiceProvider extends ServiceProvider
{
    public function boot()
    {
        // Publish package's configuration file to the application
        $this->publishes([
            __DIR__ . '/../config/config.php' => config_path('larasap.php')
        ], 'larasap');
    }

    public function register()
    {
        // Default Package Configuration
        $this->mergeConfigFrom(__DIR__ . '/../config/config.php', 'larasap');
    }

    public function provides()
    {
        return ['larasap'];
    }
}
