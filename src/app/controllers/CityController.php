<?php

class CityController extends BaseController {

    /**
     * City Query Repository
     * @var ICityQueryRepository
     */
    protected $queryRepo;

    public function __construct(ICityQueryRepository $queryRepo)
    {
        $this->queryRepo = $queryRepo;
    }

    /**
     * Given a state, retrieve a list of cities
     * @param  String $state
     * @return Mixed
     */
    public function citiesByState($state)
    {
        // get page number, default to page 1
        $page = (int) Input::get('page', '1');

        // key to store/access in the cache
        $cacheKey = 'cities_by_state-' . $state . '-' . $page;

        // attempt to retrieve the result from the cache
        if (Cache::has($cacheKey)) {
            return Cache::get($cacheKey);
        }

        // get cities by state
        $cities = $this->queryRepo->getCitiesByState($state);
        if ($cities === []) {
            return Response::make([
                'error' => 'Invalid State.'
            ], 404);
        }

        // cache value for 2 hours and return data
        Cache::put($cacheKey, $cities, 120);
        return $cities;
    }

    /**
     * Get list of cities by city and radius
     * @param  String $state
     * @param  String $city
     * @return Mixed
     */
    public function citiesByCityAndRadius($state, $city)
    {
        // get provided radius in miles, default to 100
        $radius = Input::get('radius', '100');

        // get page number, default to 1
        $page = (int) Input::get('page', '1');

        // key to store/access in the cache
        $cacheKey = 'cities_by_city_and_radius-' . $state . '-' . $city . '-' . $radius . '-' . $page;

        // attempt to retrieve the result from the cache
        if (Cache::has($cacheKey)) {
            return Cache::get($cacheKey);
        }

        // validate city and state
        $city = $this->queryRepo->getCityByStateAndCity($state, $city);
        if (!$city) {
            return Response::make([
                'error' => 'Invalid City.'
            ], 404);
        }

        // get cities by city and radius
        $cities = $this->queryRepo->getCitiesByCityAndRadius($city, $radius);

        // cache value for 2 hours and return data
        Cache::put($cacheKey, $cities, 120);
        return $cities;
    }

}