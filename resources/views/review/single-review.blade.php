<div class="review">
    <div class="review-row">
        <div class="inline-block"><img src="{{asset('images/app/unknown_avatar.jpg')}}" class='avatar' alt=""></div>
        <div class="inline-block"><span class="user left">{{$review->user->name}}</span></div>
    </div>
    <div class="review-row">
        <div class="inline-block">@include('partials.rating-stars',['average_rating'=>$review->rating])</div>
        <div class="inline-block"><span class="left title normal-bold">{{$review->title}}</span></div>
    </div>
    <div class="review-row">
        <div class="inline-block">
            @php
             $time=strtotime($review->created_at);
             $date=date('F j, Y',$time);
            @endphp
            <span class='date'>{{$date}}</span>
        </div>
    </div>

    <div class="review-row">
        <p class="text">{{$review->review}}</p>
    </div>
</div>