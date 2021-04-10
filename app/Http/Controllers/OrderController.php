<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Repos\OrderRepo;
use App\Models\Order;
use App\Components\Shop\Cart;
use App\Repos\ProductRepo;
use Validator;
use App\Rules\Decimal;
use App\Rules\NoSpecialCharacter;
use App\Rules\Phone;
use App\Repos\OrderStateRepo;
use App\Repos\CountryRepo;
use App\Components\Payment;

class OrderController extends Controller
{
    public function __construct(){
        $this->middleware('auth')->only(['register','registered','manage',
        'partialUpdate','details','update','getMyOrders','checkout','done']);
    }
    
    public function checkout(Request $req, CountryRepo $crepo){
        $cart=Cart::get();
        $cart_items=$cart->getItems();
        if(!$cart_items || !count($cart_items)){
            return redirect()->route('home');
        }
        return view('order.checkout',['cart'=>$cart, 'countries'=>$crepo->all()]);
    }

    public function manage(Request $req, OrderRepo $orepo, OrderStateRepo $osrepo, CountryRepo $crepo){
        $this->authorize('manage',Order::class);
        $view_data['data']=$orepo->with(['country','state'])->search()->getData();
        $view_data['states']=$osrepo->all();
        $view_data['countries']=$crepo->all();
        if($req->ajax()){
            $view=view('order.manage-list')->with($view_data)->render();
            return json(['view'=>$view]);
        }
        return view('order.manage',$view_data);
    }
    public function register(Request $req, OrderRepo $orepo, Payment $payment){
      $cart=Cart::get();
      $rules=[
          'firstname'=>['required',new NoSpecialCharacter(), 'max:300'],
          'lastname'=>['required',new NoSpecialCharacter(), 'max:300'],
          'email'=>['required','email','max:300'],
          'userId'=>['required','in:'.auth()->user()->id],
          'countryId'=>['required','exists:countries,id'],
          'city'=>['required',new NoSpecialCharacter(), 'max:300'],
          'street'=>['required',new NoSpecialCharacter(), 'max:300'],
          'total'=>['required',new Decimal(10,2), 'in:'.$cart->getOrderTotal()],
          'phone'=>['required',new Phone(), 'max:15'],
      ];  
      $validator=Validator::make($req->all(), $rules);
      if($validator->fails()){
        return redirect()->back()->withErrors($validator)->withInput();
      }
      $data=$validator->validate($rules);
      $data['PAYMENT_AMOUNT']=$data['total'];
      $payment->setData($data);
      /* $order=$orepo->insert($data);
      if($order){
        $order->addItems($cart);
        $cart->changeProductsState();
        $cart->clear();
        return redirect()->route('order_registered',['batch_number'=>$order->payment_batch_number]);
      }  */
      return view('payment.start',['data'=>$payment->getData()]);  
    }

    public function registered(Request $req, OrderRepo $orepo){
        if($req->batch_number){
            $order=$orepo->findByAttributesOrFail(['payment_batch_number'=>$req->batch_number]);
            return view('order.order_registered',['order'=>$order]);
        }
        abort(404);
    }

    public function track(Request $req, OrderRepo $orepo)
    {
        $view_data['order']=null;
        if(!empty($req->batch_number)){
            $view_data['order']=$orepo->with(['state'])->
            findByAttributesOrFail(['payment_batch_number'=>$req->batch_number]);
        }
        return view('order.track',$view_data);
    }

    public function partialUpdate(Request $req, OrderRepo $orepo){
      $this->authorize('partialUpdate',Order::class);
      $rules=[
        'stateId'=>['exists:order_states,id'],
      ];  
      $validator=Validator::make($req->all(), $rules);
      if($validator->fails())
      {
        return json(['result'=>false, 'errors'=>getValidatorErrors($validator)]);
      }
      $model=$orepo->findOrFail($req->id);
      $data=$validator->validate($rules);
      if($orepo->update($model, $data)){
        return json(['result'=>true, 'msg'=>'order is updated.']);
      }
    }

    public function details(Request $req, OrderRepo $orepo, OrderStateRepo $osrepo){
      $this->authorize('details',Order::class);
      $view_data['model']=$orepo->with(['user','country'])->findOrFail($req->id);
      $view_data['states']=$osrepo->all();
      return view('order.details',$view_data);
    }

    public function update(Request $req, OrderRepo $orepo){
      $this->authorize('update', Order::class);
      $rules=[
        'stateId'=>['required','exists:order_states,id'],
      ];
      $validator=Validator::make($req->all(),$rules);
      if($validator->fails()){
        return json(['result'=>false, 'errors'=>getValidatorErrors($validator)]);
      }
      $data=$validator->validate($rules);
      $model=$orepo->findOrFail($req->id);
      if($orepo->update($model, $data)){
        return json(['result'=>true, 'msg'=>'order has been updated.']);
      }
    }
    
    public function getMyOrders(Request $req, OrderRepo $orepo, OrderStateRepo $osrepo, CountryRepo $crepo){
      $view_data['countries']=$crepo->all();
      $view_data['states']=$osrepo->all();
      $orepo->getModel()->where('userId',auth()->user()->id);
      $view_data['data']=$orepo->with(['country','state'])->search()->getData();
      if($req->ajax()){
        $view=view('order.my-orders-list')->with($view_data)->render();
        return json(['view'=>$view]);
      }
      return view('order.my-orders',$view_data);
    }

    public function done(Request $req,OrderRepo $orepo){
     $order=$orepo->findByAttributesOrFail(['payment_batch_number'=>$req->batch_number]);
     return view('order.done',['order'=>$order]);
    }
}
