<?php
namespace App\Repos;
use App\Repos\Repository;
use App\Models\OrderItem;
use App\Components\DataFilters\OrderItemFilter;

class OrderItemRepo extends Repository{
    public function __construct(OrderItem $model, OrderItemFilter $filter){
        parent::__construct($model, $filter);
    }
}