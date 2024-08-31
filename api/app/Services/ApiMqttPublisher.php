<?php

namespace App\Services;

use PhpMqtt\Client\Exceptions\ConfigurationInvalidException;
use PhpMqtt\Client\Exceptions\ConnectingToBrokerFailedException;
use PhpMqtt\Client\Exceptions\DataTransferException;
use PhpMqtt\Client\Exceptions\ProtocolNotSupportedException;
use PhpMqtt\Client\Exceptions\RepositoryException;
use PhpMqtt\Client\MqttClient;
use Illuminate\Support\Facades\Log;

class ApiMqttPublisher extends BaseApiMqtt
{
    public string $client = 'laravel_mqtt_api_publisher';

    /**
     */
    public function __construct()
    {
        $correlationId = uniqid();
        $this->clientId = $this->client . $correlationId;
        parent::__construct();

    }

    /**
     * Publishes a message to a given MQTT topic.
     *
     * @param string $topic
     * @param string $message
     * @throws DataTransferException
     */
    public function publish(string $topic, string $message)
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
