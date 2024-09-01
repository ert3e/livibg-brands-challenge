<?php

namespace App\Services;

use App\Actions\FetchTvMazeSearchShowAction;
use App\Services\Mqtt\ApiMqttPublisher;
use Illuminate\Support\Collection;

class TvMazeService implements TvShowServiceInterface
{
    private FetchTvMazeSearchShowAction $fetchTvMazeShowAction;

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

        return $this->filterUniqShowByRequest($results, $request);
    }

    public function filterUniqShowByRequest(Collection $results, string $request): array
    {
        return collect($results)
            ->pluck('show')
            ->filter(function ($show) use ($request) {
                return strcasecmp($show->name, $request) === 0;
            })
            ->values()->toArray();
    }
}
