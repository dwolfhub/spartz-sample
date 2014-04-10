<?php

use Mockery as m;

class UserControllerTest extends TestCase {

    public function tearDown()
    {
        m::close();
    }

    /**
     * @dataProvider createUserVisitReturnsErrorIfInvalidInputProvider
     */
    public function testCreateUserVisitReturnsErrorIfInvalidInput($city, $state, $output)
    {
        $response = $this->call('POST', '/v1/users/1/visits', [
            'city' => $city,
            'state' => $state
        ]);
        $this->assertEquals($output, $response->getContent());
    }

    public function createUserVisitReturnsErrorIfInvalidInputProvider()
    {
        return array(
            array('', 'xx', '{"error":"Invalid input.","messages":["The city field is required."]}'),
            array('x', '', '{"error":"Invalid input.","messages":["The state field is required."]}'),
            array('x', '12', '{"error":"Invalid input.","messages":["The state may only contain letters."]}'),
            array('x', 'x', '{"error":"Invalid input.","messages":["The state must be 2 characters."]}'),
        );
    }

    public function testCreateUserVisitReturnsErrorIfInvalidCityStateGiven()
    {
        $mockQueryRepo = m::mock('UserQueryRepository');
        $mockQueryRepo->shouldReceive('getCityByStateAndCity')
            ->once()
            ->with('ST', 'CITY')
            ->andReturn(false);

        $this->app->instance('IUserQueryRepository', $mockQueryRepo);

        $response = $this->call('POST', '/v1/users/1/visits', [
            'city' => 'CITY',
            'state' => 'ST'
        ]);
        $this->assertEquals('{"error":"Invalid city or state."}', $response->getContent());
    }

    public function testCreateUserVisitReturnsErrorIfDatabaseFailure()
    {
        $mockQueryRepo = m::mock('UserQueryRepository');
        $mockQueryRepo->shouldReceive('getCityByStateAndCity')
            ->once()
            ->with('ST', 'CITY')
            ->andReturn([]);
        $mockQueryRepo->shouldReceive('addUserVisit')
            ->once()
            ->with('1', [])
            ->andReturn(false);

        $this->app->instance('IUserQueryRepository', $mockQueryRepo);

        $response = $this->call('POST', '/v1/users/1/visits', [
            'city' => 'CITY',
            'state' => 'ST'
        ]);
        $this->assertEquals('{"error":"Database error, please try again."}', $response->getContent());
    }

    public function testCreateUserVisitReturnsSuccess()
    {
        $mockQueryRepo = m::mock('UserQueryRepository');
        $mockQueryRepo->shouldReceive('getCityByStateAndCity')
            ->once()
            ->with('ST', 'CITY')
            ->andReturn([]);
        $mockQueryRepo->shouldReceive('addUserVisit')
            ->once()
            ->with('1', [])
            ->andReturn(null);

        $this->app->instance('IUserQueryRepository', $mockQueryRepo);

        $response = $this->call('POST', '/v1/users/1/visits', [
            'city' => 'CITY',
            'state' => 'ST'
        ]);
        $this->assertEquals('ok', $response->getContent());
    }

    public function testGetUserVisitsByUserIdReturnsErrorIfInvalidUser()
    {
        $mockQueryRepo = m::mock('UserQueryRepository');
        $mockQueryRepo->shouldReceive('getUser')
            ->once()
            ->with('1')
            ->andReturn(false);

        $this->app->instance('IUserQueryRepository', $mockQueryRepo);

        $response = $this->call('GET', '/v1/users/1/visits');
        $this->assertEquals('{"error":"Invalid user ID."}', $response->getContent());
    }

    public function testGetUserVisitsByUserIdReturnsSuccess()
    {
        $mockQueryRepo = m::mock('UserQueryRepository');
        $mockQueryRepo->shouldReceive('getUser')
            ->once()
            ->with('1')
            ->andReturn(['id' => '1']);
        $mockQueryRepo->shouldReceive('getUserVisits')
            ->once()
            ->with('1')
            ->andReturn(['results']);

        $this->app->instance('IUserQueryRepository', $mockQueryRepo);

        $response = $this->call('GET', '/v1/users/1/visits');
        $this->assertEquals('["results"]', $response->getContent());
    }

}