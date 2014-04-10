<?php

use Mockery as m;
// $response = $this->call($method, $uri, $parameters, $files, $server, $content);

class CityControllerTest extends TestCase {

    public function tearDown()
    {
        m::close();
    }

    public function testCitiesByStateReturnsErrorIfInvalidState()
    {
        $mockQueryRepo = m::mock('CityQueryRepository');
        $mockQueryRepo->shouldReceive('getCitiesByState')
            ->once()
            ->with('ST', 2)
            ->andReturn(['count' => 0, 'results' => []]);

        $this->app->instance('ICityQueryRepository', $mockQueryRepo);

        $response = $this->call('GET', '/v1/states/ST/cities.json?page=2');
        $this->assertEquals('{"error":"State not found."}', $response->getContent());
    }

    public function testCitiesByStateReturnsSuccessIfValidState()
    {
        $mockQueryRepo = m::mock('CityQueryRepository');
        $mockQueryRepo->shouldReceive('getCitiesByState')
            ->once()
            ->with('ST', 2)
            ->andReturn(['count' => 1, 'results' => ['something']]);

        $this->app->instance('ICityQueryRepository', $mockQueryRepo);

        $response = $this->call('GET', '/v1/states/ST/cities.json?page=2');
        $this->assertEquals('{"count":1,"results":["something"]}', $response->getContent());
    }

    public function testCitiesByCityAndRadiusReturnsErrorIfInvalidCityState()
    {
        $mockQueryRepo = m::mock('CityQueryRepository');
        $mockQueryRepo->shouldReceive('getCityByStateAndCity')
            ->once()
            ->with('ST', 'CITY')
            ->andReturn(false);

        $this->app->instance('ICityQueryRepository', $mockQueryRepo);

        $response = $this->call('GET', '/v1/states/ST/cities/CITY.json', ['radius' => '100']);
        $this->assertEquals('{"error":"Invalid city or state."}', $response->getContent());
    }

    public function testCitiesByCityAndRadiusReturnsSuccessIfValidCityState()
    {
        $mockQueryRepo = m::mock('CityQueryRepository');
        $mockQueryRepo->shouldReceive('getCityByStateAndCity')
            ->once()
            ->with('ST', 'CITY')
            ->andReturn([]);
        $mockQueryRepo->shouldReceive('getCitiesByCityAndRadius')
            ->once()
            ->with([], '100', '1')
            ->andReturn(['count' => 1, 'results' => ['name' => 'cityname']]);

        $this->app->instance('ICityQueryRepository', $mockQueryRepo);

        $response = $this->call('GET', '/v1/states/ST/cities/CITY.json', ['radius' => '100']);
        $this->assertEquals('{"count":1,"results":{"name":"cityname"}}', $response->getContent());
    }

}