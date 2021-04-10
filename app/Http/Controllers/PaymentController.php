<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repos\OrderRepo;
use App\Components\Shop\Cart;

class PaymentController extends Controller
{

    public function done(Request $req, OrderRepo $orepo){
        $data=$req->all();
        $data['payeer_account']=$data['PAYER_ACCOUNT'];
        $data['payee_account']=$data['PAYEE_ACCOUNT'];
        $data['payment_batch_number']=$data['PAYMENT_BATCH_NUM'];
        $order=$orepo->insert($data);
        if($order){
            $cart=Cart::get();
            $order->addItems($cart);
            $cart->changeProductsState();
            $cart->clear();
            return redirect()->route('order_done',['batch_number'=>$order->payment_batch_number]);
        }
    }

    public function fail(Request $req){
        echo 'payment is failed. you will be redirected in afew seconds...';
        sleep(3);
        return redirect()->route('home');
    }
}

