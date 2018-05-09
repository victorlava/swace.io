<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Jobs\SendPasswordChangedEmail;
use App\User;
use App\Flash;

class ProfileController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $first_name = $user->first_name;
        $last_name = $user->last_name;
        $email = $user->email;
        $mobile = $user->phone;

        return view('dashboard.profile', [  'first_name' => $first_name,
                                            'last_name' => $last_name,
                                            'email' => $email,
                                            'mobile' => $mobile,
                                            'disabled' => $user->disableInput()]);
    }

    public function store(Request $request)
    {
        // Default rule, when KYC is passed
        $rules = ['password' => 'string|nullable|confirmed|regex:^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)[a-zA-Z\d]{8,}$^'];

        // If KYC is not passed yet, then it is possible to change the name
        if (!Auth::user()->isKYC()) {
            $rules['first_name'] = 'required|alpha|max:255';
            $rules['last_name'] = 'required|alpha|max:255';
        }

        $this->validate($request, $rules);

        $id = Auth::user()->id;

        $user = User::where('id', $id)->first();

        if (!Auth::user()->isKYC()) {
            $user->first_name = $request->get('first_name');
            $user->last_name = $request->get('last_name');
        }

        if ($request->get('password') !== null) {
            $user->password = \Hash::make($request->get('password'));
            dispatch(new SendPasswordChangedEmail($user, request()->ip()));
        }

        $user->save();

        Flash::create('success', 'Your profile data update succesfully.');

        return redirect()->route('dashboard.index');
    }
}
