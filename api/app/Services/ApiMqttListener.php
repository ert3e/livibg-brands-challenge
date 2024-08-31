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

class ApiMqttListener extends BaseApiMqtt
{

    public string $client = 'laravel_mqtt_api_publisher';

    /**
     * @throws ConfigurationInvalidException
     */
    public function __construct()
    {
        parent::__construct();
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
