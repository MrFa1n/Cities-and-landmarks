<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EmailVerificationController;
use App\Http\Controllers\API\RedirectResponse;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "API" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

// Social
Route::get('/{provider}/auth', 'Auth\SocialController@redirectToProvider')->name('vk.auth')->middleware('web');
Route::get('/{provider}/auth/callback', 'Auth\SocialController@callback')->middleware('web');

/*
    POST запросы
*/
Route::post('/register', 'API\AuthController@register');
Route::post('/register_profile', 'API\ProfileController@register_profile');
Route::post('/login', 'API\AuthController@login');
Route::post('/match', 'API\MatchController@match_found');
Route::post('/photo_up', 'API\ProfileController@upload_photo');
Route::post('/tag_add', 'API\ProfileController@add_hashtag');
Route::post('/gift', 'API\GiftController@give_a_gift');
Route::put('/edit_profile', 'API\ProfileController@update');
// Запрос для верификации мыла - отправка сообщения на мыло пользователя
Route::post('/email/verification-notification', function (Request $request) {
    $request->user()->sendEmailVerificationNotification();
    return ['status' => 'verification-link-sent'];
})->middleware(['auth:api', 'throttle:6,1']);

/*
    GET запросы
*/
Route::get('/get_match', 'API\MatchController@get_match');
Route::get('/get_gift', 'API\GiftController@get_gift');
Route::get('/get_profile', 'API\ProfileController@get_profile');
Route::get('/get_rec', 'API\ProfileController@get_recomendations');
Route::get('/profile_gifts', 'API\GiftController@profile_gifts');
Route::get('/get_tier_gifts', 'API\GiftController@get_tier');
Route::get('/lute_box', 'API\GiftController@lutebox');
Route::get('/get_all_photo_profile', 'API\ProfileController@get_photo');
Route::get('/hello_world', 'API\ProfileController@get_hello');
// Запрос для верификации мыла - подтвержение мыла, нажатие на кнопку верификации пользователем
Route::get('/email/verify/{id}/{hash}', 'API\EmailVerificationController@verify')->middleware('api');
