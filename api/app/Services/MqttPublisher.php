<?php

namespace App\Services;

use PhpMqtt\Client\Exceptions\ConfigurationInvalidException;
use PhpMqtt\Client\Exceptions\ConnectingToBrokerFailedException;
use PhpMqtt\Client\Exceptions\DataTransferException;
use PhpMqtt\Client\Exceptions\ProtocolNotSupportedException;
use PhpMqtt\Client\Exceptions\RepositoryException;
use PhpMqtt\Client\MqttClient;
use Illuminate\Support\Facades\Log;

class MqttPublisher
{

    /**
     */
    public function __construct()
    {
        try {
            $correlationId = uniqid();

            $clientId = 'laravel_mqtt_api_publisher';
            $brokerHost = env('MQTT_BROKER_HOST');
            $brokerPort = env('MQTT_BROKER_PORT');

            $this->mqtt = new MqttClient($brokerHost, $brokerPort, $clientId . $correlationId);
            $this->mqtt->connect();
        } catch (ProtocolNotSupportedException | ConfigurationInvalidException | ConnectingToBrokerFailedException $e) {
            Log::error('MQTT connection failed: ' . $e->getMessage());
            // Handle the exception as needed, possibly rethrow or return a fallback
        }
    }

    /**
     * Publishes a message to a given MQTT topic.
     *
     * @param string $topic
     * @param string $message
     * @throws DataTransferException
     */
    public function publish($topic, string $message)
    {
        try {
            $this->mqtt->publish($topic, $message, MqttClient::QOS_AT_MOST_ONCE);
            Log::info("Published message to topic {$topic}");
        } catch (RepositoryException $e) {
            Log::error('Failed to publish MQTT message: ' . $e->getMessage());
            // Handle the exception as needed
        }
    }

    /**
     * Disconnects from the MQTT broker.
     */
    public function __destruct()
    {
        try {
            $this->mqtt->disconnect();
        } catch (\Exception $e) {
            Log::error('Error disconnecting from MQTT broker: ' . $e->getMessage());
            // Handle the exception as needed
        }
    }
}
