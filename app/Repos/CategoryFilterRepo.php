<?php
namespace App\Repos;
use App\Repos\Repository;
use App\Models\CategoryFilter;

class CategoryFilterRepo extends Repository{
    public function __construct(CategoryFilter $model){
        parent::__construct($model);
    }
}