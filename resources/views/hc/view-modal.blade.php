<div class="row modal-bg">
 <div class="col col-12">
  <div class="modal">
    <a href="#" class="btn modal-done">done</a>
    <div class="row">
     <div class="col col-12">
      <h2 class="modal-title">{{!empty(input('mt'))?input('mt'):'Home content details'}}</h2>
     </div>
    </div>
    <div class="row">
     <div class="col col-11">
      <div class="modal-body">
       <div class="hc-view-modal">
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