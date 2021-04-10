<div class="row modal-bg">
 <div class="col col-offset-1 col-10">
  <div class="modal">
   <a href="#" class="btn modal-done">done</a>
   <div class="row">
    <h2 class="col col-12">{{!empty(input('mt'))?input('mt'):'Promotion products'}}</h2>
   </div>
   <div class="row">
    <div class="col col-11">
     <div class="modal-body">
      <div class="promo-products">
       <div class="row">
        @foreach($rendrables as $key=>$rendrable)
          @if(request()->$key)
           <div class="col col-lg-6 col-md-6 col-xs-12 col-sm-12">
            @include($rendrable)           
           </div>
          @endif
        @endforeach
       </div>
       <div class="row">
         <div class="col col-12">
          <div class="updatable-view">
           @if($viewname)
            @include($viewname)            
           @endif
          </div>
         </div>   
       </div>
      </div>
     </div>
    </div>
   </div>
  </div>
 </div>
</div>