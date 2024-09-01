<?php

namespace App\Actions;

use App\Enums\ApiActionUrl;
use App\Enums\ApiActionUrlParameters;
use Illuminate\Support\Facades\Http;
use function Symfony\Component\Translation\t;

class FetchTvMazeShowAction
{
    /**
     * @throws \Exception
     */
    public function execute(string $query)
    {
        // Make the API request to search for the TV show
        try {
            $response = Http::get(ApiActionUrl::SEARCH_SHOWS->value, [
                ApiActionUrlParameters::SEARCH_SHOWS_PARAMETER->value => $query
            ]);
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }

        // Handle the response
        if ($response->successful()) {
            return $response->json();
        }

        // Handle any errors
        throw new \Exception('Failed to fetch data from TVMaze API.');
    }
}
