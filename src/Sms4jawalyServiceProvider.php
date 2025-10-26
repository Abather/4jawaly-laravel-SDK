<?php

namespace Sms4jawaly\Laravel;

use Illuminate\Support\ServiceProvider;

/**
 * Service provider registers the Gateway class with the Laravel container.
 *
 * Once registered, you can type-hint the Gateway in your controllers or other
 * services and it will automatically be resolved using your configuration
 * values defined in `config/services.php` under the `sms4jawaly` key.
 */
class Sms4jawalyServiceProvider extends ServiceProvider
{
    /**
     * Register services with the container.
     *
     * @return void
     */
    public function register(): void
    {
        $this->app->singleton(Gateway::class, function ($app) {
            // Retrieve configuration from the services configuration file
            $config = $app['config']['sms-4-jawaly'] ?? null;

            if (!$config || empty($config['api_key']) || empty($config['api_secret'])) {
                throw new \InvalidArgumentException('Sms4jawaly configuration not set. Please add sms4jawaly credentials to config/services.php');
            }

            return new Gateway(
                $config['api_key'],
                $config['api_secret']
            );
        });
    }

    /**
     * Bootstrap services.
     *
     * You may publish package assets or configuration here. For this simple
     * package there is nothing to publish at boot time.
     *
     * @return void
     */
    public function boot(): void
    {
        $this->publishes([

            __DIR__.'/../config/sms-4-jawaly.php' => config_path('sms-4-jawaly.php'),

        ]);

        // Nothing to boot
    }
}