<?php

namespace App\Http\Controllers\Auth;

use App\User;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
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
        $this->middleware('guest');
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
    public function register(Request $request) {
        $this->validator($request->all())->validate();
        event(new Registered($user = $this->create($request->all())));

        // $email = new EmailVerification($user);
        // Mail::to($user->email)->send($email);

        dispatch(new SendVerificationEmail($user));

        return redirect()->route('contribute.create');
        // return view('verification');
    }

    /**
    * Handle a registration request for the application.
    *
    * @param $token
    * @return \Illuminate\Http\Response
    */
    public function verify($token) {
        $user = User::where('email_token',$token)->first();
        $user->verified = 1;
        if($user->save()){
            // Send to dashboard, add success message
            // return view('emailconfirm',['user'=>$user]);
        }
        else {
            // Send to dashboard, add error message
        }
    }
}
