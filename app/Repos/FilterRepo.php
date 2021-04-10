<?php
namespace App\Repos;
use App\Repos\Repository;
use App\Models\Filter;
use App\Components\DataFilters\FilterFilter;

class FilterRepo extends Repository{
    public function __construct(Filter $model, FilterFilter $filter){
        parent::__construct($model, $filter);
    }

    public function insert(array $data){
        $model=parent::insert($data);
        if(is_array(json_decode($data['options']))){
            $model->addOptions(json_decode($data['options']));
        }
        return $model;
    }

    public function update($model, $data){
        $result=parent::update($model, $data);
        if(is_array(json_decode($data['options']))){
            $model->addOptions(json_decode($data['options']));
        }
        return $result;
    }
}