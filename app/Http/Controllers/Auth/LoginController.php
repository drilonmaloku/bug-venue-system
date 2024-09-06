<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

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
    protected $redirectTo = '/dashboard';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }


    /**
     * Override the credentials method to allow login with either username or email.
     *
     * @param Request $request
     * @return array
     */
    protected function credentials(Request $request)
    {
        // Determine if the input is an email or username
        $login = $request->input('email');
        $fieldType = filter_var($login, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';

        // Return credentials for the login attempt
        return [
            $fieldType => $login,
            'password' => $request->input('password'),
        ];
    }


    protected function authenticated(Request $request, User $user)
    {
        if (!$user->isLocationEnabled()) {
            $this->guard()->logout();
            throw ValidationException::withMessages([
                'location_disabled' => "Ky perdorues eshte bere disable",
            ]);
        }

        return redirect()->intended($this->redirectPath());
    }
}
