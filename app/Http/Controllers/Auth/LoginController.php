<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Validation\Rule;
use Validator;

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
    protected $redirectTo = '/';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function authenticate(Request $request)
    {
        $failCounter = session()->get('fail_counter');

        $validator = Validator::make($request->all(), [
            'email' => 'required|email|max:254',
            'password' => 'required',
        ]);

        $validator->email = $request->email;
        $validator->password = $request->password;
        $validator->recaptcha = $request->input('g-recaptcha-response');
        $validator->failCounter = $failCounter;

        $validator->after(function ($validator) {
            $recaptcha = new \ReCaptcha\ReCaptcha(env('RECAPTCHA_SECRET_KEY'));
            $response = $recaptcha->verify($validator->recaptcha, request()->ip()); // Verify recaptcha

            if (!$response->isSuccess() && $validator->failCounter > 2) { // If reCAPTCHA failed then add an error
                $validator->errors()->add('recaptcha', 'reCAPTCHA validation failed, please try again the "I\'m not a robot" test.');
            } else { // If reCAPTCHA succeeded then attempt to authenicate

                if (!Auth::attempt(['email' => $validator->email,
                                    'password' => $validator->password])) { // If authenication fails then add an error

                    $validator->failCounter++; // The fail counter increases only when AUTH is attempted
                    session()->put('fail_counter', $validator->failCounter);

                    $validator->errors()->add('email', 'These credentials do not match our records.');
                }
            }
        });

        if ($validator->fails()) { // If validation failed then show errors on login page
            return redirect()->route('login')->withErrors($validator)->withInput();
        }


        if(Auth::user()->isAdmin()){
            return redirect()->route('admin.index'); 
        }

        return redirect()->route('dashboard.index');
    }

    public function showLoginForm()
    {
        $failCounter = session()->get('fail_counter');

        return view('auth.login', ['recaptcha' => env('RECAPTCHA_SITE_KEY'),
                                    'fail_counter' => $failCounter ]);
    }


}
