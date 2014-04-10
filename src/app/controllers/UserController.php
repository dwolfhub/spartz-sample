<?php


class UserController extends BaseController {

    /**
     * User Query Repository
     * @var IUserQueryRepository
     */
    protected $queryRepo;

    public function __construct(IUserQueryRepository $queryRepo)
    {
        $this->queryRepo = $queryRepo;
    }

    /**
     * Create user visit
     * @param  String $userId
     * @return Mixed
     */
    public function createUserVisit($userId)
    {
        // validate input
        $validationRules = [
            'city' => 'required',
            'state' => 'required|alpha|size:2'
        ];
        $validator = Validator::make(Input::all(), $validationRules);

        if ($validator->fails())
        {
            // return validation errors
            return Response::json([
                'error' => 'Invalid input.',
                'messages' => $validator->messages()->all()
            ], 400);
        }

        // gather input
        $city = Input::get('city');
        $state = Input::get('state');

        // validate city and state
        $city = $this->queryRepo->getCityByStateAndCity($state, $city);
        if ($city === false) {
            return Response::json([
                'error' => 'Invalid city or state.'
            ], 404);
        }

        // try to insert visit
        $visit = $this->queryRepo->addUserVisit($userId, $city);
        if ($visit === false) {
            // return insert error
            return Response::json([
                'error' => 'Database error, please try again.'
            ], 500);
        }

        // clear cache value for user visits
        Cache::forget('user_visits_by_user_id-' . $userId);

        return 'ok';
    }

    /**
     * Get user visits by user id
     * @param  String $userId
     * @return Mixed
     */
    public function getUserVisitsByUserId($userId)
    {
        // key to store/access in the cache
        $cacheKey = 'user_visits_by_user_id-' . $userId;

        // attempt to retrieve the result from the cache
        if (Cache::has($cacheKey)) {
            return Cache::get($cacheKey);
        }

        // check if user is valid
        $user = $this->queryRepo->getUser($userId);
        if ($user === false) {
            return Response::json([
                'error' => 'Invalid user ID.'
            ], 400);
        }

        // get visits
        $visits = $this->queryRepo->getUserVisits($user['id']);

        // update cache
        Cache::forever($cacheKey, $visits);

        return $visits;
    }
}