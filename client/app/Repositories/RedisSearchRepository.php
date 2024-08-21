<?php

namespace App\Repositories;

use Illuminate\Support\Facades\Redis;

class RedisSearchRepository implements SearchRepositoryInterface
{
    public function find(string $query): ?array
    {
        $cachedResults = Redis::get('tvshow_search_' . $query);

        return $cachedResults ? json_decode($cachedResults, true) : null;
    }

    public function save(string $query, array $results): void
    {
        Redis::set('tvshow_search_' . $query, json_encode($results), 'EX', 600); // Cache for 10 minutes
    }
}
