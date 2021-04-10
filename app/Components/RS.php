<?php
namespace App\Components;
use App\Models\Review;
use App\User;
use App\Models\Product;
use Illuminate\Support\Facades\DB;
use App\Repos\RSMatrixRepo;
use App\Repos\ReviewRepo;

/* 
 itemid1 itemid2 sum count
 2       1       +rating_diff +1
 1       2
 3       1       rating_diff   1
 1       3       -rating_diff  1   

 productid userid rating
 1         1      4 
 2         1      2  
 3         1      5
*/
class RS{
    private $rrepo=null;
    private $rsmrepo=null;
    public function __construct(ReviewRepo $rrepo, RSMatrixRepo $rsmrepo){
        $this->rrepo=$rrepo;
        $this->rsmrepo=$rsmrepo;
    }
    public function updateMatrix(Review $r){
        $other_reviews=$this->rrepo->getOtherReviews($r);
        if($other_reviews->count()){
           $insertable_items=[];
           $updatable_items=[];
           $matrix_items=$this->rsmrepo->getAllByAttributes(['itemId1'=>$r->productId, 
           'itemId2'=>$other_reviews->pluck('productId')->toArray()]);
           foreach($other_reviews as $or){
            $matrix_item=$matrix_items->first(function($item) use($r,$or){
              return $item->itemId1==$r->productId && $item->itemId2==$or->productId;      
            });
            if(!empty($matrix_item)){
              $update_item1=['itemId1'=>$r->productId, 'itemId2'=>$or->productId,
              'count'=>$matrix_item->count+1,'sum'=>$matrix_item->sum+$or->rating_diff];
              $update_item2=['itemId1'=>$or->productId, 'itemId2'=>$r->productId,
             'sum'=>-1*($matrix_item->sum+$or->rating_diff),'count'=>$matrix_item->count+1];  
             array_push($updatable_items, $update_item1);
             array_push($updatable_items, $update_item2);
            }else{
                $insert_item1=['itemId1'=>$r->productId, 'itemId2'=>$or->productId,
                'sum'=>$or->rating_diff,'count'=>1];
                $insert_item2=['itemId1'=>$or->productId, 'itemId2'=>$r->productId,
                'sum'=>-1*($or->rating_diff),'count'=>1];
                array_push($insertable_items, $insert_item1);
                array_push($insertable_items, $insert_item2);
            }
           } 

           if(count($updatable_items)){
             $this->rsmrepo->updateMany($updatable_items);   
           }
           if(count($insertable_items)){
            $this->rsmrepo->insertMany($insertable_items);
           }
        }
    }

    public function getGeneralRecommendations($product, $count=4){
        $productId=is_numeric($product)?$product:$product->id;
        return Product::with(['firstImage'])->join('rs_matrix','products.id','=','rs_matrix.itemId2')->
        join('reviews','products.id','=','reviews.productId')->
        select(DB::raw('products.*, (rs_matrix.sum/rs_matrix.count) as average_rating, sum(rating) as total_ratings,
        count(userId) as total_reviews'))->where('rs_matrix.itemId1',$productId)->groupBy('products.id')
        ->orderBy('average_rating','desc')->paginate($count);
    }

    public function getPersonalizedRecommendations($count=6){
      if(!auth()->check()){
        return collect([]);
      }  
      return Product::with(['firstImage'])->join('reviews','reviews.productId','=','products.id')->
      join('rs_matrix',function($join){
       $join->on('rs_matrix.itemId2','=','reviews.productId'); 
      })->where('reviews.userId',auth()->user()->id)->groupBy('rs_matrix.itemId2')->
      select(DB::raw('products.*, sum(rs_matrix.sum+rs_matrix.count*reviews.rating)/sum(rs_matrix.count) as average_rating,
      sum(rating) as total_ratings, count(userId) as total_reviews'))->orderBy('average_rating','desc')
      ->paginate($count);
    }
}