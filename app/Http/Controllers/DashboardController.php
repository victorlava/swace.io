<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use CoinGate\CoinGate as Gateway;
use App\Currency;
use App\Order;

class DashboardController extends Controller
{

    public $token_price;

    public function __construct() {

        $this->token_price = env('TOKEN_PRICE');

    }

    /* By default shows transaction history */
    public function index() {

        $verified = (Auth::user()->verified) ? true : false;
        $orders = Order::where('user_id', Auth::user()->id)->orderBy('created_at', 'desc')->get();

        // dd($transactions);

        return view('dashboard/index', ['verified' => $verified,
                                        'orders' => $orders]);

    }

    public function create() {

        $currencies = Currency::all();

        return view('dashboard/create', ['currencies' => $currencies,
                                         'token_price' => $this->token_price]);

    }


}
