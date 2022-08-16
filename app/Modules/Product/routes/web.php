<?php

Route::group(['prefix'=>'admin','module' => 'Product', 'middleware' => ['web','auth','admin'], 'namespace' => 'App\Modules\Product\Controllers\Backend'], function() {

    /*
     * product category Routes
     */
    Route::get('product/categories/{id}/delete','ProductCategoryController@delete');
    Route::resource('product/categories', 'ProductCategoryController', ['names' => [
        'index'     => 'admin.product.categories.index',
        'create'    => 'admin.product.categories.create',
        'store'     => 'admin.product.categories.store',
        'edit'      => 'admin.product.categories.edit',
        'update'    => 'admin.product.categories.update'
    ]]);

    /*
     *  product subcategory Routes
     */
    Route::get('product/subcategories-by-category','ProductSubcategoryController@subcategoriesByCategory');
    Route::get('product/subcategories/{id}/delete','ProductSubcategoryController@delete');
    Route::resource('product/subcategories', 'ProductSubcategoryController', ['names' => [
        'index'    => 'admin.product.subcategories.index',
        'create'   => 'admin.product.subcategories.create',
        'store'    => 'admin.product.subcategories.store',
        'edit'     => 'admin.product.subcategories.edit',
        'update'   => 'admin.product.subcategories.update'
    ]]);

    /*
     *  product sku Routes
     */
    Route::get('product/skus/{id}/delete','ProductSkuController@delete');
    Route::resource('product/skus', 'ProductSkuController', ['names' => [
        'index'    => 'admin.product.skus.index',
        'create'   => 'admin.product.skus.create',
        'store'    => 'admin.product.skus.store',
        'edit'     => 'admin.product.skus.edit',
        'update'   => 'admin.product.skus.update'
    ]]);

    /*
     * product Routes
     */
    Route::get('products/{id}/delete','ProductController@delete');
    Route::resource('products', 'ProductController', ['names' => [
        'index'     => 'admin.products.index',
        'create'    => 'admin.products.create',
        'store'     => 'admin.products.store',
        'edit'      => 'admin.products.edit',
        'update'    => 'admin.products.update'
    ]]);

    /*
     *  product Brand Routes
     */
    Route::get('product/brands/{id}/delete','ProductBrandController@delete');
    Route::resource('product/brands', 'ProductBrandController', ['names' => [
        'index'    => 'admin.product.brands.index',
        'create'   => 'admin.product.brands.create',
        'store'    => 'admin.product.brands.store',
        'edit'     => 'admin.product.brands.edit',
        'update'   => 'admin.product.brands.update'
    ]]);


});

Route::group(['module' => 'Product', 'middleware' => ['web'], 'namespace' => 'App\Modules\Product\Controllers\Frontend'], function() {

    /*
      * Product related routes
    */
    Route::get('search-products', 'ProductController@searchProducts');
    Route::post('load-more/products', 'ProductController@loadMoreProducts');//front-end load-more product
    Route::get('product-details/{productId}', 'ProductController@productDetail');
    Route::get('/categories/{categoryId}','CategoryController@index');
    Route::get('/subcategories/{subcategoryId}','SubcategoryController@index');

    /*
      * Cart routes
     */
    Route::get('cart', 'CartController@index');
    Route::post('add-to-cart','CartController@addToCart');
    Route::post('add-to-cart/from-wishlist','CartController@addToCartFromWishlist');
    Route::post('update-cart-items','CartController@updateCartItem');
    Route::get('remove-items/{productId}','CartController@removeItem');
    Route::get('/quick-view/{productId}','ProductController@quickView');

    /*
      * Wishlist routes
     */
    Route::get('wishlist','WishListController@index');
    Route::post('add-to-wishlist/{productId}','WishListController@addToWishList');
    Route::post('remove-wishlist-item/{wishlistId}','WishListController@removeWishlistItem');


    Route::get('/brand','BrandController@index');
    Route::get('/checkout','CheckoutController@index');
    Route::post('/update-shipping-address','CheckoutController@updateShippingAddress');

});
