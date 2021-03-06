<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group(['prefix' => 'api'], function () {
    //Route::post('/login', [AuthController::class, 'login']);
    //Route::post('/register', [AuthController::class, 'register']); 

    Route::post('login', array("as" => "login.login", 'uses' => 'JWTAuthController@login'));
    Route::post('register', array("as" => "register.register", 'uses' => 'JWTAuthController@register'));
    
});
Route::group(['middleware'=>'auth:api','prefix' => 'api'], function () {
    Route::post('logout', array("as" => "logout.logout", 'uses' => 'JWTAuthController@logout'));    
    Route::get('user', array("as" => "user-profile.user_profile", 'uses' => 'JWTAuthController@user_profile')); 
    Route::get('user', array("as" => "user-profile.user_profile", 'uses' => 'JWTAuthController@user_profile'));

    Route::apiResource('product', 'ProductController');
    Route::get('product/{product}/edit', 'ProductController@edit',['except' => ['create']]); 

    Route::get('validate-token', array("as" => "validate-token.verify_token", 'uses' => 'JWTAuthController@verify_token'));
    
});

