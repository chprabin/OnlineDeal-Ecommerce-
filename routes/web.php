<?php


Auth::routes();
Route::get('/', 'HomeController@index')->name('home');
Route::get('/account','UserController@account')->name('account');
Route::group(['prefix'=>'admin'],function(){
 /* brands routes */
 Route::get('brands','BrandController@manage')->name('brands_management');
 Route::get('brands/create','BrandController@create')->name('brand_create');
 Route::get('brands/{id}/edit','BrandController@edit')->name('brand_edit');
 Route::post('brands/save','BrandController@save')->name('brand_save');
 Route::put('brands/{id}/update','BrandController@update')->name('brand_update');
 Route::delete('brands/{id}','BrandController@delete')->name('brand_delete');
 Route::delete('brands','BrandController@deleteAll')->name('brands_delete');
 /* end of brands routes */
 /* categories routes */
 Route::get('categories','CategoryController@manage')->name('categories_management');
 Route::get('categories/create','CategoryController@create')->name('category_create');
 Route::get('categories/{id}/edit','CategoryController@edit')->name('category_edit');
 Route::post('categories/save','CategoryController@save')->name('category_save');
 Route::put('categories/{id}/update','CategoryController@update')->name('category_update');
 Route::delete('categories/{id}','CategoryController@delete')->name('category_delete');
 Route::delete('categories','CategoryController@deleteAll')->name('categories_delete');
 /* end of categories routes */
 /* filter routes */
 Route::get('filters','FilterController@manage')->name('filters_management');
 Route::get('filters/create','FilterController@create')->name('filter_create');
 Route::post('filters/save','FilterController@save')->name('filter_save');
 Route::get('filters/{id}/edit','FilterController@edit')->name('filter_edit');
 Route::put('filters/{id}/update','FilterController@update')->name('filter_update');
 Route::delete('filters/{id}','FilterController@delete')->name('filter_delete');
 Route::delete('filters','FilterController@deleteAll')->name('filters_delete');
 /* end of filter routes */
 /* options routes */
 Route::post('options/save','OptionController@save')->name('option_save');
 Route::delete('options/{id}','OptionController@delete')->name('option_delete');
 /* end of options routes */
 /* category filters routes */
 Route::delete('categories/{categoryId}/filters','CategoryFilterController@deleteCategoryFilters')->
 name('category_filters_delete');

 Route::delete('categories/{categoryId}/filters/{filterId}','CategoryFilterController@deleteCategoryFilter')->
 name('category_filter_delete'); 
 /* end of category filters routes */
 /* products routes */
 Route::get('products','ProductController@manage')->name('products_management');
 Route::get('products/create','ProductController@create')->name('product_create');
 Route::get('products/{id}/edit','ProductController@edit')->name('product_edit');
 Route::post('products/save','ProductController@save')->name('product_save');
 Route::put('products/{id}/update','ProductController@update')->name('product_update');
 Route::delete('products/{id}','ProductController@delete')->name('product_delete');
 Route::delete('products','ProductController@deleteAll')->name('products_delete');
 /* end of products routes */
 /* product images routes */
 Route::post('product-images/save','ProductImageController@save')->name('product_image_save');
 Route::delete('product-images/{id}','ProductImageController@delete')->name('product_image_delete');
 Route::delete('product-images','ProductImageController@deleteAll')->name('product_images_delete');
 /* end of product images routes */


 /* product categories */
 Route::delete('products/{productId}/categories/{categoryId}','ProductCategoryController@delete')->name('product_category_delete');
 Route::delete('products/{id}/categories','ProductCategoryController@deleteAll')->name('product_categories_delete');
 /* end of product categories */
 
 /* product options */
 Route::delete('products/{productId}/options/{optionId}','ProductOptionController@delete')
 ->name('product_option_delete');
 Route::delete('products/{productId}/options','ProductOptionController@deleteAll')->
 name('product_options_delete');
 /* end of product options */
 /* reviews */
 Route::get('reviews','ReviewController@manage')->name('reviews_management');
 Route::delete('reviews/{id}','ReviewController@delete')->name('review_delete');
 Route::delete('reviews','ReviewController@deleteAll')->name('reviews_delete');
 /* end of reviews */
 /* coupons */
 Route::get('coupons','CouponController@manage')->name('coupons_management');
 Route::get('coupons/create','CouponController@create')->name('coupon_create');
 Route::get('coupons/{id}/edit','CouponController@edit')->name('coupon_edit');
 Route::post('coupons/save','CouponController@save')->name('coupon_save');
 Route::put('coupons/{id}/update','CouponController@update')->name('coupon_update');
 Route::delete('coupons/{id}','CouponController@delete')->name('coupon_delete');
 Route::delete('coupons','CouponController@deleteAll')->name('coupons_delete');
 Route::put('coupons/{id}/partial-update','CouponController@partialUpdate')->name('coupon_partial_update');
 /* end of coupons */
 
 /* settings */
 Route::get('settings','SettingController@index','SettingController@index')->name('settings');
 Route::put('settings','SettingController@update','SettingController@update')->name('settings_update');
 /* end of settings */
 /* orders */
 Route::get('orders','OrderController@manage')->name('orders_management');
 Route::put('orders/{id}/partial-update','OrderController@partialUpdate')->name('order_partial_update');
 Route::get('orders/{id}','OrderController@details')->name('order_details');
 Route::put('orders/{id}','OrderController@update')->name('order_update');
 /* end of orders */
 /* hcs */
 Route::get('hcs','HcController@manage')->name('hcs_management');
 Route::get('hcs/create','HcController@create')->name('hc_create');
 Route::post('hcs/save','HcController@save')->name('hc_save');
 Route::delete('hcs/{id}','HcController@delete')->name('hc_delete');
 Route::delete('hcs','HcController@deleteAll')->name('hcs_delete');
 Route::post('hcs/upload-image','HcController@uploadImage');
 /* end of hcs */
 /* reports */
 Route::get('reports','ReportController@index')->name('reports');
/* end of reports */
/* promotions */
Route::get('promos','PromoController@manage')->name('promos_management');
Route::get('promos/create','PromoController@create')->name('promo_create');
Route::post('promos/save','PromoController@save')->name('promo_save');
Route::delete('promos/{id}','PromoController@delete')->name('promo_delete');
Route::delete('promos','PromoController@deleteAll')->name('promos_delete');
/* end of promotions */
/* promo products */
Route::get('promo-products','PromoProductController@index')->name('promo_products');
Route::get('promos/{promoId}/products','PromoProductController@getPromoProducts')->name('promo_promo_products');
/* end of promo products */
});

