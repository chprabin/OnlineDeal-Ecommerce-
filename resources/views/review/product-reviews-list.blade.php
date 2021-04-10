<div class="row">
 <div class="col col-lg-6 col-md-6 col-xs-12 col-sm-12">
     <form action="{{route('product_reviews',['productId'=>$product->id, 'productName'=>$product->name])}}" class='search-form'>
      <div class="row">
        <div class="col col-lg-8 col-md-8 col-xs-12 col-sm-12">
            <input type="text" name='q' value="{{input('q')}}" placeholder='Search in reviews...' class='form-control control-block'>
        </div>
        <div class="col col-lg-4 col-md-4 col-xs-12 col-sm-12">
            <input type="submit" value='Search' class='btn btn-primary'>
        </div>
      </div>
     </form>
 </div> 
</div>

<div class="row">
 <!-- reviews list -->
  <div class="col col-lg-8 col-md-8 col-xs-12 col-sm-12">
   @if($data->total())
    <div style='margin:20px 0;'>
     @php
      $start=($data->currentPage()-1)*$data->perPage()+1;
      $end=($start+$data->perPage()) <= $data->total() ? ($start+$data->perPage()) : $data->total();
      $printable='Showing '.$start.'-'.$end.' of '.$data->total().' reviews';
     @endphp
     <span style='font-size:16px; font-weight:600'>{{$printable}}</span>
    </div>

    <div class="results">
     @foreach($data as $d)
      @include('review.single-review',['review'=>$d])
     @endforeach
     <div class='pagination'>
      {{$data->links()}}
     </div>
    </div>
   @else
    <p>no data found</p>
   @endif
  </div>
 <!-- end of reviews list -->
</div>

