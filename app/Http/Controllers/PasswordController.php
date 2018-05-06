<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\User;
use App\Flash;

class PasswordController extends Controller
{
    public function index()
    {
        return view('dashboard.password');
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'password' => 'required|string|confirmed|regex:^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)[a-zA-Z\d]{8,}$^'
        ]);

        $id = Auth::user()->id;

        $user = User::where('id', $id)->first();
        $user->password = \Hash::make($request->get('password'));
        $user->save();

        Flash::create('success', 'Your password updated succesfully.');

        return redirect()->route('profile.password.index');
    }
}
