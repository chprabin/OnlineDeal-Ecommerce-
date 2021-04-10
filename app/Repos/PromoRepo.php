<?php
namespace App\Repos;
use App\Repos\Repository;
use App\Models\Promo;
use App\Components\DataFilters\PromoFilter;

class PromoRepo extends Repository{
    public function __construct(Promo $model, PromoFilter $filter){
        parent::__construct($model, $filter);
    }

    public function insert(array $data){
        $model=parent::insert($data);
        if(is_array(json_decode($data['products']))){
            $model->addProducts(json_decode($data['products']));
        }
        return $model;
    }
}