<?php

namespace MKD\FastAPI;

use App\Http\Controllers\OTPController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use MKD\FastAPI\Attributes\FastAPI;
use MKD\FastAPI\Attributes\FastAPIGroup;
use MKD\FastAPI\commands\FastAPICacheCommand;
use MKD\FastAPI\commands\FastAPIClearCacheCommand;
use Mkd\LaravelAdvancedSubscription\LaravelAdvancedSubscription;

class LaravelFastAPIServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     */
    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__ . '/../config/config.php' => config_path('fast-api.php'),
            ], 'config');
        }
        (new FastAPIService())->registerRoutes();


        $this->commands([
            FastAPICacheCommand::class,
            FastAPIClearCacheCommand::class,
        ]);
    }

    public function register()
    {
        // Automatically apply the package configuration
        $this->mergeConfigFrom(__DIR__ . '/../config/config.php', 'fast-api');

    }

}
