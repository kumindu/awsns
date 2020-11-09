<?php

namespace kumindu\awsns;


use Illuminate\Support\ServiceProvider;
use Aws\Sdk;
/**
 * Class AwsServiceProvider
 *
 * @package Laravel\PricingPlans
 */
class AwsServiceProvider extends ServiceProvider
{
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = true;

    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            __DIR__ . '/../config/aws.php' => config_path('aws.php')
        ], 'config');
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/aws.php', 'aws');

        $this->app->singleton('aws', function ($app) {
            $config = $app->make('config')->get('aws');

            return new Sdk($config);
        });

        $this->app->alias('aws', Sdk::class);
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return ['aws', Sdk::class];
    }
}
