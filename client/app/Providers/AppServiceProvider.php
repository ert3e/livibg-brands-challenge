<?php

namespace App\Providers;

use App\Repositories\RedisSearchRepository;
use App\Repositories\SearchRepositoryInterface;
use App\Services\MqttClient;
use App\Services\MqttClientInterface;
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
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
