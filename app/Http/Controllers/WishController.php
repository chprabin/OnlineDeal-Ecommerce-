<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Components\Shop\Wish;
use Validator;

class WishController extends Controller
{
    public function index(){
        $wish=Wish::get();
        return view('wish.wish-list',['wish'=>$wish]);
    }

    public function save(Request $req){
        $rules=[
            'productId'=>['required','exists:wishes,id'],
        ];
        $validator=Validator::make($req->all(), $rules);
        if($validator->fails()){
            return redirect()->back();
        }
        $wish=Wish::get();
        $data=$validator->validate($rules);
        $wish->addToList($data['productId']);
        return redirect()->route('wish_list');
    }

    public function delete(Request $req){
        $rules=[
            'productId'=>['required','exists:wishes,id'],
        ];
        $validator=Validator::make($req->all(), $rules);
        if($validator->fails()){
            return redirect()->back();
        }
        $wish=Wish::get();
        $data=$validator->validate($rules);
        $wish->removeFromList($data['productId']);
        return redirect()->back();
    }
}
