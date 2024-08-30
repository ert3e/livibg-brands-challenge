<?php

namespace App\Services;

use App\Jobs\ProcessMqttMessage;

use Illuminate\Support\Facades\Log;
use PhpMqtt\Client\Exceptions\ConfigurationInvalidException;
use PhpMqtt\Client\Exceptions\ConnectingToBrokerFailedException;
use PhpMqtt\Client\Exceptions\DataTransferException;
use PhpMqtt\Client\Exceptions\InvalidMessageException;
use PhpMqtt\Client\Exceptions\MqttClientException;
use PhpMqtt\Client\Exceptions\ProtocolNotSupportedException;
use PhpMqtt\Client\Exceptions\ProtocolViolationException;
use PhpMqtt\Client\Exceptions\RepositoryException;
use PhpMqtt\Client\MqttClient;

class ApiMqttListener
{
    protected $mqtt;

    /**
     * @throws ConfigurationInvalidException
     */
    public function __construct()
    {
        $clientId = 'laravel_mqtt_api_listener';
        $brokerHost = env('MQTT_BROKER_HOST');
        $brokerPort = env('MQTT_BROKER_PORT');

        if (empty($brokerHost) ||empty($brokerPort)) {
            throw new ConfigurationInvalidException("Configuration is missing");
        }
        try {
            $this->mqtt = new MqttClient($brokerHost, $brokerPort, $clientId);
            $this->mqtt->connect();
        } catch (ProtocolNotSupportedException $e) {
            Log::error('MQTT connection failed: ' . $e->getMessage());
        } catch (ConfigurationInvalidException $e) {
        } catch (ConnectingToBrokerFailedException $e) {
        }
    }

    /**
     * @throws ProtocolViolationException
     * @throws InvalidMessageException
     * @throws MqttClientException
     * @throws RepositoryException
     * @throws DataTransferException
     */
    public function listen(): void
    {
       // ProcessMqttMessage::dispatch('test', $mqttPublisher);
        $this->mqtt->subscribe('search/tvshow', function (string $topic, string $message)  {
            // Dispatch the job with the received message
            logger()->info($topic . ': ' . $message);
            try {
                // Dispatch the job with the received message
                ProcessMqttMessage::dispatch($message);
                logger()->info($topic . 'w3q: ' . $message);
            } catch (\Exception $e) {
                logger()->error('Error dispatching job: ' . $e->getMessage());
            }
            logger()->info($topic . ': ' . $message);
        }, MqttClient::QOS_AT_MOST_ONCE);

        $this->mqtt->loop(true);
    }

    /**
     * @throws DataTransferException
     */
    public function __destruct()
    {
        $this->mqtt->disconnect();
    }
}
