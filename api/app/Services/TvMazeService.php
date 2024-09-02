<?php

namespace App\Services;

use App\Actions\FetchTvMazeSearchShowAction;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;

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
        Log::info($results);
        $array = $this->filterUniqShowByRequest($results, $request);
        Log::info($array);
        return $array;
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
