<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Jobs\SendPasswordChangedEmail;
use App\User;
use App\Flash;
use Camroncade\Timezone\Timezone as Timezone;

class ProfileController extends Controller
{
    public function index()
    {

        $timezone = new Timezone();

        $user = Auth::user();
        $first_name = $user->first_name;
        $last_name = $user->last_name;
        $email = $user->email;
        $mobile = $user->phone;
        $personal = $user->personal;
        $company_name = $user->company_name;
        $company_code = $user->company_code;
        $company_vat = $user->company_vat;
        $company_address = $user->company_address;
        $current_timezone = $user->timezone;

        return view('dashboard.profile', [  'first_name' => $first_name,
                                            'last_name' => $last_name,
                                            'email' => $email,
                                            'mobile' => $mobile,
                                            'personal' => $personal,
                                            'company_name' => $company_name,
                                            'company_code' => $company_code,
                                            'company_vat' => $company_vat,
                                            'company_address' => $company_address,
                                            'timezone' => $timezone,
                                            'current_timezone' => $current_timezone,
                                            'disabled' => $user->disableInput()]);
    }

    public function store(Request $request)
    {
        // Default rule, when KYC is passed
        $rules = ['password' => 'string|nullable|confirmed|regex:^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)[a-zA-Z\d]{8,}$^'];
        $rules['company_name'] = 'required|max:255';
        $rules['company_code'] = 'required|integer';
        $rules['company_vat'] = 'nullable|integer';
        $rules['company_address'] = 'required|max:255';
        $rules['timezone'] = 'required|max:60|alpha-dash';


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

        $user->company_name = $request->get('company_name');
        $user->company_code = $request->get('company_code');
        $user->company_vat = $request->get('company_vat');
        $user->company_address = $request->get('company_address');
        $user->timezone = $request->get('timezone');

        if ($request->get('password') !== null) {
            $user->password = \Hash::make($request->get('password'));
            dispatch(new SendPasswordChangedEmail($user, request()->ip()));
        }

        $user->save();

        Flash::create('success', 'Your profile data updated successfully.');

        return redirect()->route('dashboard.index');
    }
}
