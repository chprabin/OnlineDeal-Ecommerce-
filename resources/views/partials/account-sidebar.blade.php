<!--  <ul>
 <li><a href="#">link item</a></li>
 <li><a href="#">link item</a></li>
 <li><a href="#">link item</a></li>
 <li><a href="#">link item</a></li>
 <li><a href="#">link item</a></li>
 <li><a href="#">link item</a></li>
 <li><a href="#">link item</a></li>
</ul>   -->
<ul>
 @foreach(auth()->user()->roles as $role)
  @includeIf('partials.'.$role->role.'-account-sidebar')
 @endforeach
 <li><a href="{{route('my_orders')}}">My orders <i class="fa fa-angle-right"></i></a></li>
</ul>