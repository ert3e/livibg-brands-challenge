<?php

namespace App\DTO;

use Spatie\LaravelData\Data;

class ShowDTO extends Data
{
    public int $id;
    public string $name;
    public string $url;
    public string $type;
    public string $language;
    public array $genres;
    public string $status;
    public ?int $runtime;
    public ?int $averageRuntime;
    public ?string $premiered;
    public ?string $ended;
    public ?string $officialSite;
    public array $schedule;
    public array $rating;
    public int $weight;
    public ?array $network;
    public ?array $webChannel;
    public ?array $externals;
    public ?array $image;
    public string $summary;
    public ?array $links;
}
