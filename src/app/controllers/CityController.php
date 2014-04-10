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
     * @return Array
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
        $cities = $this->queryRepo->getCitiesByState($state, $page);
        if ($cities['results'] === []) {
            return Response::json([
                'error' => 'State not found.'
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
        if ($city === false) {
            return Response::json([
                'error' => 'Invalid city or state.'
            ], 404);
        }

        // get cities by city and radius
        $cities = $this->queryRepo->getCitiesByCityAndRadius($city, $radius, $page);

        // cache value for 2 hours and return data
        Cache::put($cacheKey, $cities, 120);

        return $cities;
    }

}