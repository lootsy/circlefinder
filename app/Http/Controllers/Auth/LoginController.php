<?php

namespace App\Http\Controllers\Auth;

use Socialite;
use Auth;
use \App\User;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
     */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function redirectToFacebook()
    {
        return Socialite::with('facebook')->redirect();
    }

    public function getFacebookCallback()
    {
        $data = Socialite::with('facebook')->user();

        $user = User::where('email', $data->email)->first();

        if (!is_null($user)) {
            Auth::login($user);
            
            $user->name = $data->user['name'];
            $user->provider_id = $data->user['id'];

            $user->save();
        } else {
            $user = User::where('provider_id', $data->user['id'])->first();

            if (is_null($user)) {
                $user = new User();
                
                $user->name = $data->user['name'];
                $user->email = $data->email;
                $user->provider_id = $data->user['id'];
                $user->password = Hash::make(str_random(25));

                $user->save();
            }

            Auth::login($user);
        }

        return redirect(route('home'))->with('success', 'Successfully logged in!');
    }
}
