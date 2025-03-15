<?php

namespace Toolkito\Larasap;

use Illuminate\Support\ServiceProvider;
use Illuminate\Contracts\Support\DeferrableProvider;

class LarasapServiceProvider extends ServiceProvider implements DeferrableProvider
{
    /**
     * All of the container bindings that should be registered.
     *
     * @var array<class-string, class-string>
     */
    public array $bindings = [
        'larasap' => SendTo::class,
    ];

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

        // Register the main class to use with the facade
        $this->app->singleton('larasap', function () {
            return new SendTo();
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        if ($this->app->runningInConsole()) {
            // Publish package's configuration file
            $this->publishes([
                __DIR__ . '/../config/config.php' => config_path('larasap.php'),
            ], ['larasap-config', 'larasap']);

            // Publish package's views
            $this->publishes([
                __DIR__ . '/../resources/views' => resource_path('views/vendor/larasap'),
            ], ['larasap-views', 'larasap']);
        }
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
