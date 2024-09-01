<?php

namespace App\Services;

use App\Services\Mqtt\ApiMqttPublisher;
use PhpMqtt\Client\Exceptions\DataTransferException;

class ApiService implements ApiServiceInterface
{
    private ApiMqttPublisher $apiMqttPublisher;
    private TvMazeService $tvMazeService;

    public function __construct(TvMazeService $tvMazeService, ApiMqttPublisher $apiMqttPublisher)
    {
        $this->apiMqttPublisher = $apiMqttPublisher;
        $this->tvMazeService = $tvMazeService;
    }

    /**
     * @throws DataTransferException
     * @throws \Exception
     */
    public function execute(array $params): void
    {
        $searchQuery = $params['query'];
        $correlationId = $params['correlation_id'];

        $results = $this->tvMazeService->searchShows($searchQuery);

        // Publish the response back to the MQTT topic
        $this->apiMqttPublisher->publish('response/tvshow', json_encode([
                'correlation_id' => $correlationId,
                'results' => $results
            ])
        );
    }
}
