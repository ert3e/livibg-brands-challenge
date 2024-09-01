<?php

namespace App\Providers;

use App\Services\Mqtt\ApiMqttListener;
use App\Services\Mqtt\ApiMqttListenerInterface;
use App\Services\Mqtt\ApiMqttPublisher;
use App\Services\Mqtt\ApiMqttPublisherInterface;
use App\Services\TvShowService;
use App\Services\TvShowServiceInterface;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(TvShowServiceInterface::class, TvShowService::class);
        $this->app->bind(ApiMqttListenerInterface::class, ApiMqttListener::class);
        $this->app->bind(ApiMqttPublisherInterface::class, ApiMqttPublisher::class, );
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
