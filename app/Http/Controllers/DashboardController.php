<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /* By default shows transaction history */
    public function index() {

        return view('dashboard/index');

    }

    public function test() {
        echo 'sdfs';
    }


}
