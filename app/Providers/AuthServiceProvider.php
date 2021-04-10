<?php

namespace App\Providers;

use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        // 'App\Model' => 'App\Policies\ModelPolicy',
        'App\Models\Brand'=>'App\Policies\BrandPolicy',
        'App\Models\Category'=>'App\Policies\CategoryPolicy',
        'App\Models\Filter'=>'App\Policies\FilterPolicy',
        'App\Models\Option'=>'App\Policies\OptionPolicy',
        'App\Models\CategoryFilter'=>'App\Policies\CategoryFilterPolicy',
        'App\Models\Product'=>'App\Policies\ProductPolicy',
        'App\Models\ProductImage'=>'App\Policies\ProductImagePolicy',
        'App\Models\ProductCategory'=>'App\Policies\ProductCategoryPolicy',
        'App\Models\ProductOption'=>'App\Policies\ProductOptionPolicy',        
        'App\Models\Review'=>'App\Policies\ReviewPolicy',        
        'App\Models\Coupon'=>'App\Policies\CouponPolicy',        
        'App\Components\Setting'=>'App\Policies\SettingPolicy',        
        'App\Models\Order'=>'App\Policies\OrderPolicy',        
        'App\Models\Hc'=>'App\Policies\HcPolicy',        
        'App\Components\Report'=>'App\Policies\ReportPolicy',        
        'App\Models\Promo'=>'App\Policies\PromoPolicy',        
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        //
    }
}
