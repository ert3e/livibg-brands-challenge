<?php

namespace Tests\Unit;

use App\Http\Controllers\TvShowSearchController;
use App\Services\SearchService;
use Illuminate\Http\Request;
use PHPUnit\Framework\TestCase;
use Mockery;

class TvShowSearchControllerTest extends TestCase
{
    public function testSearchReturnsErrorWhenQueryIsEmpty()
    {
        $searchService = Mockery::mock(SearchService::class);
        $controller = new TvShowSearchController($searchService);

        $request = Request::create('/search', 'GET', ['query' => '']);
        $response = $controller->search($request);

        $this->assertEquals(504, $response->getStatusCode());
        $this->assertEquals(['error' => 'No query'], $response->getData(true));
    }

    public function testSearchReturnsErrorWhenNoResponse()
    {
        $searchService = Mockery::mock(SearchService::class);
        $searchService->shouldReceive('search')->andReturn(null);
        $controller = new TvShowSearchController($searchService);

        $request = Request::create('/search', 'GET', ['query' => 'example']);
        $response = $controller->search($request);

        $this->assertEquals(504, $response->getStatusCode());
        $this->assertEquals(['error' => 'No response received from search service'], $response->getData(true));
    }

    public function testSearchReturnsValidResponse()
    {
        $expectedResponse = ['show' => 'Example Show'];
        $searchService = Mockery::mock(SearchService::class);
        $searchService->shouldReceive('search')->andReturn($expectedResponse);
        $controller = new TvShowSearchController($searchService);

        $request = Request::create('/search', 'GET', ['query' => 'example']);
        $response = $controller->search($request);

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals($expectedResponse, $response->getData(true));
    }

    protected function tearDown(): void
    {
        Mockery::close();
    }
}
