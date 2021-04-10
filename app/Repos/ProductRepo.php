<?php
namespace App\Repos;
use App\Repos\Repository;
use App\Models\Product;
use App\Components\DataFilters\ProductFilter;
use Illuminate\Support\Facades\DB;
use App\Components\Date;

class ProductRepo extends Repository{
    public function __construct(Product $model, ProductFilter $filter){
        parent::__construct($model, $filter);
    }

    public function insert(array $data){
        $model=parent::insert($data);
        if($model && is_array(json_decode($data['images']))){
            $model->addImages(json_decode($data['images']));
        }
        if($model && is_array(json_decode($data['categories']))){
            $model->addCategories(json_decode($data['categories']));
        }
        if($model && is_array(json_decode($data['options']))){
         $model->addOptions(json_decode($data['options']));
        }
        return $model;
    }

    public function update($model, $data){
        $result=parent::update($model, $data);
        if($result && is_array(json_decode($data['images']))){
            $model->addImages(json_decode($data['images']));
        }
        if($result && is_array(json_decode($data['categories']))){
            $model->addCategories(json_decode($data['categories']));
        }
        if($result && is_array(json_decode($data['options']))){
            $model->addOptions(json_decode($data['options']));
        }
        return $result;
    }

    public function getCategoryBestSellers($category, $limit=4){
      return $category->products()->with($this->relations)->withCount($this->withCounts)->
      leftJoin(DB::raw("(select productId, sum(rating) as total_ratings, count(userId)
       as total_reviews from reviews group by productId) as r")
      ,'products.id','=', 'r.productId')->leftJoin(DB::raw
      ("(select productId, orderId, sum(quantity) as total_sold_count from order_items group by productId) as oi"),
      'r.productId','=','oi.productId')->leftJoin('orders','orders.id','=','oi.orderId')->
      select(DB::raw("products.*, total_ratings, total_reviews, total_sold_count"))->groupBy('products.id')->
      orderBy("total_sold_count",'desc')->limit($limit)->get();
    }

    public function getTopRatings($count=6){
        $query=$this->model->with($this->relations)->withCount($this->withCounts)->
        join('reviews','products.id','=','reviews.productId')->groupBy('products.id')->
        select(DB::raw('products.*, sum(reviews.rating) as total_ratings, count(reviews.userId) as total_reviews,
        sum(reviews.rating)/count(reviews.userId) as average_rating'))->orderBy('average_rating','desc')->take($count);
        $page=(int)input('page');
        if(is_int($page) && $page > 0){
         $query->skip(($page-1)*$count);
        }
        return $query->get();   
    }
    public function getMostWished($count=6){
        $query=$this->model->with($this->relations)->withCount($this->withCounts)->
        join('reviews','products.id','=','reviews.productId')->join('wishes','wishes.productId','=','reviews.productId')
        ->groupBy(['products.id','reviews.productId','wishes.productId'])->
        select(DB::raw('distinct products.*, sum(reviews.rating) as total_ratings, 
        count(reviews.userId) as total_reviews, count(wishes.userId) as total_wishes'))->
        orderBy('total_wishes','desc')->take($count);
        $page=(int)input('page');
        if(is_int($page) && $page > 0){
         $query->skip(($page-1)*$count);
        }
        return $query->get();   
    }
    public function getBestSellers($count=6, $month=null){
      $query=$this->model->with($this->relations)->withCount($this->withCounts);
      $query->leftJoin(DB::raw("(select productId, sum(rating) as total_ratings, count(userId)
       as total_reviews from reviews group by productId) as r")
      ,'products.id','=', 'r.productId')->leftJoin(DB::raw
      ("(select productId, orderId, sum(quantity) as total_sold_count from order_items group by productId) as oi"),
      'r.productId','=','oi.productId')->leftJoin('orders','orders.id','=','oi.orderId')->
      select(DB::raw("products.*, total_ratings, total_reviews, total_sold_count"))->groupBy('products.id')->
      orderBy("total_sold_count",'desc');
      if(!empty($month)){
        $first_day_date=Date::buildFirstDayDate($month);
        if($first_day_date){
            $query->where('orders.created_at','>=',$first_day_date);
        }
        $last_day_date=Date::buildLastDayDate($month);
        if($last_day_date){
            $query->where('orders.created_at','<=',$last_day_date);
        }
      }
      $query->take($count);
      $page=(int)input('page');
      if(is_int($page) && $page > 0){
        $query->skip(($page-1) * $count);
      }
      return $query->get();
    }
    public function getCategoryTopRatings($category, $limit=4){
        return $category->products()->withCount([
            'reviews as total_ratings'=>function($q){
                $q->select(DB::raw('sum(rating)'));
            },'reviews as total_reviews',
        ])->with($this->relations)->join('reviews','products.id','=','reviews.productId')->
        selectRaw('products.*, avg(reviews.rating) as average_rating, count(reviews.userId) as total_reviews')
        ->groupBy('reviews.productId')->orderBy('average_rating','desc')->limit($limit)->get();
    }   

    public function getCategoryMostWishedFor($category, $limit=4){
        return $category->products()->withCount([
            'reviews as total_ratings'=>function($q){
                $q->select(DB::raw('sum(rating)'));
            },'reviews as total_reviews'
        ])->with($this->relations)->join('wishes','products.id','=','wishes.productId')->
        selectRaw('products.*, count(wishes.userId) as total_users')->groupBy('wishes.productId')->
        orderBy('total_users','desc')->limit($limit)->get();
    }

    public function getCategoryProducts($category)
    {
     return $category->products()->withCount([
         'reviews as total_reviews',
         'reviews as total_ratings'=>function($q){
            $q->select(DB::raw('sum(rating)'));
         }
     ])->paginate(app('request')->per_page??20);
    }

    public function findDetailsProduct($productId){
        return $this->model->with([
            'images','brand','categories','options'=>function($options){
                $options->with(['filter']);
            },
            'reviews'=>function($reviews){
                $reviews->orderBy('created_at','desc')->take(10);
            }
        ])->withCount([
            'reviews as total_ratings'=>function($q){
                $q->select(DB::raw('sum(rating)'));
            },'reviews as total_reviews'
        ])->findOrFail($productId);
    }

    public function getSimilarProducts($product, $leaf_category){
       $products=$this->model->whereHas('categories',function($categories) use($leaf_category){
        $categories->where('categories.id',$leaf_category->id);
       })->with([
           'categories','brand','images','options'=>function($options){
            $options->with(['filter']);
           }
       ])->withCount([
           'reviews as total_ratings'=>function($q){
            $q->select(DB::raw('sum(rating)'));
           },'reviews as total_reviews',
       ])->limit(3)->orderBy(DB::raw('RAND()'))->get();
       return $products;     
    }

    public function findReviewProduct($productId){
        return $this->model->with([
            'firstImage','brand'
        ])->withCount([
            'reviews as total_ratings'=>function($q){
                $q->select(DB::raw('sum(rating)'));
            },'reviews as total_reviews',
        ])->findOrFail($productId);
    }
}


 