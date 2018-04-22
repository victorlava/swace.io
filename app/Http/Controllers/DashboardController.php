<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use CoinGate\CoinGate as Gateway;
use App\Currency;
use App\Order;
use App\Sale;

class DashboardController extends Controller
{

    public $token_price;
    public $sale_amount;

    public function __construct() {

        $this->token_price = env('TOKEN_PRICE');
        $this->sale_amount = env('SALE_AMOUNT');

    }

    /* By default shows transaction history */
    public function index() {

        $verified = (Auth::user()->verified) ? true : false;
        $orders = Order::where('user_id', Auth::user()->id)->orderBy('created_at', 'desc')->get();
        $collected = Sale::latest()->first();
        $percentage = (100 * $collected->amount) / (int)$this->sale_amount;
        // dd($this->sale_amount);

        return view('dashboard/index', ['verified' => $verified,
                                        'orders' => $orders,
                                        'sale' => $this->sale_amount,
                                        'collected' => $collected->amount,
                                        'percentage' => $percentage]);

    }

    public function create() {

        $currencies = Currency::all();

        return view('dashboard/create', ['currencies' => $currencies,
                                         'token_price' => $this->token_price]);

    }


}
