<?php

namespace App\Providers;

use App\Models\OpenWeatherModel;
use App\Services\Weather\OpenWeather;
use App\Services\Weather\WeatherServiceInterface;
use Illuminate\Filesystem\FilesystemManager;
use Illuminate\Support\Facades\Storage;
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
            return new OpenWeather(
                new OpenWeatherModel(new FilesystemManager($app))
            );
        });
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
