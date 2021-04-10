<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Repos\HcRepo;
use App\Repos\ProductRepo;
use Illuminate\Support\Facades\DB;
use App\Models\Promo;
use App\Models\Product;
use App\Models\Category;
use App\Models\Option;
use App\Models\Filter;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
     //$this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(Request $req,HcRepo $hcrepo, ProductRepo $prepo)
    {
     $hcs=$hcrepo->all();
     $view_data['banners']=$hcs->where('section','banner');
     $view_data['category_grid']=$hcs->where('section','category_grid');
     $view_data['best_sellers']=$prepo->with(['firstImage'])->getBestSellers();
     $view_data['top_ratings']=$prepo->with(['firstImage'])->getTopRatings();
     $view_data['most_wished']=$prepo->with(['firstImage'])->getMostWished();

     /* $products=Product::get()->pluck('id')->toArray();
     $users=User::get()->pluck('id')->toArray();
     $ratings=[1,2,3,4,5];
     foreach($products as $p){
      $rating_users=[];
      foreach(range(1,25) as $ri){
       $userId=$users[rand(0, count($users)-1)];
       while(in_array($userId, $rating_users)){
        $userId=$users[rand(0, count($users)-1)];
       }
       array_push($rating_users, $userId);
       $rating=$ratings[rand(0, count($ratings)-1)]; 
       $data=[
           'userId'=>$userId,'rating'=>$rating,
            'productId'=>$p, 'title'=>'this is a sample review',
            'review'=>'following review is generated using simple php code.'
       ];
       Review::create($data);
      }
     }    */

     /* $users=User::get()->pluck('id')->toArray();
     $products=Product::get()->pluck('id')->toArray();
     foreach($products as $productId){
       $wishing_users=[];  
       foreach(range(0, rand(20,30)) as $r){
        $userId=$users[rand(0, count($users)-1)];
        while(in_array($userId, $wishing_users)){
         $userId=$users[rand(0, count($users)-1)];
        }
        array_push($wishing_users, $userId);
        $data=['userId'=>$userId, 'productId'=>$productId];
        Wish::create($data);
       } 
     }   */
    /*  $countries = array("Afghanistan", "Albania", "Algeria", "American Samoa", "Andorra",
     "Angola", "Anguilla", "Antarctica", "Antigua and Barbuda", "Argentina", "Armenia", "Aruba", "Australia", "Austria", "Azerbaijan", "Bahamas", "Bahrain", "Bangladesh", "Barbados", "Belarus", "Belgium", "Belize", "Benin", "Bermuda", "Bhutan", "Bolivia", "Bosnia and Herzegowina", "Botswana", "Bouvet Island", "Brazil", 
     "British Indian Ocean Territory", "Brunei Darussalam", "Bulgaria", "Burkina Faso", "Burundi", "Cambodia",
     "Cameroon", "Canada", "Cape Verde", "Cayman Islands", "Central African Republic", "Chad", "Chile", "China", "Christmas Island", "Cocos (Keeling) Islands", "Colombia", "Comoros", "Congo", "Congo, the Democratic Republic of the", "Cook Islands", "Costa Rica", "Cote d'Ivoire", "Croatia (Hrvatska)", "Cuba", "Cyprus", "Czech Republic", "Denmark", "Djibouti", "Dominica", "Dominican Republic", "East Timor", "Ecuador", "Egypt", "El Salvador", "Equatorial Guinea", "Eritrea", "Estonia", "Ethiopia", "Falkland Islands (Malvinas)", "Faroe Islands", "Fiji", "Finland", "France", "France Metropolitan", "French Guiana", "French Polynesia", "French Southern Territories", "Gabon", "Gambia", "Georgia", "Germany", "Ghana", "Gibraltar", "Greece", "Greenland", "Grenada", "Guadeloupe", "Guam", "Guatemala", "Guinea", "Guinea-Bissau", "Guyana", 
    "Haiti", "Heard and Mc Donald Islands", "Holy See (Vatican City State)", "Honduras", "Hong Kong", "Hungary", "Iceland", "India", "Indonesia", "Iran (Islamic Republic of)", "Iraq", "Ireland", "Israel", "Italy", "Jamaica", "Japan", "Jordan", "Kazakhstan", "Kenya", "Kiribati", 
    "Korea, Democratic People's Republic of", "Korea, Republic of", "Kuwait", "Kyrgyzstan", "Lao, People's Democratic Republic", "Latvia", "Lebanon", "Lesotho", "Liberia", "Libyan Arab Jamahiriya", "Liechtenstein", "Lithuania", "Luxembourg"
    , "Macau", "Macedonia, The Former Yugoslav Republic of", "Madagascar", "Malawi", "Malaysia", "Maldives", "Mali", "Malta", "Marshall Islands", "Martinique", "Mauritania", "Mauritius", "Mayotte", "Mexico", "Micronesia, Federated States of", "Moldova, Republic of", 
    "Monaco", "Mongolia", "Montserrat", "Morocco", "Mozambique", "Myanmar", "Namibia", "Nauru", "Nepal", "Netherlands", "Netherlands Antilles", "New Caledonia", "New Zealand", "Nicaragua", "Niger", "Nigeria", 
    "Niue", "Norfolk Island", "Northern Mariana Islands", "Norway", "Oman", "Pakistan", "Palau", "Panama", "Papua New Guinea", "Paraguay", "Peru", "Philippines", "Pitcairn", 
    "Poland", "Portugal", "Puerto Rico", "Qatar", "Reunion", "Romania", "Russian Federation", "Rwanda", "Saint Kitts and Nevis", "Saint Lucia",
     "Saint Vincent and the Grenadines", "Samoa", "San Marino", "Sao Tome and Principe", "Saudi Arabia", "Senegal", "Seychelles", "Sierra Leone", "Singapore", "Slovakia (Slovak Republic)", "Slovenia", "Solomon Islands", "Somalia", "South Africa", 
    "South Georgia and the South Sandwich Islands", "Spain", "Sri Lanka", "St. Helena", "St. Pierre and Miquelon", "Sudan", "Suriname",
     "Svalbard and Jan Mayen Islands", "Swaziland", "Sweden", "Switzerland", "Syrian Arab Republic", "Taiwan, Province of China", "Tajikistan", 
    "Tanzania, United Republic of", "Thailand", "Togo", "Tokelau", "Tonga", "Trinidad and Tobago", "Tunisia", 
    "Turkey", "Turkmenistan", "Turks and Caicos Islands", "Tuvalu", "Uganda", "Ukraine", "United Arab Emirates", 
    "United Kingdom", "United States", "United States Minor Outlying Islands", "Uruguay", "Uzbekistan", 
    "Vanuatu", "Venezuela", "Vietnam", "Virgin Islands (British)", "Virgin Islands (U.S.)", 
    "Wallis and Futuna Islands", "Western Sahara", "Yemen", "Yugoslavia", "Zambia", "Zimbabwe");
     foreach($countries as $c){
      Country::create(['name'=>$c]);
     } */
    /*  ini_set('max_execution_time','0');
     $users=User::get()->pluck('id')->toArray();
     $products=Product::get()->pluck('id')->toArray();
     $ratings=range(1,5);
     foreach($users as $userId){
      $user_rated_products=[];
      foreach(range(1,rand(15,25)) as $rating_index){
        $data['userId']=$userId;
        $productId=$products[rand(0, count($products)-1)];
        while(in_array($productId, $user_rated_products)){
         $productId=$products[rand(0, count($products)-1)];
        }
        array_push($user_rated_products, $productId);
        $data['productId']=$productId;
        $data['rating']=$ratings[rand(0, count($ratings)-1)];
        $r=Review::create($data);
        $rs->updateMatrix($r);
      }
     } */
    /*  ini_set('max_execution_time','0');
     $users=User::get()->pluck('id')->toArray();
     $products=Product::get()->pluck('id')->toArray();
     $countries=Country::get()->pluck('id')->toArray();
     foreach($users as $userid){
       foreach(range(1,rand(3,6)) as $uo){
        $data=['userId'=>$userid,'countryId'=>$countries[rand(0,count($countries)-1)],'stateId'=>1];
       $order_products=[];
       $order_items=[];
       foreach(range(1,rand(5,10)) as $pi){   
         $productId=$products[rand(0,count($products)-1)];
         while(in_array($productId, $order_products)){
          $productId=$products[rand(0,count($products)-1)];
         }
         array_push($order_products, $productId);
         array_push($order_items, ['productId'=>$productId, 'quantity'=>rand(1,10)]);
       }
       $date='2019-'.rand(1,12).'-'.rand(1,31);
       $data['created_at']=$date;
       $data['updated_at']=$date;
       $data['total']=rand(10000,300000);
       $o=Order::create($data);
       foreach($order_items as $index=>$order_item)
       {
        $order_item['orderId']=$o->id;
        $order_items[$index]=$order_item;
        $o->items()->create($order_item);
       }
       }
     } */
     return view('home',$view_data);
    }
}
