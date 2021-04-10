<?php
namespace App\Repos;
use App\Repos\Repository;
use App\Models\Order;
use App\Components\DataFilters\OrderFilter;
use Illuminate\Support\Facades\DB;
use App\Components\Date;

class OrderRepo extends Repository{
    public function __construct(Order $model, OrderFilter $filter){
        parent::__construct($model, $filter);
    }
    public function getOrdersStats($month=null){
        $query=$this->model->select(DB::raw('sum(total) as orders_total, count(id) as total_orders'));
        if(!empty($month)){
            $first_day_date=Date::buildFirstDayDate($month);
            if($first_day_date){
                $query->where('created_at','>=',$first_day_date);
            }
            $last_day_date=Date::buildLastDayDate($month);
            if($last_day_date){
                $query->where('created_at','<=',$last_day_date);
            }
        }
        return $query->first();
    }
    public function getMonthlyOrders($month){
        $query=$this->model->select(DB::raw('count(id) as total_orders, created_at as orders_day'))->groupBy('created_at');
        $first_day_date=Date::buildFirstDayDate($month);
        if($first_day_date){
            $query->where('created_at','>=',$first_day_date);
        }
        $last_day_date=Date::buildLastDayDate($month);
        if($last_day_date){
            $query->where('created_at','<=',$last_day_date);
        }
        $query->orderBy('created_at');
        return $query->get();
    }
}