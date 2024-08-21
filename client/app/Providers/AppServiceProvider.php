<?php

namespace App\Providers;

use App\Repositories\RedisSearchRepository;
use App\Repositories\SearchRepositoryInterface;
use App\Services\MqttClient;
use App\Services\MqttClientInterface;
use App\Services\MqttListener;
use App\Services\MqttListenInterface;
use App\Services\MqttPublisher;
use App\Services\MqttPublisherInterface;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(SearchRepositoryInterface::class, RedisSearchRepository::class);
        $this->app->bind(MqttClientInterface::class, MqttClient::class);
        $this->app->bind(MqttPublisherInterface::class, MqttPublisher::class);
        $this->app->bind(MqttListenInterface::class, MqttListener::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
