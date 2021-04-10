<?php
namespace App\Repos;
use App\Repos\Repository;
use App\Models\Option;
use App\Components\DataFilters\OptionFilter;

class OptionRepo extends Repository{
    public function __construct(Option $model, OptionFilter $filter){
        parent::__construct($model, $filter);
    }
}