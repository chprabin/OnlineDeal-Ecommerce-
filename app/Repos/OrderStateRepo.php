<?php
namespace App\Repos;
use App\Repos\Repository;
use App\Models\OrderState;

class OrderStateRepo extends Repository{
    public function __construct(OrderState $model){
        parent::__construct($model);
    }
}