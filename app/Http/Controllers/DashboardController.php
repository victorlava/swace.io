<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    /* By default shows transaction history */
    public function index() {

        $verified = (Auth::user()->verified) ? true : false;

        return view('dashboard/index', ['verified' => $verified]);

    }

    public function test() {
        echo 'sdfs';
    }


}
