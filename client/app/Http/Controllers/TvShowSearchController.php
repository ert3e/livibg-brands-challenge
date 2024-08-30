<?php

namespace App\Http\Controllers;

use App\Services\SearchService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use PhpMqtt\Client\ConnectionSettings;
use PhpMqtt\Client\Exceptions\ConfigurationInvalidException;
use PhpMqtt\Client\Exceptions\ConnectingToBrokerFailedException;
use PhpMqtt\Client\Exceptions\DataTransferException;
use PhpMqtt\Client\Exceptions\InvalidMessageException;
use PhpMqtt\Client\Exceptions\MqttClientException;
use PhpMqtt\Client\Exceptions\ProtocolNotSupportedException;
use PhpMqtt\Client\Exceptions\ProtocolViolationException;
use PhpMqtt\Client\Exceptions\RepositoryException;
use PhpMqtt\Client\MqttClient;

class TvShowSearchController
{
    private SearchService $searchService;

    public function __construct(SearchService $searchService)
    {
        $this->searchService = $searchService;
    }

    /**
     */
    public function search(Request $request): JsonResponse
    {
        $searchQuery = $request->input('query');

        if (empty($searchQuery)) {
            return response()->json(['error' => 'No query'], 504);
        }
        $response = $this->searchService->search($searchQuery);
        // Check if we received a response
        if ($response) {
            return response()->json($response);
        } else {
            return response()->json(['error' => 'No response received from search service'], 504);
        }

    }
}
