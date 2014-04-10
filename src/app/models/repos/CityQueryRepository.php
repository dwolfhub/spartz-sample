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

        $query = City::where('state', 'LIKE', $state);

        return [
            'count' => $query->count(),
            'results' => $query->take($limit)->skip($offset)->get()->toArray(),
        ];
    }

    /**
     * Get city by state and city
     * @param  String $state
     * @param  String $city
     * @return stdObject
     */
    public function getCityByStateAndCity($state, $city)
    {
        $city = City::where('state', $state)
            ->where('name', $city)
            ->first();

        // return false if city / state doesn't exist
        if (!$city) {
            return false;
        } else {
            return $city->toArray();
        }
    }

    /**
     * Get cities by city and radius
     * @param  String $city
     * @param  String $radius (in miles)
     * @return Array
     */
    public function getCitiesByCityAndRadius($city, $radius, $page)
    {
        $limit = 500;

        return [
            'count' => DB::SELECT(
                'SELECT COUNT(0) count FROM (
                    SELECT round( sqrt( (POW( `cities`.`latitude` - ' . $city['latitude'] . ', 2)* 68.1 * 68.1)
                        + (POW( `cities`.`longitude` - ' . $city['longitude'] . ', 2) * 53.1 * 53.1) ) ) AS distance
                    FROM cities
                    WHERE id <> ' . $city['id'] . '
                    HAVING distance < ' . $radius . '
                ) a;'
            )[0]->count,

            'results' => DB::table('cities')
                ->select(DB::raw(
                    '`cities`.*, round( sqrt( (POW( `cities`.`latitude` - ' . $city['latitude'] . ', 2)* 68.1 * 68.1)' .
                    ' + (POW( `cities`.`longitude` - ' . $city['longitude'] . ', 2) * 53.1 * 53.1) ) ) AS distance'
                ))
                ->where('id', '<>', $city['id'])
                ->having('distance', '<', $radius)
                ->orderBy('distance')
                ->take($limit)
                ->skip(($page - 1) * $limit)
                ->get(),
        ];
    }

}