<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });


Route::group(['middleware' => 'guest','namespace' => 'Auth'],function() {
    Route::post('register','RegisterController@register')->name('register');
    Route::post('login','LoginController@login')->name('login');
    Route::get('logout','LoginController@logout')->name('logout');
    Route::get('/authorization-checker/domain/{domain}','AuthorizationChecker@domainAuthorizationChecker')->where('doamin','\d+');
    Route::get('/authorization-checker/menu/{menu}','AuthorizationChecker@menuAuthorizationChecker')->where('menu','\d+');
});

Route::get('home/index','HomeController@homeIndex')->name('index');
Route::get('check','HomeController@checking');

Route::Group(['prefix' => 'domain','as' => 'domain'],function() {
    Route::get('show/{domain}','DomainController@show')->name('show')->where('domain','\d+');
    Route::get('menu/{domain}','DomainController@getDomainMenu')->name('getDomainMenu')->where('domain','\d+');
    Route::get('menuClass/{domain}','DomainController@getDomainMenuClass')->name('getDomainMenuClass')->where('domain','\d+');
    Route::get('index/{domain}','DomainController@index')->name('index')->where('domain','\d+');
    Route::post('store','DomainController@store')->name('store');
    Route::post('update/{domain}','DomainController@updateDomain')->name('update')->where('domain','\d+');
    Route::get('edit/{domain}','DomainController@edit')->name('edit')->where('domain','\d+');
    Route::delete('delete/{domain}','DomainController@destroy')->name('delete')->where('domain','\d+');
    Route::get("/",'DomainController@domains')->name('domains');
});

Route::Group(['prefix' => 'menu', 'as' =>'menu'], function() {
    Route::get('index/{menu}','MenuController@index')->name('index')->where('menu','\d+');
    Route::get('show/{menu}','MenuController@show')->name('show')->where('menu','\d+');
    Route::get('menu-class/{menu}','MenuController@getMenuClass')->name('getMenuClass')->where('menu','\d+');
    Route::post('store/{domain}','MenuController@store')->name('store')->where('domain','\d+');
    Route::post('update/{menu}','MenuController@update')->name('update')->where('menu','\d+');
    Route::get('edit/{menu}','MenuController@edit')->name('edit')->where('menu','\d+');
    Route::delete('delete/{menu}','MenuController@destroy')->name('delete')->where('menu','\d+');
});

Route::Group(['prefix' => 'menu-class', 'as' =>'menu-class'], function() {
    Route::get('index/{menuClass}','MenuClassController@index')->name('index')->where('menuClass','\d+');
    Route::get('show/{menuClass}','MenuClassController@show')->name('show')->where('menuClass','\d+');
    Route::post('store/{menu}','MenuClassController@store')->name('store')->where('menu','\d+');
    Route::post('update/{menuClass}','MenuClassController@update')->name('update')->where('menuClass','\d+');
    Route::get('edit/{menuClass}','MenuClassController@edit')->name('edit')->where('menuClass','\d+');
    Route::delete('delete/{menuClass}','MenuClassController@destroy')->name('delete')->where('menuClass','\d+');
});

Route::Group(['prefix' => 'product', 'as' =>'product'], function() {
    Route::get('index/{product}','ProductController@index')->name('index')->where('product','\d+');
    Route::get('show/{product}','ProductController@show')->name('show')->where('product','\d+');
    Route::post('store/{domain}','ProductController@store')->name('store')->where('domain','\d+');
    Route::post('update-price/{product}','ProductController@updatePrice')->name('updatePrice')->where('product','\d+');
    Route::post('update/{product}','ProductController@update')->name('update')->where('product','\d+');
    Route::get('edit/{product}','ProductController@edit')->name('edit')->where('product','\d+');
    Route::delete('delete/{product}','ProductController@destroy')->name('delete')->where('product','\d+');
    Route::get('order/{product}','ProductController@productOrder')->name('product.order')->where('product','\d+');
});

Route::Group(['prefix' => 'order', 'as' =>'cart'], function() {
    Route::get('index/{domain}','CustomerController@getDomainOrder')->name('getOrder')->where('domain','\d+');
    Route::get('history/{domain}','CustomerController@getDomainOrderHistory')->name('getOrderHistory')->where('domain','\d+');
    Route::get('index','CustomerController@getOrder')->name('getOrder');
    Route::post('store','CustomerController@setOrder')->name('setOrder');
    Route::get("status/{order}/{status}","CustomerController@changeOrderStatus")->name("changeOrderStatus")->where(['order'=>'\d+','status'=>'[a-z]+']);
    Route::get('history','CustomerController@getOrderHistory')->name('getOrderHistory');
    Route::get('admin/status/{order}/{status}','CustomerController@changeAdminOrderStatus')->name('changeAdminOrderStatus')->where(['order'=>'\d+','status'=>'[a-z]+']);
});
