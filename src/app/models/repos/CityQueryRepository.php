<?php

/**
* City Query Repository
*/
class CityQueryRepository implements ICityQueryRepository
{

    /**
     * Get cities by state, page
     * @param  String  $state
     * @param  Integer $page
     * @return Array
     */
    public function getCitiesByState($state, $page)
    {
        $limit = 500;
        $offset = ($page - 1) * $limit;

        return City::where('state', $state)
            ->take($limit)
            ->skip($offset)
            ->get()
            ->toArray();
    }

    /**
     * Get city by state and city
     * @param  String $state
     * @param  String $city
     * @return stdObject
     */
    public function getCityByStateAndCity($state, $city)
    {
        return (object) City::where('state', $state)
            ->where('name', $city)
            ->first();
    }

    /**
     * Get cities by city and radius
     * @param  String $city
     * @param  String $radius (in miles)
     * @return Array
     */
    public function getCitiesByCityAndRadius($city, $radius, $city)
    {

    }

}