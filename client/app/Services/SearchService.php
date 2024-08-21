<?php

namespace App\Services;

use App\Repositories\SearchRepositoryInterface;


class SearchService
{
    protected SearchRepositoryInterface $searchRepository;
    protected MqttClientInterface $mqttClient;

    public function __construct(SearchRepositoryInterface $searchRepository, MqttClientInterface $mqttClient)
    {
        $this->searchRepository = $searchRepository;
        $this->mqttClient = $mqttClient;
    }

    public function search(string $query): array
    {
        // Check Redis cache
        $cachedResults = $this->searchRepository->find($query);

        if ($cachedResults) {
            return $cachedResults;
        }

        // If not found in cache, send request to MQTT
        $correlationId = uniqid();
        $response = $this->mqttClient->searchTvShow($query, $correlationId);

        if ($response) {
            $results = is_array($response) || is_string($response) ? $response : json_decode($response, true);
            $this->searchRepository->save($query, $results);

            return $results;
        } else {
            return ['error' => 'No response received from search service'];
        }
    }
}
