<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Laravel\Socialite\Facades\Socialite;

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
Route::post('oauth/facebook', function () {

    $url = Socialite::driver('facebook')// это для реддита
    ->stateless()
    ->redirect()
    ->getTargetUrl();
    // $url = Socialite::driver('facebook')->scopes(['public_profile', 'email'])->asPopup()->redirect()->getTargetUrl();

    return response()->json([
        'url' => $url
    ]);
});

// Route::post('oauth/facebook/callback', ;

Route::middleware('auth:admin')->get('/user', function (Request $request) {
    return $request->user();
});
Route::middleware('auth:brand')->get('/brand', function (Request $request) {
    return $request->user();
});


