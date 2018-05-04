<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function index()
    {
        $users = User::all();

        return view('admin.index', ['users' => $users]);
    }
}
