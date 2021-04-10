<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repos\HcRepo;
use Validator;
use App\Rules\NoSpecialCharacter;
use App\Models\Hc;

class HcController extends Controller
{
    public function __construct(){
        $this->middleware('auth')->only(['delete','deleteAll','save','manage','create']);
    }

    public function getViews(){
        return [
            'quicklook'=>'hc.quick-look',
        ];
    }
    public function manage(Request $req, HcRepo $hcrepo){
        $this->authorize('manage',Hc::class);
        $view_data['data']=$hcrepo->search(['per_page'=>$req->per_page??10])->getData();
        if($req->ajax()){
            $view=view('hc.manage-list')->with($view_data)->render();
            return json(['view'=>$view]);
        }
        return view('hc.manage',$view_data);
    }

    public function create(Request $req){
        $this->authorize('create',Hc::class);
        return view('hc.create');
    }

    public function view(Request $req, HcRepo $hcrepo){
     $model=$hcrepo->findOrFail($req->id);
     $view_data['model']=$model;
     $viewname=$this->getViews()[$req->view];
     if($req->wm){
        $view_data['viewname']=$viewname;
        $viewname='hc.view-modal';
     }       
     if($req->ajax()){
        $view=view($viewname)->with($view_data)->render();
        return json(['view'=>$view]);
     }
     return view($viewname, $view_data);
    }

    public function save(Request $req, HcRepo $hcrepo){
        $this->authorize('save',Hc::class);
        $rules=[
            'name'=>['required',new NoSpecialCharacter(), 'max:191','unique:hcs,name'],
            'content'=>['required'],
            'section'=>['required','in:banner,category_grid'],
        ];
        $validator=Validator::make($req->all(), $rules);
        if($validator->fails()){
            return json(['result'=>false, 'errors'=>getValidatorErrors($validator)]);
        }
        $data=$validator->validate($rules);
        $model=$hcrepo->insert($data);
        if($model)
         return json(['result'=>true, 'msg'=>'new home content is created.']);
    }

    public function delete(Request $req, HcRepo $hcrepo){
        $this->authorize('delete',Hc::class);
        $result=false;
        if($hcrepo->delete($req->id)){
         $result=true;
        }
        return json(['result'=>$result]);
    }
    public function deleteAll(Request $req, HcRepo $hcrepo){
        $this->authorize('deleteAll',Hc::class);
        $result=false;
        if($hcrepo->deleteMany($req->ids)){
            $result=true;
        }
        return json(['result'=>$result]);
    }

    public function uploadImage(Request $req){
        $rules=[
            'image'=>['required','mimetypes:image/jpg,image/jpeg,image/png','max:5000'],
        ];
        $validator=Validator::make($req->all(), $rules);
        if($validator->fails()){
            return json(['result'=>false, 'errors'=>getValidatorErrors($validator)]);
        }
        $image=$req->image->store('content-uploads');
        if($image){
            return json(['result'=>true,'location'=>$image]);
        }    
    }
}
