@php
 $routes=[
    ['text'=>'brands','name'=>'brands_management','params'=>['wcrud'=>1]],
    ['text'=>'categories','name'=>'categories_management','params'=>['wcrud'=>1]],
    ['text'=>'filters','name'=>'filters_management','params'=>['wcrud'=>1]],
    ['text'=>'products','name'=>'products_management','params'=>['wcrud'=>1]],
    ['text'=>'reviews','name'=>'reviews_management','params'=>['wcrud'=>1]],
    ['text'=>'coupons','name'=>'coupons_management','params'=>['wcrud'=>1]],
    ['text'=>'Orders','name'=>'orders_management','params'=>[]],
    ['text'=>'Home contents','name'=>'hcs_management','params'=>['wcrud'=>1]],
    ['text'=>'Promotions','name'=>'promos_management','params'=>['wcrud'=>1]],
    ['text'=>'Statistics','name'=>'reports','params'=>[]],
    ['text'=>'Application settings','name'=>'settings','params'=>[]],
    ];
@endphp
@foreach($routes as $route)
 <li><a href="{{route($route['name'], $route['params'])}}">{{$route['text']}} <i class="fa fa-angle-right"></i></a></li>
@endforeach