<?php
namespace App\Repos;
use App\Repos\Repository;
use App\Models\Hc;
use App\Components\DataFilters\HcFilter;

class HcRepo extends Repository{
    public function __construct(Hc $model,HcFilter $filter){
        parent::__construct($model,$filter);
    }

    public function getAllBanners(){
        return $this->model->where('section','banner')->get();
    }
}