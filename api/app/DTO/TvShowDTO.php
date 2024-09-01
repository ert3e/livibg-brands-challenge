<?php

namespace App\DTO;

use Spatie\LaravelData\Data;

class TvShowDTO extends Data
{
    public int $score;
    public ShowDTO $show;
}
