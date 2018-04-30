<?php

namespace App\Http\Controllers\Auth;

use App\User;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use App\Jobs\SendVerificationEmail;
use App\Mail\EmailVerification;
use Mail;

// use Job;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
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
        $this->middleware('guest')->except('verify'); // You can verify when you are signed in
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|confirmed|regex:^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)[a-zA-Z\d]{8,}$^',
            'phone' => 'required|max:255',
            'terms' => 'accepted',
            'resident' => 'accepted'
        ], [
            'phone.required' => 'The :attribute field is required. ' . __('Phone field is used to reset your account.'),
            'password.regex' => __('The password field must be atleast 8 characters long, contain atleast one uppercase letter, one lowercase letter and one number.')
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\User
     */
    protected function create(array $data)
    {
        session()->put('email', $data['email']);
        session()->put('confirm_email', __('We have sent you a confirmation link to your ' .
                                                    $data['email'] .
                                                    ' e-mail address. Make sure to confirm your address within 24 hours.'));

        return User::create([
            'first_name' => $data['first_name'],
            'last_name' => $data['last_name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'phone' => $data['phone'],
            'email_token' => base64_encode($data['email'])
        ]);
    }
    /**
    * Handle a registration request for the application.
    *
    * @param \Illuminate\Http\Request $request
    * @return \Illuminate\Http\Response
    */
    public function register(Request $request)
    {
        $this->validator($request->all())->validate();
        event(new Registered($user = $this->create($request->all())));

        Auth::attempt(['email' => $request->email,
                      'password' => $request->password]);
        // Login the user even if he's not verified

        dispatch(new SendVerificationEmail($user));


        return redirect()->route('dashboard.index');
    }

    /**
    * Handle a registration request for the application.
    *
    * @param $token
    */
    public function verify(string $token)
    {
        $user = User::where('email_token', $token)->first();

        if ($user) {
            $user->verify();

            Flash::create(
                'type',
                'Your email is verified, now you can start using the dashboard.'
            );
        } else {

            /* Only show this message if user is logged in and not verified */
            if (Auth::user() && !Auth::user()->is_verified()) {
                Flash::create(
                    'error',
                    'Your email confirmation link has expired, contact our support team to continue.'
                );
            }
        }

        return redirect()->route('dashboard.index');
    }
}
