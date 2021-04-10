<?php
namespace App\Repos;
use App\Repos\Repository;
use App\Models\Coupon;
use App\Components\DataFilters\CouponFilter;

class CouponRepo extends Repository{
    public function __construct(Coupon $model, CouponFilter $filter){
        parent::__construct($model, $filter);
    }
}