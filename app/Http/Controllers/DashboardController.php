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
    private $meta;

    public function __construct()
    {
        $this->meta = [ 'token_price' => env('TOKEN_PRICE'),
                        'sale_amount' => env('SALE_AMOUNT'),
                        'bonus_percentage' => env('BONUS_PERCENTAGE'),
                        'coingate_fee' => env('COINGATE_FEE')];
    }

    public function index()
    {
        $currencies = Currency::all();
        $verified = Auth::user()->isVerified();
        $user_tokens = Auth::user()->tokens();
        $orders = Order::where('user_id', Auth::user()->id)->orderBy('created_at', 'desc')->get();

        $collected = Sale::collectedAmount();
        $percentage = Sale::collectedPercentage($collected, $this->meta['sale_amount']);

        return view('dashboard/index', ['currencies' => $currencies,
                                        'verified' => $verified,
                                        'orders' => $orders,
                                        'collected' => $collected,
                                        'percentage' => $percentage,
                                        'user_tokens' => $user_tokens,
                                        'meta' => $this->meta]);
    }

    public function create()
    {
        $currencies = Currency::all();

        return view('dashboard/create', ['currencies' => $currencies,
                                         'token_price' => $this->token_price]);
    }
}
