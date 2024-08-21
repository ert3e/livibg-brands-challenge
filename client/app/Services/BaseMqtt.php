<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;
use PhpMqtt\Client\Exceptions\ConfigurationInvalidException;
use PhpMqtt\Client\Exceptions\ProtocolNotSupportedException;
use PhpMqtt\Client\MqttClient as BaseMqttClient;

abstract class BaseMqtt
{
    public BaseMqttClient $mqtt;

    /**
     * @throws ConfigurationInvalidException
     */
    public function __construct($clientId)
    {
        $brokerHost = env('MQTT_BROKER_HOST');
        $brokerPort = env('MQTT_BROKER_PORT');

        if (empty($brokerHost) ||empty($brokerPort)) {
            throw new ConfigurationInvalidException("Configuration is missing");
        }
        try {
            $this->mqtt = new BaseMqttClient($brokerHost, $brokerPort, $clientId);
        } catch (ProtocolNotSupportedException $e) {
            Log::error('MQTT connection failed: ' . $e->getMessage());
            // Handle the exception as needed, possibly rethrow or return a fallback
        }
    }

}
