<?php

namespace App\Services\Mqtt;

use Illuminate\Support\Facades\Log;
use PhpMqtt\Client\Exceptions\DataTransferException;
use PhpMqtt\Client\Exceptions\RepositoryException;
use PhpMqtt\Client\MqttClient;

class ApiMqttPublisher extends BaseApiMqtt implements ApiMqttPublisherInterface
{
    public string $clientId = 'laravel_mqtt_api_publisher';
    /**
     */
    public function __construct()
    {
        $this->createCorrelationClientId();
        parent::__construct();
    }

    /**
     * Publishes a message to a given MQTT topic.
     *
     * @param string $topic
     * @param string $message
     * @throws DataTransferException
     */
    public function publish(string $topic, string $message): void
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
    public function createCorrelationClientId(): void
    {
        $this->clientId = $this->clientId . uniqid();
    }
}
