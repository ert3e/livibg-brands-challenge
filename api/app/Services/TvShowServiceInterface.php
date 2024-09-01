<?php

namespace App\Services;

interface TvShowServiceInterface
{
    public function searchShows(string $request): array;
}