/* categories routes */
Route::get('/categories','CategoryController@index')->name('categories');
Route::get('/categories/{showname}','CategoryController@findByShowname')->name('category_by_showname');
/* end of categories routes */
/* options routes */
Route::get('/options','OptionController@index')->name('options');
Route::get('/filters/{filterId}/options','OptionController@getFilterOptions')->name('filter_options');
Route::get('/options/{id}','OptionController@find');
/* end of options routes */
/* category filters routes */
Route::get('/category-filters','CategoryFilterController@index')->name('category_filters');
Route::get('/categories/{categoryId}/filters','CategoryFilterController@getCategoryFilters')
->name('category_category_filters');
/* end of category filters routes */

/* brands routes */
Route::get('/brands','BrandController@index')->name('brands');
/* end of brands routes */

/* product images routes */
Route::get('/product-images','ProductImageController@index')->name('product_images');
Route::get('/products/{productId}/images','ProductImageController@getProductImages')->name('product_product_images');
/* end of product images routes */

/* product categories routes*/
Route::get('/product-categories','ProductCategoryController@index')->name('product_categories');
Route::get('/products/{productId}/categories','ProductCategoryController@getProductCategories')
->name('product_product_categories');
/* end of product  categories routes */

/* product options */
Route::get('/product-options','ProductOptionController@index')->name('product_options');
Route::get('/products/{productId}/options','ProductOptionController@getProductOptions')->
name('product_product_options');
/* end of product options */

/* filters */
Route::get('/filters','FilterController@index')->name('filters');
/* end of filters */
/* orders routes */
Route::get('/track-order','OrderController@track')->name('order_track');
Route::get('/orders','OrderController@getMyOrders')->name('my_orders');
Route::get('/checkout','OrderController@checkout')->name('order_checkout');
Route::post('/order-register','OrderController@register')->name('order_register');
Route::get('/order-registered','OrderController@registered')->name('order_registered');
/* end of orders */
/* wishes */
Route::get('/wishes','WishController@index')->name('wish_list');
Route::post('/wishes','WishController@save')->name('wish_save');
Route::delete('/wishes','WishController@delete')->name('wish_delete');
/* end of wishes */
/* cart rouets */
Route::get('/cart','CartController@index')->name('cart');
Route::post('/cart','CartController@save')->name('cart_save');
Route::delete('/cart','CartController@delete')->name('cart_delete');
Route::put('/cart','CartController@update')->name('cart_update');
/* end of cart routes */

/* products */
Route::get('/products/search','ProductController@search')->name('products_search');
Route::get('/products/{id}/{name}/details','ProductController@details')->name('product_details');
Route::get('/products/{productId}/recommendations','ProductController@getProductRecommendations')->name('product_recommendations');
Route::get('/personalized-recommendations','ProductController@getPersonalizedRecommendations');
Route::get('/best-sellers','ProductController@getBestSellers')->name('best_sellers');
Route::get('/top-ratings','ProductController@getTopRatings')->name('top_ratings');
Route::get('/most-wished','ProductController@getMostWished')->name('most_wished');
/* end of products */

/* product reviews */
Route::get('/products/{productId}/{productName}/reviews','ReviewController@getProductReviews')->name('product_reviews');
Route::post('/product-review','ReviewController@save')->name('review_save');
/* end of product reviews */

/* cart routes */
Route::post('/cart','CartController@save')->name('cart_save');
/* end of cart routes */
/* coupons */
 Route::post('/apply-coupon','CouponController@apply')->name('coupon_apply');
/* end of coupons */

/* order items */
Route::get('/orders/{orderId}/items','OrderItemController@getOrderItems')->name('order_items');
/* end of order items */
/* hcs */
Route::get('/hcs/{id}/view','HcController@view')->name('hc_view');
/* end of hcs */

/*payment routes */
Route::post('/payment-fail','PaymentController@fail');
Route::post('/payment-done','PaymentController@done');
Route::get('/orders/{batch_number}/done','OrderController@done')->name('order_done');
/* end of payment routes */ 

