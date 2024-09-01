<?php

namespace App\Services\Mqtt;

interface ApiMqttPublisherInterface
{
    public function publish(string $topic, string $message);
}
