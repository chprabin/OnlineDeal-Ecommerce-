<div class="header">
          <!-- header top -->
           <div class="header-top">
             <div class="row">
               <!-- logo -->
               <div class="col col-lg-1 col-md-1 col-sm-2">
                 <a href="/" class='logo'><h1 style='margin:0;'>Onlinedeal</h1></a>
               </div>
               <!-- search bar -->
               <div class="col col-lg-10 col-offset-lg-1 col-offset-md-1 col-md-10 col-sm-10 col-xs-12">
                <div class="header-search-bar">
                    <form action="{{route('products_search')}}" method='GET' class="msf cf">
                        <div class="msf-section section-left left">
                          <select name="c" id="" class='form-control' 
                          onchange="document.querySelector('.c-selector-text').
                          textContent=this.value?this.value:this.options[this.selectedIndex].text;">
                            <option value="">All</option>
                            @foreach($headerCategories as $hc)
                             @php
                              $selected=input('c')==$hc->category_show_name?"selected='selected'":null;
                             @endphp
                             <option value="{{$hc->category_show_name}}" {{$selected}}>{{$hc->category_show_name}}</option>
                            @endforeach
                          </select>
                          <a href="#" class='c-selector form-control'><span class='c-selector-text'>{{input('c')?input('c'):'All'}}</span> <i class="fa fa-caret-down"></i></a>
                        </div>
                        <div class="msf-section section-mid left">
                          <input type="text" class='form-control text-control' name='q' placeholder='Search in products'>
                        </div>
                        <div class="msf-section section-right left">
                          <input type="submit" class='form-control submit-control' value='Search'>
                        </div>
                      </form>
                </div>
               </div>
             </div>
           </div>
          <!-- end of header top -->
          <!-- header mid -->
           <div class="header-mid">
            <div class="row">
              <div class="col col-6 col-offset-lg-2 col-xs-12">
                <ul class="header-nav">
                  <li style='margin-right:30px;' class='relative'><a href="#" style='text-transform: uppercase;border:1px solid #404040;border-radius: 4px;padding:5px;' class='header-nav-bold'>categories <i style='color:#e7e0e0a8;' class="fa fa-caret-down"></i></a>
                    <div class="a-list">
                     @foreach($headerCategories as $c)
                      <a href="{{route('category_by_show_name',['showName'=>$c->category_show_name])}}" class='list-option'>{{$c->category_show_name}}</a>
                     @endforeach
                    </div>
                  </li>
                  <li><a href="/">home</a></li>
                  <li><a href="{{route('order_track')}}">track order</a></li>
                  <li><a href="#">help</a></li>
                  <li><a href="#">contact</a></li>
                </ul>
              </div>
              <div class="col col-lg-4 col-md-4 col-offset-md-2 col-xs-12">
                <ul class="header-nav multi-line-header-nav left">
                  <li><a href="#">
                    <span class="header-nav-line header-nav-line-1">hello, {{Auth::check()?Auth::user()->name:'sign in'}}</span>
                    <span class='header-nav-line header-nav-line-2'>Account & lists <i style='color:#e7e0e0a8;' class="fa fa-caret-down"></i></span>
                  </a>
                   <div class="a-list">
                     @if(Auth::check())
                      <a href="{{route('dashboard')}}" class="list-option">dasboard</a>
                      <a href="{{route('logout')}}" class="list-option" onclick="event.preventDefault();
                      this.querySelector('form').submit();">logout <form action="{{route('logout')}}" method='POST'>
                      @csrf</form></a>                      
                     @else
                      <a class='list-option' href="{{route('login')}}">sing in</a>
                      <a href="{{route('register')}}" class="list-option">register</a>
                     @endif
                      <a href="{{route('wish_list')}}" class="list-option">wish list</a>
                      <a href="{{route('my_orders')}}" class="list-option">my orders</a>
                   </div> 
                  </li>
                  <style>
                   .header-nav .hover-toggle{
                    position: absolute;
                    min-width: 300px;
                    left: -88px;
                    background: white;
                    border-radius: 6px;
                    padding: 10px;
                    box-shadow: 0 0 2px gray;
                    z-index: 2000;
                    display:none;
                   }
                   .header-nav li:hover > .hover-toggle{
                     display:initial;
                   }
                  </style>
                  <li><a href="{{route('my_orders')}}" class='header-nav-bold'>Orders</a></li>
                  <li class='relative' style='width:60px;'>
                  <a href="{{route('cart')}}" class='header-cart'><img src="{{asset('images/app/cart.png')}}"
                   style='position:relative;'
                     width='50px' alt=""> <span class='items-count'>{{$header_cart->getTotalItems()}}</span></a>
                     <div class="hover-toggle">
                      @include('cart.summary',['cart'=>$header_cart])
                      @if($header_cart->getOrderTotal() > 0)
                       <a href="{{route('order_checkout')}}" class="btn btn-block btn-sm btn-shop">Checkout</a>
                      @endif
                     </div>
                  </li>
                  <li class='relative' style='width:60px;'>
                    <a href="{{route('wish_list')}}" class="header-cart">
                     <img src="{{asset('images/app/wish-icon.png')}}" style='position:relative;' width="50px" alt="">
                     <span class="items-count">{{$header_wish->getTotalItems()}}</span>
                    </a>   
                    <div class="hover-toggle">
                     @include('wish.wish-summary',['wish'=>$header_wish])
                     <a href='{{route("wish_list")}}' class="btn-block btn btn-shop">See your list</a> 
                    </div>
                  </li>
                </ul>
              </div>
            </div>
           </div>
          <!-- end of header mid -->
        </div>