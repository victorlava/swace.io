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

    public function authenticate(Request $request) {


        // Rule::unique('users')->where(function ($query) {
        //     // echo 'sdf';
        //     return $query->where('email', 'sveiki@viktoraslava.lt');
        // });

        // $email = $request->email;
        // // dd($email);
        //
        // $validator = Validator::make($request->all(), [
        //                 'email' => [
        //                     'required',
        //                     Rule::unique('users')->where(function ($query) {
        //                         // echo 'sdf';
        //                         // dd($this);
        //                         return $query->where('email', $request->input('email'));
        //                     }),
        //                 ],
        //             ]);
        // dd($validator->errors());

        $validator = Validator::make($request->all(), [
            'email' => 'required|email|max:254',
            'password' => 'required',
        ]);

        $validator->email = $request->email;
        $validator->password = $request->password;

        $validator->after(function ($validator) {
            // dd($validator);
            if (!Auth::attempt(['email' => $validator->email, 'password' => $validator->password])) {
                $validator->errors()->add('email', 'Something is wrong with this field!');
            }
            else {
                // return redirect()->route('dashboard');
            }
        });
        //
        // // $error = '';
        //
        // if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
        //     // Authentication passed...
        //     return redirect()->route('dashboard');
        // }
        //
        // // if ($validator->fails()) {
        // //     return view('auth.login', ['recaptcha' => env('RECAPTCHA_SITE_KEY')])->withErrors($validator);
        // // }
        //
        return view('auth.login', ['recaptcha' => env('RECAPTCHA_SITE_KEY')])->withErrors($validator);
    }

    public function showLoginForm() {
        return view('auth.login', ['recaptcha' => env('RECAPTCHA_SITE_KEY') ]);
    }
}
