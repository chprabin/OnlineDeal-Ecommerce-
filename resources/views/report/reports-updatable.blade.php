<style>
 div.stats-summary .summary-section{
     display:inline-block;
     width:300px;
     border:1px solid #ccc;
     padding:10px;
     border-radius:4px;
     background:#f7f7f7;
     font-size:16px;
     float:left;
     min-height:160px;
     margin-right:10px;
 }  
 div.summary-section .section-title{
     margin:7px 0;
 }
 div.summary-section .section-row{
     margin:10px 0;
 }
 div.summary-section .section-row span:first-child{
     min-width:100px;
     display:inline-block;
 }

</style>
<div class="row">
 <div class="col col-12">
  <div class="stats-summary">
  
   <div class="summary-section">
    <h3 class="section-title">Orders</h3>
    <div class="section-content">
     <div class="section-row">
      <span>Total salses:</span>
      <span>${{number_format($orders_stats->orders_total, 2, '.', '')}}</span>
     </div>
     <div class="section-row">
      <span>Total orders: </span>
      <span>{{$orders_stats->total_orders}}</span>
     </div>
    </div>
   </div>
  
   <div class="summary-section">
    <h3 class="section-title">Monthly orders of {{$month}}</h3>
    <div class="section-content">
     <div class="section-row">
      <span>Total sales: </span>
      <span>${{number_format($monthly_orders_stats->orders_total, 2, '.', '')}}</span>
     </div>    
     <div class="section-row">
      <span>Total orders: </span>
      <span>{{$monthly_orders_stats->total_orders}}</span>
     </div>
     <div class="section-row">
      @php
       $total_orders_percentage=$monthly_orders_stats->total_orders/$orders_stats->total_orders * 100;
      @endphp
      <span>{{number_format($total_orders_percentage,2,'.','').'%'}} of total orders</span>
     </div>
    </div>
   </div> 

    <div class="summary-section">
     <h3 class="section-title">Users registered on {{$month}}</h3>
     <div class="section-content">
      <div class="section-row">
       <span>Total users: </span>
       <span>{{$users_stats->total_users}}</span>
      </div>
      <div class="section-row">
       <span>Total sales:</span>
       <span>${{number_format($users_stats->orders_total, 2, '.', '')}}</span>
      </div>
      <div class="section-row">
       @php
        $users_sales_percentage=$users_stats->orders_total/$orders_stats->orders_total * 100;
       @endphp
       <span>{{number_format($users_sales_percentage,2,'.','').'%'}} of total sales</span>
      </div>
     </div>       
    </div>    

  </div>
 </div>
</div>

<div class="row">
    <div class="col col-lg-7 col-md-7 col-xs-12 col-sm-12">
     <div style='width:655px; height:300px;'>
      <canvas id='chart' style='width:100%;height:100%;'></canvas>
     </div>
    </div>
    <div class="col col-lg-5 col-md-5 col-xs-12 col-sm-12">
     <div style='padding:15px 5px;'>
      <h2 class="normal-bold" style='border-bottom:1px solid #ccc;padding-bottom:10px;'>Best sellers</h2>
      @foreach($best_sellers as $p)
       <div class="row">
        <div class="col col-12">
          <div class="left" style='margin-right:10px;'>
           <img src="{{asset($p->firstImage()->first()->image)}}" style='width:50px;height:50px;' alt="">
          </div>
          <div class="left" style='width:300px;'>
           <a href="{{route('product_details',['id'=>$p->id, 'name'=>$p->name])}}" 
           class="normal-text block">{{shorten_text($p->name, 50)}}</a>
           @if($p->total_reviews)
            @php
             $average_rating=calculate_average_rating($p->total_ratings, $p->total_reviews);
            @endphp
            <div style='margin:5px 0;'>
             <a href="{{route('product_reviews',['productId'=>$p->id, 'productName'=>$p->name])}}" 
             class="block">
              @include('partials.rating-stars',['average_rating'=>$average_rating])
             </a>
            </div>
           @endif
           <div>
            <span style='color:brown; font-size:15px;'>${{number_format($p->price,2,'.','')}}</span>
           </div>
          </div>  
        </div>
       </div>
      @endforeach
     </div>
    </div>
</div>
<link rel="stylesheet" href="{{asset('css/chartjs.css')}}">
<script>
 require(['chartjs'],function(){
     var monthly_orders=@json($monthly_orders);
     monthly_orders=JSON.parse(monthly_orders);
     var labels=[];
     var data_points=[];
     monthly_orders.forEach(function(o){
        var d=new Date(o.orders_day);
        d=d.getFullYear()+'/'+(d.getMonth()+1)+'/'+d.getDate();
        labels.push(d);
        data_points.push(o.total_orders);
     });
     var ctx=document.getElementById('chart').getContext('2d');
     var chart=new Chart(ctx, {
         type:'line',
         data:{
            labels:labels,
            datasets:[
                {
                    label:'Orders per day',
                    data:data_points,
                }
            ],
         },
     });
 });
</script>