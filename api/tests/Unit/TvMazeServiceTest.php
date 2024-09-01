<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Services\TvMazeService;
use App\Actions\FetchTvMazeSearchShowAction;
use App\DataTransferObjects\ShowDTO;
use Mockery;
use Illuminate\Support\Collection;

class TvMazeServiceTest extends TestCase
{
    public function testSearchShowsReturnsFilteredResults()
    {
        // Mock the FetchTvMazeSearchShowAction
        $fetchTvMazeShowAction = Mockery::mock(FetchTvMazeSearchShowAction::class);
        $fetchTvMazeShowAction->shouldReceive('execute')
            ->once()
            ->with('Girls')
            ->andReturn($this->getSampleApiResponse());

        // Instantiate the service with the mocked action
        $tvMazeService = new TvMazeService($fetchTvMazeShowAction);

        // Call the searchShows method
        $results = $tvMazeService->searchShows('Girls');

        // Assert the filtered results
        $this->assertCount(1, $results);
        $this->assertEquals('Girls', $results[0]->name);
    }

    public function testFilterUniqShowByRequest()
    {
        // Instantiate the service (we don't need to mock the action for this test)
        $tvMazeService = new TvMazeService(Mockery::mock(FetchTvMazeSearchShowAction::class));

        // Use the method directly with a sample response
        $results = new Collection($this->getSampleApiResponse());

        $filteredResults = $tvMazeService->filterUniqShowByRequest($results, 'Girls');

        // Assert the filtered results
        $this->assertCount(1, $filteredResults);
        $this->assertEquals('Girls', $filteredResults[0]->name);
    }

    private function getSampleApiResponse()
    {
        return [
            [
                'score' => 0.9081737,
                'show' => new ShowDTO([
                    'id' => 139,
                    'name' => 'Girls',
                    'url' => 'https://www.tvmaze.com/shows/139/girls',
                    'type' => 'Scripted',
                    'language' => 'English',
                    'genres' => ['Drama', 'Romance'],
                    'status' => 'Ended',
                    'runtime' => 30,
                    'averageRuntime' => 30,
                    'premiered' => '2012-04-15',
                    'ended' => '2017-04-16',
                    'officialSite' => 'http://www.hbo.com/girls',
                    'schedule' => ['time' => '22:00', 'days' => ['Sunday']],
                    'rating' => ['average' => 6.4],
                    'weight' => 96,
                    'network' => [
                        'id' => 8,
                        'name' => 'HBO',
                        'country' => ['name' => 'United States', 'code' => 'US', 'timezone' => 'America/New_York'],
                        'officialSite' => 'https://www.hbo.com/'
                    ],
                    'webChannel' => null,
                    'externals' => ['tvrage' => 30124, 'thetvdb' => 220411, 'imdb' => 'tt1723816'],
                    'image' => [
                        'medium' => 'https://static.tvmaze.com/uploads/images/medium_portrait/31/78286.jpg',
                        'original' => 'https://static.tvmaze.com/uploads/images/original_untouched/31/78286.jpg'
                    ],
                    'summary' => '<p>This Emmy-winning series is a comic look at the assorted humiliations and rare triumphs of a group of girls in their 20s.</p>',
                    '_links' => [
                        'self' => ['href' => 'https://api.tvmaze.com/shows/139'],
                        'previousepisode' => ['href' => 'https://api.tvmaze.com/episodes/1079686', 'name' => 'Latching']
                    ]
                ])
            ]
        ];
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }
}
