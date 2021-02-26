<?php

namespace App\Http\Controllers\Auth;

use App\User;
use Exception;
use App\Http\Controllers\Controller;
use App\OAuthProvider;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use App\Admin;

class LoginController extends Controller
{

    public function __construct()
    {
        $this->middleware('guest');
    }

    protected $providers = [
        'github', 'facebook', 'google', 'twitter'
    ];

    public function show()
    {
    }

    public function redirectToProvider($driver)
    {
        if (!$this->isProviderAllowed($driver)) {
            return $this->sendFailedResponse("{$driver} is not currently supported");
        }

        try {
            return Socialite::driver($driver)->stateless()->redirect()->getTargetUrl();
        } catch (Exception $e) {
            // You should show something simple fail message
            return $this->sendFailedResponse($e->getMessage());
        }
    }





    // public function handleProviderCallback($driver)
    // {
        // $user = Socialite::driver('facebook')->stateless()->user();
    //     $user = Socialite::driver('facebook')->scopes(['public_profile', 'email'])->asPopup()->redirect();
    //     return $user->token;
    // }



    // protected function sendSuccessResponse($token)
    // {
    //     return response()->json([
    //         'access_token' => $token->accessToken,
    //         'token_type' => 'Bearer',
    //         'expires_at' => Carbon::parse(
    //             $token->token->expires_at
    //         )->toDateTimeString()
    //     ]);
    // }

    // protected function sendFailedResponse($msg = null)
    // {
    //     return redirect()->route('social.login')
    //         ->withErrors(['msg' => $msg ?: 'Unable to login, try with another provider to login.']);
    // }

    protected function loginOrCreateAccount($providerUser, $driver)
    {




        $checking = Admin::where('email', $providerUser->getEmail())->first();

     
            $request = request();
            Auth::login($checking, true);
            $user = $request->user();
            $tokenResult = $user->createToken('Personal Access Token');
            $token = $tokenResult->token;
            $token->save();


            return $tokenResult;
        
      
    }

    // private function isProviderAllowed($driver)
    // {
    //     return in_array($driver, $this->providers) && config()->has("services.{$driver}");
    // }

    public function handleProviderCallback($provider)
    {
        $user = Socialite::driver($provider)->stateless()->user();
        $result = $this->loginOrCreateAccount($user, $user);
        // dd($user);
        // $this->guard()->setToken(
        //     $token = $this->guard()->login($user)
        // );

        return view('oauth/callback', [
            'access_token' => $result->accessToken,
            'token_type' => 'Bearer',
            'expires_at' => Carbon::parse(
                $result->token->expires_at
            )->toDateTimeString()
        ]);
    }

    /**
     * @param  string $provider
     * @param  \Laravel\Socialite\Contracts\User $sUser
     * @return \App\User|false
     */
    // protected function findOrCreateUser($provider, $user)
    // {
    //     OAuth
    //     $oauthProvider = OAuthProvider::where('provider', $provider)
    //         ->where('provider_user_id', $user->getId())
    //         ->first();

    //     if ($oauthProvider) {
    //         $oauthProvider->update([
    //             'access_token' => $user->token,
    //             'refresh_token' => $user->refreshToken,
    //         ]);

    //         return $oauthProvider->user;
    //     }

    //     // if (User::where('email', $user->getEmail())->exists()) {
    //     //     throw new EmailTakenException;
    //     // }

    //     return $this->createUser($provider, $user);
    // }

    /**
     * @param  string $provider
     * @param  \Laravel\Socialite\Contracts\User $sUser
     * @return \App\User
     */
    protected function createUser($provider, $sUser)
    {
        $user = User::create([
            'name' => $sUser->getName(),
            'email' => $sUser->getEmail(),
            'email_verified_at' => now(),
        ]);

        $user->oauthProviders()->create([
            'provider' => $provider,
            'provider_user_id' => $sUser->getId(),
            'access_token' => $sUser->token,
            'refresh_token' => $sUser->refreshToken,
        ]);

        return $user;
    }
}
