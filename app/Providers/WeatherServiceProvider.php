<?php

namespace App\Providers;

use App\Models\Weather\OpenWeatherModel;
use App\Services\APIs\Weather\OpenWeatherService;
use App\Services\APIs\Weather\WeatherServiceInterface;
use Illuminate\Filesystem\FilesystemManager;
use Illuminate\Support\ServiceProvider;

class WeatherServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(WeatherServiceInterface::class, function ($app) {
            return new OpenWeatherService(
                new OpenWeatherModel(new FilesystemManager($app))
            );
        });
    }
}
