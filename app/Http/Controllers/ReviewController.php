<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Validator;
use App\Repos\ReviewRepo;
use App\Repos\ProductRepo;
use App\Rules\NoSpecialCharacter;
use App\Models\Review;
use App\Components\Rs;

class ReviewController extends Controller
{
    public function __construct(){
        $this->middleware('auth')->only(['manage','save','delete','deleteAll']);
    }

    public function manage(Request $req, ReviewRepo $rrepo){
     $this->authorize('manage',Review::class);
     $view_data['data']=$rrepo->with(['user','product'])->search()->getData();
     if($req->ajax()){
        $view=view('review.manage-list')->with($view_data)->render();
        return json(['view'=>$view]);
     }
     return view('review.manage',$view_data);
    }

    public function delete(Request $req, ReviewRepo $rrepo){
        $this->authorize('delete',Review::class);
        $result=$rrepo->delete($req->id);
        return json(['result'=>$result]);
    }

    public function deleteAll(Request $req, ReviewRepo $rrepo){
        $this->authorize('deleteAll',Review::class);
        $result=$rrepo->deleteByAttributes(['id'=>$req->ids]);
        return json(['result'=>$result]);
    }
    
    public function save(Request $req, ReviewRepo $repo){
      $rules=[
          'title'=>['required','max:300',new NoSpecialCharacters()],
          'review'=>['required',new NoSpecialCharacter()],
          'productId'=>['required','exists:products,id'],
          'userId'=>['required','in:'.$req->user()->id,],
          'rating'=>['required','in:1,2,3,4,5'],
      ];  
      $validator=Validator::make($req->all(), $rules);
      if($validator->fails())
      {
        return json(['result'=>false, 'errors'=>getValidatorErrors($validator)]);
      }
      $data=$validator->validate($rules);
      $model=$rrepo->insert($data);
      if($model){
        return json(['result'=>true, 'msg'=>'You have successfully rated this item.']);
      }else
      {
          return json(['result'=>false, 'msg'=>'You have already voted this item.']);
      }
    }

    public function getProductReviews(Request $req,ProductRepo $prepo, ReviewRepo $rrepo, RS $rs){
      $product=$prepo->findReviewProduct($req->productId);
      $view_data['product']=$product;
      $view_data['data']=$rrepo->search(['productId'=>$req->productId])->getData();
      $view_data['review_groups']=$rrepo->getProductReviewGroups($product);
      $view_data['general_recommendations']=$rs->getGeneralRecommendations($product);
      if($req->ajax()){
        $view=view('review.product-reviews-list')->with($view_data)->render();
        return json(['view'=>$view]);
      }
      return view('review.product-reviews',$view_data);
    }
}
