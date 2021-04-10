<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Brand;
use App\Repos\BrandRepo;
use App\Rules\NoSpecialCharacter;
use Validator;

class BrandController extends Controller
{
    public function __construct(){
        $this->middleware('auth')->only(['manage','create','save','edit','update','delete','deleteAll']);
    }
    public function getViews(){
      return [
          'slist'=>'brand.selectable-list',
      ];      
    }
    public function index(Request $req, BrandRepo $brepo){
     $view_data['data']=$brepo->search(['per_page'
     =>$req->per_page??10])->getData();
     $viewname=$this->getViews()[$req->view];
     if($req->wm){
      $view_data['viewname']=$viewname;
      $viewname='brand.modal';  
     }   
     $view=view($viewname)->with($view_data)->render();
     return json(['view'=>$view]);
    }
    public function manage(Request $req, BrandRepo $brepo){
     $this->authorize('manage',Brand::class);
     $df=$brepo->search();
     $data=$df->getData();
     $view_data['data']=$data;
     if($req->ajax()){
        $view=view('brand.manage-list')->with($view_data)->render();
        return json(['view'=>$view]);
     }
     return view('brand.manage',$view_data);
    }
    public function delete(Request $req, BrandRepo $brepo){
        $this->authorize('delete',Brand::class);
        $result=$brepo->deleteByAttributes(['id'=>$req->id]);
        if($result){
            return json(['result'=>true]);
        }else{
            return json(['result'=>false]);
        }
    }

    public function deleteAll(Request $req, BrandRepo $brepo){
        $this->authorize('deleteAll',Brand::class);
        $affected_rows=$brepo->deleteMany($req->ids);
        if($affected_rows){
            return json(['result'=>true]);
        }else{
            return json(['result'=>false]);
        }
    }

    public function create(){
        $this->authorize('create',Brand::class);
        return view('brand.create');
    }

    public function save(Request $req, BrandRepo $brepo){
        $this->authorize('save',Brand::class);
        $rules=[
            'name'=>['required','max:191',new NoSpecialCharacter(),'unique:brands,name'],
        ];
        $validator=Validator::make($req->all(), $rules);
        if($validator->fails()){
            return json(['result'=>false, 'errors'=>getValidatorErrors($validator)]);
        }
        $data=$validator->validate($rules);
        $brand=$brepo->insert($data);
        if($brand){
            return json(['result'=>true, 'msg'=>'new brand is created.']);
        }
    }
    public function edit(Request $req, BrandRepo $brepo){
        $this->authorize('edit',Brand::class);
        $model=$brepo->findOrFail($req->id);
        return view('brand.edit',['model'=>$model]);
    }
    public function update(Request $req, BrandRepo $brepo){
        $this->authorize('update',Brand::class);
        $rules=[
            'name'=>['required','max:191','unique:brands,name, '.$req->id, new NoSpecialCharacter()],
        ];
        $validator=Validator::make($req->all(), $rules);
        if($validator->fails()){
            return json(['result'=>false, 'errors'=>getValidatorErrors($validator)]);
        }   
        $data=$validator->validate($rules);
        $model=$brepo->findOrFail($req->id);
        if($brepo->update($model, $data)){
            return json(['result'=>true, 'msg'=>'brand has been updated.']);
        }   
    }
}
