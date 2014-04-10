<?php

/**
 * IOC Container Bindings
 */
App::bind('ICityQueryRepository', 'CityQueryRepository');
App::bind('IUserQueryRepository', 'UserQueryRepository');

/**
 * Load balancer test route
 */
Route::get('/', function ()
{
    return 'ok';
});

/**
 * List all cities in a state
 */
Route::get('/v1/states/{state}/cities.json', 'CityController@citiesByState');

/**
 * List cities within a variable mile radius of a city
 */
Route::get('/v1/states/{state}/cities/{city}.json', 'CityController@citiesByCityAndRadius');

/**
 * Allow a user to indicate they have visited a particular city.
 */
Route::post('/v1/users/{userId}/visits', 'UserController@createUserVisit');

/**
 * Return a list of cities the user has visited
 */
Route::get('/v1/users/{userId}/visits', 'UserController@getUserVisitsByUserId');
