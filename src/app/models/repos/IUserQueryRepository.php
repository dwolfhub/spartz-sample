<?php

interface IUserQueryRepository
{
    public function getCityByStateAndCity($state, $city);
    public function addUserVisit($userId, $city);
}