<?php

namespace App\Services;
use PhpMqtt\Client\Exceptions\ConfigurationInvalidException;
use PhpMqtt\Client\Exceptions\ConnectingToBrokerFailedException;
use PhpMqtt\Client\Exceptions\DataTransferException;
use PhpMqtt\Client\Exceptions\InvalidMessageException;
use PhpMqtt\Client\Exceptions\MqttClientException;
use PhpMqtt\Client\Exceptions\ProtocolNotSupportedException;
use PhpMqtt\Client\Exceptions\ProtocolViolationException;
use PhpMqtt\Client\Exceptions\RepositoryException;
use PhpMqtt\Client\MqttClient as BaseMqttClient;
use PhpMqtt\Client\ConnectionSettings;
use Illuminate\Support\Facades\Redis;

class MqttClient extends BaseMqtt implements MqttClientInterface
{
    public $clientId = 'laravel_mqtt_client';
    public $correlationId;

    /**
     * @throws ConfigurationInvalidException
     */
    public function __construct()
    {
        parent::__construct($this->clientId .  $this->correlationId);
    }

    /**
     * @throws ConnectingToBrokerFailedException
     * @throws ConfigurationInvalidException
     * @throws RepositoryException
     * @throws DataTransferException
     */
    public function searchTvShow(string $query, string $correlationId = null) : array
    {
        $this->mqtt->connect();
        $this->correlationId = !$correlationId ?: uniqid();

        // Publish search query
        $this->mqtt->publish('search/tvshow', json_encode([
            'query' => $query,
            'correlation_id' => $correlationId,
        ]), BaseMqttClient::QOS_AT_MOST_ONCE);

        // Set up a variable to store the response
        $responseMessage = null;
        // Subscribe to the response topic
        $this->mqtt->subscribe('response/tvshow', function (string $topic, string $message) use ($correlationId, &$responseMessage) {
            $response = json_decode($message, true);
            if (isset($response['correlation_id']) && $response['correlation_id'] === $correlationId) {
                $responseMessage = $response;
                if ($this->mqtt->isConnected()){
                    $this->mqtt->disconnect();
                }
            }
        }, BaseMqttClient::QOS_AT_MOST_ONCE);

        try {
            $this->mqtt->loop();
        } catch (DataTransferException|MqttClientException $e) {

        }
        if($this->mqtt->isConnected()) {
            $this->mqtt->disconnect();
        }
        return $responseMessage;
    }

    /**
     * @throws ConnectingToBrokerFailedException
     * @throws MqttClientException
     * @throws RepositoryException
     * @throws ConfigurationInvalidException
     * @throws ProtocolViolationException
     * @throws InvalidMessageException
     * @throws DataTransferException
     */
    public function subscribeToResponses(): void
    {
        $this->mqtt->connect();

        $this->mqtt->subscribe('response/tvshow', function (string $topic, string $message) {
            $response = json_decode($message, true);

            if (isset($response['correlation_id'])) {
                Redis::set('response_' . $response['correlation_id'], json_encode($response));
                Redis::expire('response_' . $response['correlation_id'], 600); // Expire in 10 minutes
            }
        }, BaseMqttClient::QOS_AT_MOST_ONCE);

        $this->mqtt->loop(true); // Continuous listening
    }
}
