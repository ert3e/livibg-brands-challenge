<?php

namespace App\Services;

use App\Actions\FetchTvMazeSearchShowAction;
use App\Services\Mqtt\ApiMqttPublisher;

class TvShowService implements TvShowServiceInterface
{
    private FetchTvMazeSearchShowAction $fetchTvMazeShowAction;
    private ApiMqttPublisher $apiMqttPublisher;

    public function __construct(FetchTvMazeSearchShowAction $fetchTvMazeShowAction)
    {
        $this->fetchTvMazeShowAction = $fetchTvMazeShowAction;
    }

    /**
     * @throws \Exception
     */
    public function searchShows(string $request): array
    {
        $results = $this->fetchTvMazeShowAction->execute($request);

        return collect($results)
            ->pluck('show')
            ->filter(function ($show) use ($request) {
                return strcasecmp($show['name'], $request) === 0;
            })
            ->values();
    }
}
