<?php

namespace App\Http\Controllers\Admin\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * Source: http://leogo.es/3u8k4
 */
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
    protected $redirectTo = '/admin';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest:admin')->except('logout');
    }

    public function showLoginForm()
    {
        return view('admin.auth.login');
    }

    public function login(Request $request)
    {
        # TODO: Move the validation to the model or policy
        $this->validate($request, [
            'email' => 'required|email',
            'password' => 'required|min:6',
        ]);

        if (Auth::guard('admin')->attempt(
            ['email' => $request->email,
                'password' => $request->password],
            $request->remember
        )) {
            return redirect()->intended(route('admin.dashboard'));
        }

        return redirect()->back()
            ->withInput($request->only('email', 'remember'))
            ->withErrors(['message' => 'Wrong username or password!']);
    }

    public function logout()
    {
        Auth::guard('admin')->logout();

        return redirect(route('admin.login'));
    }
}
