<?php

namespace App\Services;

interface MqttClientInterface
{
    public function searchTvShow(string $query, string $correlationId);
}
