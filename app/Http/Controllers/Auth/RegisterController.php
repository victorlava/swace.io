<?php

namespace App\Http\Controllers\Auth;

use App\User;
use App\Flash;
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
    protected $redirectTo = '/';

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
            'timezone' => 'nullable|max:60',
            'personal' => 'required|integer|min:0|max:1',
            'company_name' => 'required_if:personal,0|max:255',
            'company_code' => 'required_if:personal,0|nullable|integer',
            'company_vat' => 'nullable',
            'company_address' => 'required_if:personal,0|max:255',
            'company_city' => 'required_if:personal,0|max:255',
            'first_name' => 'required|max:255|regex:/^[\pL\s\-]+$/u',
            'last_name' => 'required|max:255|regex:/^[\pL\s\-]+$/u',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|regex:^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)[a-zA-Z\d\W_]{8,}$^',
            'phone' => 'required|max:255',
            'terms' => 'accepted',
            'resident' => 'accepted'
        ], [
            'company_name.required_if' => 'The company name is required.',
            'company_code.required_if' => 'The company code is required.',
            'company_address.required_if' => 'The company address is required.',
            'company_city.required_if' => 'The company city is required.',
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
            'timezone' => $data['tz'],
            'personal' => $data['personal'],
            'company_name' => $data['company_name'],
            'company_code' => $data['company_code'],
            'company_vat' => $data['company_vat'],
            'company_address' => $data['company_address'],
            'company_city' => $data['company_city'],
            'first_name' => $data['first_name'],
            'last_name' => $data['last_name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'phone' => $data['phone'],
            'email_token' => base64_encode($data['email']),
            'created_at' => date('Y-m-d H:i:s')
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
                'success',
                'Your email is verified, now you can start using the dashboard.'
            );
        } else {

            /* Only show this message if user is logged in and not verified */
            if (Auth::user() && !Auth::user()->isVerified()) {
                Flash::create(
                    'error',
                    'Your email confirmation link has expired, contact our support team to continue.'
                );
            }
        }

        return redirect()->route('dashboard.index');
    }
}
