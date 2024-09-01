<?php

namespace App\Services;

use App\Actions\FetchTvMazeShowAction;

class TvShowService implements TvShowServiceInterface
{
    private FetchTvMazeShowAction $fetchTvMazeShowAction;

    public function __construct(FetchTvMazeShowAction $fetchTvMazeShowAction)
    {
        $this->fetchTvMazeShowAction = $fetchTvMazeShowAction;
    }

    public function searchShows(string $request): array
    {
        return $this->fetchTvMazeShowAction->execute($request);
    }
}
