<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use App\Components\Shop\Cart;
use App\Components\Shop\Wish;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Schema::defaultStringLength(191);
        view()->composer('*',function($view){
         $crepo=resolve('App\Repos\CategoryRepo');   
         $global_categories=$crepo->all();
         $cart=Cart::get(false);
         $wish=Wish::get();
         $view->with('global_categories',$global_categories);
         $view->with('global_cart',$cart);  
         $view->with('global_wish',$wish);
         if(auth()->check()){
            $rs=resolve('App\Components\RS'); 
            $view->with('personalized_recommendations',$rs->getPersonalizedRecommendations(4));    
         }
        });
    }
}
