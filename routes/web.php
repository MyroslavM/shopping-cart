<?php
Route::get('/',[
    'uses' => 'ProductController@getIndex',
    'as'   => 'product.index'
    ]);
Route::get('/add-to-cart/{id}',[
    'uses' => 'ProductController@getAddToCart',
    'as'   => 'product.addToCart'
]);
Route::get('/shopping-cart',[
    'uses' => 'ProductController@getCart',
    'as'   => 'product.shoppingCart'
]);
Route::get('/checkout',[
    'uses' => 'ProductController@getCheckout',
    'as'   => 'checkout'
]);
Route::post('/checkout',[
    'uses' => 'UserController@postCheckout',
    'as'   => 'checkout'
]);
Route::group(['prefix' => 'user'], function (){
    Route::group(['middleware' => 'guest'], function (){
        Route::get('/signup',[
            'uses' => 'UserController@getSignup',
            'as'   => 'user.signup'
        ]);
        Route::post('/signup',[
            'uses' => 'UserController@postSignup',
            'as'   => 'user.signup'
        ]);
        Route::get('/signin',[
            'uses' => 'UserController@getSignin',
            'as'   => 'user.signin'
        ]);
        Route::post('/signin',[
            'uses' => 'UserController@postSignin',
            'as'   => 'user.signin'
        ]);
        });
    Route::group(['middleware' => 'auth'], function (){
        Route::get('/profile',[
            'uses' => 'UserController@getProfile',
            'as'   => 'user.profile'
        ]);
        Route::get('/logout',[
            'uses' => 'UserController@getLogout',
            'as'   => 'user.logout'
        ]);
        Route::get('/edit-profile',[
            'uses' => 'UserController@getEditProfile',
            'as'   => 'user.edit-profile'
        ]);
        Route::post('/edit-profile',[
            'uses' => 'UserController@updateProfile',
            'as'   => 'user.update-profile'
        ]);
    });
});


