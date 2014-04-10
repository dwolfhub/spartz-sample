<?php


/**
* User Query Repository
*/
class UserQueryRepository implements IUserQueryRepository
{

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
     * Add a user visit
     * @param String $userId
     * @param String $state
     * @param String $city
     */
    public function addUserVisit($userId, $city)
    {
        try {
            DB::insert('insert into users_cities (city_id, user_id) values (?, ?)', array($city['id'], $userId));
        } catch (Exception $e) {
            return false;
        }
    }

    /**
     * Get user by id
     * @param  String $userId
     * @return Array
     */
    public function getUser($userId)
    {
        return User::find($userId)->toArray();
    }

    /**
     * Get user visits
     * @param  String $userId
     * @return Array
     */
    public function getUserVisits($userId)
    {
        return DB::table('users_cities')
            ->distinct()
            ->select(['cities.name', 'cities.state'])
            ->join('cities', 'cities.id', '=', 'users_cities.city_id')
            ->where('users_cities.user_id', '=', $userId)
            ->get();
    }

}