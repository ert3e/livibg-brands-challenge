<?php


namespace App\Services;

use Illuminate\Support\Facades\Log;
use PhpMqtt\Client\Exceptions\ConfigurationInvalidException;
use PhpMqtt\Client\Exceptions\ConnectingToBrokerFailedException;
use PhpMqtt\Client\Exceptions\ProtocolNotSupportedException;
use PhpMqtt\Client\MqttClient;

class BaseApiMqtt
{
    protected string $clientId = '';
    protected MqttClient $mqtt;

    function __construct() {
        try {
            $brokerHost = env('MQTT_BROKER_HOST');
            $brokerPort = env('MQTT_BROKER_PORT');

           if (empty($brokerHost) ||empty($brokerPort)) {
               throw new ConfigurationInvalidException("Configuration is missing");
           }

           $this->mqtt = new MqttClient($brokerHost, $brokerPort, $this->clientId);
           $this->mqtt->connect();
       } catch (ProtocolNotSupportedException | ConfigurationInvalidException | ConnectingToBrokerFailedException $e) {
           Log::error('MQTT connection failed: ' . $e->getMessage());
           // Handle the exception as needed, possibly rethrow or return a fallback
       }
   }
}
