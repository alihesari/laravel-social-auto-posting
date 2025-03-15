<?php

namespace Toolkito\Larasap;

use Illuminate\Support\ServiceProvider;

class LarasapServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Default Package Configuration
        $this->mergeConfigFrom(
            __DIR__ . '/../config/config.php',
            'larasap'
        );
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Publish package's configuration file to the application
        $this->publishes([
            __DIR__ . '/../config/config.php' => config_path('larasap.php'),
        ], 'larasap-config');

        // Publish package's assets
        $this->publishes([
            __DIR__ . '/../resources/views' => resource_path('views/vendor/larasap'),
        ], 'larasap-views');
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array<int, string>
     */
    public function provides(): array
    {
        return ['larasap'];
    }
}
