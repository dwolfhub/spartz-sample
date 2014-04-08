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
}