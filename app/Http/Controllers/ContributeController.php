<?php

namespace App\Http\Controllers;

use App\Email;
use Illuminate\Http\Request;

class ContributeController extends Controller
{
    public function create()
    {
        return view('pages/contribute');
    }

    public function store(Request $request)
    {

        // Checking two things:
        // 1. is email registered for the contribution
        // 2. is email registered for the user dashboard (ICO)
        $request->validate([
            'email' => 'required|email|unique:emails,email|unique:users,email',
        ]);

        $email = new Email();
        $email->email = $request->email;
        $email->token = csrf_token();
        $email->save();

        $request->session()->flash('email', $request->email);

        return redirect()->route('register');
    }
}
