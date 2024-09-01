<?php

namespace App\Actions;

use App\DTO\TvShowDTO;
use App\Enums\ApiActionUrl;
use App\Enums\ApiActionUrlParameters;
use Illuminate\Support\Facades\Http;

class FetchTvMazeSearchShowAction
{
    public string $url = ApiActionUrl::SEARCH_SHOWS->value;
    public string $parameter = ApiActionUrlParameters::SEARCH_SHOWS_PARAMETER->value;
    /**
     * @throws \Exception
     */
    public function execute(string $query)
    {
        // Make the API request to search for the TV show
        try {
            $response = Http::get($this->url, [
                $this->parameter => $query
            ]);
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }

        // Handle the response
        if ($response->successful()) {
            return collect($response->json())->map(fn($item) => TvShowDTO::from($item));
        }

        // Handle any errors
        throw new \Exception('Failed to fetch data from TVMaze API.');
    }
}
