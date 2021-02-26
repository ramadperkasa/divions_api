<?php

use App\Notifications\Telegram;
use App\Notifications\Twitter;
use App\User;
use Illuminate\Support\Facades\Route;
use Laravel\Socialite\Facades\Socialite;
use NotificationChannels\Twitter\TwitterChannel;
use NotificationChannels\Telegram\TelegramChannel;
use NotificationChannels\Telegram\Facebook;
use NotificationChannels\FacebookPoster\FacebookPosterChannel;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/




Route::get('auth/social', 'Auth\LoginController@show')->name('social.login');
// Route::get('oauth/{driver}', 'Auth\LoginController@redirectToProvider')->name('social.oauth');
Route::get('oauth/{driver}/callback', 'Auth\LoginController@handleProviderCallback')->name('social.callback');




// Route::get('/', function () {
// Notification::route()->notify(new Twitter);
// Notification::send(['sinopsis' => 'sinopsis'], new Twitter);
// Notification::route(TwitterChannel::class, ['sinopsis' => 'email'])->notify(new Twitter(['sinopsis' => 'email']));
// Notification::route(TelegramChannel::class, '')->notify(new App\Notifications\Telegram);
// Notification::route(FacebookPosterChannel::class, '')->notify(new App\Notifications\FacebookPoster);
//     return view('welcome');
// });
Route::get('/', function () {
    return "Connected";
});
Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
