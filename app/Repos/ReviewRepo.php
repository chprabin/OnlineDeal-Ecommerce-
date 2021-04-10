<?php
namespace App\Repos;
use App\Repos\Repository;
use App\Models\Review;
use App\Components\DataFilters\ReviewFilter;
use Illuminate\Support\Facades\DB;

class ReviewRepo extends Repository{
    public function __construct(Review $model, ReviewFilter $filter){
        parent::__construct($model, $filter);
    }

    public function getProductReviewGroups($product){
        return $product->reviews()->groupBy('rating')
        ->select(DB::raw('rating as star, sum(rating) as total_ratings, count(rating) as total_reviews'))->get();
    }
    public function getOtherReviews($r){
        return $this->model->where('userId',$r->userId)->where('productId','<>',$r->productId)->
        select(DB::raw("productId, abs(rating-".$r->rating.") as rating_diff"))->get();
    }
}