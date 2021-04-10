<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Validator;
use App\Repos\ProductRepo;
use App\Repos\ProductImageRepo;
use App\Models\ProductImage;

class ProductImageController extends Controller
{
    public function __construct(){
      $this->middleware('auth')->only(['save','delete','deleteAll']);  
    }
    public function getViews(){
      return [
       'clist'=>'product_image.create-list',
       'elist'=>'product_image.edit-list',
       'vlist'=>'product_image.view-list',   
      ];  
    }
    public function getRendrables(){
      return [
          'wi'=>'product_image.insert-form',
      ];  
    }
    public function index(Request $req){
        $viewname=$this->getViews()[$req->view];
        $view_data['rendrables']=$this->getRendrables();
        if($req->wm){
            $view_data['viewname']=$viewname;
            $viewname='product_image.modal';
        }
        if($req->inserted_data){
            $view_data['inserted_data']=json_decode($req->inserted_data);
        }
        $view=view($viewname)->with($view_data)->render();
        return json(['view'=>$view]);
    }
    public function save(Request $req, ProductImageRepo $pirepo){
        $this->authorize('save',ProductImage::class);
        $rules=[
            'image'=>['required','file','max:10000','mimetypes:image/png,image/jpg,image/jpeg'],
        ];
        $validator=Validator::make($req->all(),$rules);
        if($validator->fails()){
            return json(['result'=>false, 'errors'=>getValidatorErrors($validator)]);
        }
        $data=$validator->validate($rules);
        $image=$pirepo->uploadImage($data['image']);
        if($image){
            return json(['result'=>true, 'msg'=>'new image is created.','data'=>['image'=>$image]]);
        }
    }
    public function getProductImages(Request $req,ProductRepo $prepo,ProductImageRepo $pirepo){    
        $product=$prepo->with(['images'])->findOrFail($req->productId);
        $view_data['product']=$product;
        $view_data['rendrables']=$this->getRendrables();
        $viewname=$this->getViews()[$req->view];
        if($req->inserted_data){
            $view_data['inserted_data']=json_decode($req->inserted_data);    
        }
        if($req->wm){
           $view_data['viewname']=$viewname;
           $viewname='product_image.modal'; 
        }
        $view=view($viewname)->with($view_data)->render();
        return json(['view'=>$view
        ]);
    }
    public function delete(Request $req, ProductImageRepo $pirepo){
        $this->authorize('delete',ProductImage::class);
        $image=$pirepo->findByAttributes(['id'=>$req->id]);
        $result=true;
        if($image && !$pirepo->delete($image)){
            $result=false;
        }
        return json(['result'=>$result]);
    }

}
