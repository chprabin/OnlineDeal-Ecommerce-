<?php
namespace App\Repos;
use App\Repos\Repository;
use App\Models\Country;

class CountryRepo extends Repository{
    public function __construct(Country $model){
        parent::__construct($model);
    }
}