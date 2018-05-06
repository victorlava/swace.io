<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
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
                                            'mobile' => $mobile]);
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'first_name' => 'required|alpha|max:255',
            'last_name' => 'required|alpha|max:255',
        ]);

        $id = Auth::user()->id;

        $user = User::where('id', $id)->first();
        $user->first_name = $request->get('first_name');
        $user->last_name = $request->get('last_name');
        $user->save();

        Flash::create('success', 'Your profile data update succesfully.');

        return redirect()->route('profile.index');
    }
}
