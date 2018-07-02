<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use CoinGate\CoinGate as Gateway;
use Carbon\Carbon;
use App\Currency;
use App\Order;
use App\Sale;

class DashboardController extends Controller
{
    private $meta;

    public function __construct()
    {
        $this->meta = [ 'token_price' => env('SWACE_TOKEN_PRICE'),
                        'sale_amount' => env('SWACE_SALE_AMOUNT'),
                        'bonus_percentage' => env('SWACE_BONUS_PERCENTAGE'),
                        'coingate_fee' => env('SWACE_COINGATE_FEE'),
                        'end_date' => env('SWACE_END_DATE')];
    }

    public function index()
    {
        $currencies = Currency::all();
        $verified = Auth::user()->isVerified();
        $tokens = Auth::user()->tokens();
        $email = Auth::user()->email;
        $orders = Order::where('user_id', Auth::user()->id)->orderBy('created_at', 'desc')->get();
        $token_end_date = Carbon::parse($this->meta['end_date'])
                                ->timezone(Auth::user()->timezone);

        $days_left = $token_end_date->diffInDays(Carbon::now());
        $hours_left = $token_end_date->diffInHours(Carbon::now());
        $minutes_left = $token_end_date->diffInMinutes(Carbon::now());

        $collected = Sale::collectedAmount();
        $percentage = Sale::collectedPercentage($collected, $this->meta['sale_amount']);

        return view('dashboard/index', ['currencies' => $currencies,
                                        'verified' => $verified,
                                        'orders' => $orders,
                                        'collected' => $collected,
                                        'percentage' => $percentage,
                                        'tokens' => $tokens,
                                        'email' => $email,
                                        'token_end_date' => $token_end_date->toDayDateTimeString(),
                                        'days_left' => $days_left,
                                        'hours_left' => $hours_left,
                                        'minutes_left' => $minutes_left,
                                        'meta' => $this->meta]);
    }

    public function json_rates($currency) {

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "https://api.coingate.com/v2/rates/merchant/USD/" . $currency);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $output = curl_exec($ch);
        curl_close($ch);

        echo $output;

    }
}
