<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Repos\CouponRepo;
use App\Rules\NoSpecialCharacter;
use App\Rules\Decimal;
use Validator;
use App\Components\Shop\Cart;
use App\Models\Coupon;
class CouponController extends Controller
{
    public function __construct(){
        $this->middleware('auth')->only(['manage','edit','create','save','update','delete','deleteAll']);
    }
    public function manage(Request $req, CouponRepo $crepo){
     $this->authorize('manage',Coupon::class);   
     $view_data['data']=$crepo->search()->getData();
     if($req->ajax()){
        $view=view('coupon.manage-list')->with($view_data)->render();
        return json(['view'=>$view]);
     }
     return view('coupon.manage',$view_data);
    }

    public function save(Request $req, CouponRepo $crepo){
        $this->authorize('save',Coupon::class);   
        $rules=[
            'name'=>['required',new NoSpecialCharacter(), 'max:191', 'unique:coupons,name'],
            'type'=>['required','in:amount,percent'],
            'amount'=>['required','integer'],
            'sdate'=>['required','date'],
            'edate'=>['required','date','after_or_equal:sdate'],
            'min_total'=>['required',new Decimal(10,2)],
            'max_usage'=>['required','integer']
        ];
        $validator=Validator::make($req->all(), $rules);
        if($validator->fails()){
            return json(['result'=>false, 'errors'=>getValidatorErrors($validator)]);
        }
        $data=$validator->validate($rules);
        $model=$crepo->insert($data);
        if($model){
         return json(['result'=>true, 'msg'=>'new coupon is created.']);
        }
    }

    
    public function update(Request $req, CouponRepo $crepo){
        $this->authorize('update',Coupon::class);   
        $rules=[
            'name'=>['required',new NoSpecialCharacter(), 'max:191', 'unique:coupons,name,'.$req->id],
            'type'=>['required','in:amount,percent'],
            'amount'=>['required','integer'],
            'sdate'=>['required','date'],
            'edate'=>['required','date','after_or_equal:sdate'],
            'min_total'=>['required',new Decimal(10,2)],
            'max_usage'=>['required','integer']
        ];
        $validator=Validator::make($req->all(), $rules);
        if($validator->fails()){
            return json(['result'=>false, 'errors'=>getValidatorErrors($validator)]);
        }
        $data=$validator->validate($rules);
        $model=$crepo->findOrFail($req->id);
        if($crepo->update($model, $data)){
         return json(['result'=>true, 'msg'=>'coupon has been updated.']);
        }
    }

    public function delete(Request $req, CouponRepo $crepo){
      $this->authorize('delete',Coupon::class);   
      if($crepo->deleteByAttributes(['id'=>$req->id])){
        return json(['result'=>true]);
      }
      return json(['result'=>false]);
    }
    
    public function deleteAll(Request $req, CouponRepo $crepo){
        $this->authorize('deleteAll',Coupon::class);   
     $affected=$crepo->deleteMany($req->ids);
     if($affected){
        return json(['result'=>true]);
     }
     return json(['result'=>false]);
    }

    public function create(){
        $this->authorize('create',Coupon::class);   
      return view('coupon.create');  
    }
    public function edit(Request $req,CouponRepo $crepo){
        $this->authorize('edit',Coupon::class);   
     $view_data['model']=$crepo->findOrFail($req->id);
     return view('coupon.edit',$view_data);
    }

    public function partialUpdate(Request $req, CouponRepo $crepo){
        $rules=[
            'active'=>['boolean'],
        ];
        $validator=Validator::make($req->all(),$rules);
        if($validator->fails()){
            return json(['result'=>false, 'errors'=>getValidatorErrors($validator)]);
        }
        $model=$crepo->findOrFail($req->id);
        $data=$validator->validate($rules);
        if($crepo->update($model, $data)){
            return json(['result'=>true,'msg'=>'coupon is updated.']);
        }
    }

    public function apply(Request $req, CouponRepo $crepo)
    {    
        $rules=[
            'coupon_code'=>['required','exists:coupons,code'],
        ];
        $validator=Validator::make($req->all(), $rules);
        $result=false;
        $ret_array=[];
        $msg='';
        $cart=Cart::get();
        if($validator->fails()){
            $errors=getValidatorErrors($validator);
            $msg=current($errors);
        }else{
            $data=$validator->validate($rules);
            $coupon=$crepo->findByAttributesOrFail(['code'=>$data['coupon_code']]);    
            if(auth()->user()->coupons()->where('coupons.id', $coupon->id)->exists()){
               $msg='You have already used coupon'; 
            }else if($coupon->used_count==$coupon->max_usage && $coupon->max_usage!=0){
               $msg='Coupon is not valid.';
            }else if(!$coupon->isActive()){
               $msg='Coupon is not valid.'; 
            }else if($coupon->min_total > $cart->getSubtotal()){
                $msg='Your should order at least $'.$coupon->min_total.' to be able to use this coupon.';
            }else{
                $cart->applyDiscount($coupon);
                $coupon->used_count++;
                $coupon->save();
                auth()->user()->coupons()->attach($coupon->id);
                $msg='Coupon applied';
                $result=true;
                $view=view('cart.cart-summary')->with(['cart'=>$cart])->render();
                $ret_array['view']=$view; 
            }
        }
        $ret_array['result']=$result;
        $ret_array['msg']=$msg;
        return json($ret_array);
    }
}
