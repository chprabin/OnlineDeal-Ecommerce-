<?php
namespace App\Repos;
use App\Repos\Repository;
use App\Models\ProductCategory;

class ProductCategoryRepo extends Repository{
    public function __construct(ProductCategory $model){
        parent::__construct($model);
    }
}