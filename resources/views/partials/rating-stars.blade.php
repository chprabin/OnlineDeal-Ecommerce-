    <?php
     $full_stars=floor($average_rating);
     $half_stars=0;
     $empty_stars=0;
     if((int)$average_rating-$average_rating==0){
      $half_stars=0;
     }else{
      $half_stars=1; 
     }
     $empty_stars=5-($full_stars+$half_stars);
     if(!function_exists('print_star'))
     {
        function print_star($star_type='full_star'){
            $url='';
            if($star_type=='full_star'){
                $url=asset('images/app/star.jpg');
            }else if($star_type=='half_star'){
                $url=asset('images/app/half-star.png');
            }else if($star_type=='empty_star'){
                $url=asset('images/app/empty-star.jpg');
            }
            $star_width=isset($star_width)?$star_width.'px':'20px';
            echo "<img class='rating-star' src='".$url."' width='".$star_width."' />";
        }    
     }

     if($full_stars || $half_stars){
        echo  "<div class='inline-block'>";
        for($i=0;$i<$full_stars;$i++){
            print_star('full_star');
        }
        if($half_stars){
            print_star('half_star');
        }
        for($i=0;$i<$empty_stars;$i++){
            print_star('empty_star');
        }
        echo "</div>";
     }
    ?>