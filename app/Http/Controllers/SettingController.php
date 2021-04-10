<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Components\Setting;
use Validator;

class SettingController extends Controller
{
    public function __construct(){
        $this->middleware('auth')->only(['index','update']);
    }
    public function index(Request $req){
        $this->authorize('index',Setting::class);
        $view_data['settings']=config('settings');
        return view('setting.index',$view_data);
    }
    public function update(Request $req){
     $rules=[
         'test_mode'=>['in:0,1'],
     ];
     $validator=Validator::make($req->all(),$rules);
     if($validator->fails()){
      return json(['result'=>false, 'errors'=>getValidatorErrors($validator)]);
     }
     $settings=config('settings');
     $data=$validator->validate($rules);
     foreach($data as $key=>$value){
      if(array_key_exists($key, $settings)){
        $settings[$key]=$value;
      }
     }
     file_put_contents(ROOT.'/config/settings.php',"<?php return ".var_export($settings, true).';');
     return json(['result'=>true]);
    }
}
