<?php

namespace App\Enums;

enum ApiActionUrl: string
{
    case SEARCH_SHOWS = 'https://api.tvmaze.com/search/shows';
    case SHOW_DETAILS = 'https://api.tvmaze.com/shows/';
}
