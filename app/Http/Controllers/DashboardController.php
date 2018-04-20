<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use CoinGate\CoinGate as Gateway;

class DashboardController extends Controller
{

    public $token_price;

    public function __construct() {

        $this->token_price = env('TOKEN_PRICE');

    }

    /* By default shows transaction history */
    public function index() {

        $verified = (Auth::user()->verified) ? true : false;

        return view('dashboard/index', ['verified' => $verified]);

    }

    public function create() {


        return view('dashboard/create', ['token_price' => $this->token_price]);

    }


}
