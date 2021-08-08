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

//POST
Route::post('/register', 'Api\AuthController@register');
Route::post('/register_profile', 'Api\ProfileController@register_profile');
Route::post('/login', 'Api\AuthController@login');
Route::post('/match', 'Api\MatchController@match_found');
Route::post('/photo_up', 'Api\ProfileController@upload_photo');
Route::post('/gift', 'Api\GiftController@give_a_gift');

//GET(GAY)
Route::get('/get_match', 'Api\MatchController@get_match');
Route::get('/get_gift', 'Api\GiftController@get_gift');
Route::get('/get_profile', 'Api\ProfileController@get_profile');
Route::get('/get_rec', 'Api\ProfileController@get_recomendations');
Route::get('/profile_gifts', 'Api\GiftController@profile_gifts');
Route::get('/get_tier_gifts', 'Api\GiftController@get_tier');
Route::get('/lute_box', 'Api\GiftController@lutebox');

