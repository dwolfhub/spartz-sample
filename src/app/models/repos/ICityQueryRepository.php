<?php

interface ICityQueryRepository
{
    public function getCitiesByState($state, $page);
    public function getCityByStateAndCity($state, $city);
    public function getCitiesByCityAndRadius($city, $radius, $page);
}