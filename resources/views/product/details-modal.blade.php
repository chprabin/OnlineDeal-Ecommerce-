<div class="row modal-bg">
 <div class="col col-12">
  <div class="modal">
    <a href="#" class="btn modal-done">done</a>
    <div class="row">
     <div class="col col-12">
      <h2 class="modal-title">{{!empty(input('mt'))?input('mt'):'Product details'}}</h2>
     </div>
    </div>
    <div class="row">
     <div class="col col-11">
      <div class="modal-body">
       <div class="product-details-modal">
        <div class="row">
         <div class="col col-12">
          <div class="updatable-view">
           @if(isset($viewname))
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