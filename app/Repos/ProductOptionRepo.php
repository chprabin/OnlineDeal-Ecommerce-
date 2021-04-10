<?php
namespace App\Repos;
use App\Repos\Repository;
use App\Models\ProductOption;

class ProductOptionRepo extends Repository{

    public function __construct(ProductOption $model){
        parent::__construct($model);
    }
}