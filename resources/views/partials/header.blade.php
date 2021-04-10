<div class="header">
 <div class="header-top">
  <div class="row">
   <div class="col col-lg-2 col-md-2 col-xs-12 col-sm-2">
    <a href="/" class="logo"><h1 class="no-margin" style='font-weight:bold;'>Onlinedeal</h1></a>
   </div>
   <div class="col col-lg-10 col-md-10 col-xs-12 col-sm-10">
    <form action="{{route('products_search')}}" class="msf cf">
     <div class="form-section section-left">
      @php
       $c=input('c');
       $selected_category=$global_categories->first(function($categ) use($c){
        return $c==$categ->showname;
       });  
       $selector_text='All';
       if($selected_category){
        $selector_text=$selected_category->showname;
       }
      @endphp
       <select name="c" id="" class='form-control select-control'>
        <option value="">All categories</option>
        @foreach($global_categories as $gc)
         @php
          $selected=!empty($selected_category) && $selected_category->id==
          $gc->id?"selected='selected'":null;
         @endphp
         <option value="{{$gc->showname}}" {{$selected}}>{{$gc->showname}}</option>
        @endforeach
       </select>  
       <a href="#" class="c-selector form-control"><span class="selector-face">{{$selector_text}}</span> <i class="fa fa-caret-down"></i></a>
     </div>
     <div class="form-section section-mid">
      <input type="text" name='sq' placeholder='Search in products' value="{{input('sq')}}" class='form-control text-control'>
     </div>
     <div class="form-section section-right">
      <input type="submit" class='form-control submit-control' value='Search'>
     </div>
    </form>
   </div>
  </div>
 </div>
 <div class="header-mid">
  <div class="row">
   <div class="col col-offset-lg-2 col-offset-md-2 col-lg-6 col-md-6 col-xs-12 col-sm-12">
    <ul class="header-nav">
     <li style='margin-right:30px;' class='relative'><a class='categories-toggle'
      href="#">Categories <i class="fa fa-caret-down" style='color:#e7e0e0a8;'></i></a>
       <div class="a-list">
        @foreach($global_categories as $gc)
         <a href="{{route('category_by_showname',['showname'=>$gc->showname])}}" class="list-item">{{$gc->showname}}</a>
        @endforeach
       </div>
     </li>
     <li><a href="/">Home</a></li>
     <li><a href="{{route('order_track')}}">Track order</a></li>
     <li><a href="#">Contact</a></li>
     <li><a href="#">About us</a></li>
    </ul>
   </div>
   <div class="col col-lg-4 col-md-4 col-xs-12 col-sm-12">
    <ul class="header-nav multi-line-header-nav">
     <li>
      <a href="#">
       <span class="header-nav-line header-nav-line-1">welcome, {{Auth::check()?Auth::user()->name:'sign in'}}</span>
       <span class="header-nav-line header-nav-line-2">Account & lists <i class="fa fa-caret-down" style='color:#e7e0e0a8;'></i></span>
      </a>
      <div class="a-list">
        <a href="{{route('account')}}" class="list-item">account</a>
        <a href="{{route('wish_list')}}" class="list-item">wish list</a>
        @if(Auth::check())
          <form action="{{route('logout')}}" class='logout' method='POST'>
           @csrf
           <input type="submit" value="logout">
          </form>
         </a>
        @else
          <a href="{{route('register')}}" class="list-item">register</a>
          <a href="{{route('login')}}" class="list-item">login</a>
        @endif 
      </div>
     </li>
     <li><a href="{{route('my_orders')}}" class='header-nav-bold'>Orders</a></li>
     <li class='relative'>
      <a href="{{route('cart')}}" class="header-shop relative block">
       <img src="{{asset('images/app/cart.png')}}" alt="">
       <span class="items-count">{{$global_cart->getTotalItems()}}</span>
      </a>
      <div class="hover-toggle">
       @include('cart.cart-summary',['cart'=>$global_cart])
       @if($global_cart->getOrderTotal() > 0)
        <a href="{{route('order_checkout')}}" class="btn text-center btn-block btn-shop">Checkout</a>
       @endif
      </div>
     </li>
     <li class='relative'>
      <a href="{{route('wish_list')}}" class='header-shop relative block'>
       <img src="{{asset('images/app/wish-icon.png')}}" alt="">
       <span class="items-count">{{$global_wish->getTotalItems()}}</span>
      </a>
      <div class="hover-toggle">
       @include('wish.wish-summary',['wish'=>$global_wish])
       <a href="{{route('wish_list')}}" class="btn btn-shop btn-block text-center">See wish list</a>
      </div>
     </li>
    </ul>
   </div>
  </div>
 </div>
</div>
<script>
 var msf_select_control=document.querySelector('.msf select.select-control');
 var c_selector=document.querySelector('.msf .c-selector');
 msf_select_control.onchange=function(){
  if(this.value){
    c_selector.querySelector('.selector-face').textContent=this.value;
  }else{
    c_selector.querySelector('.selector-face').textContent=this.options[this.selectedIndex].text;
  }
 }
</script>