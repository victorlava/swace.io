<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use CoinGate\CoinGate;
use App\Order;
use App\Currency;

class PaymentController extends Controller
{
    private $receiveCurrency;
    private $coingateFee;
    private $bonusPercentage;
    private $tokenPrice;

    public function __construct()
    {
        $this->receiveCurrency = 'USD';
        $this->coingateFee = 1; // Percentage;
        $this->bonusPercentage = env('BONUS_PERCENTAGE');
        $this->tokenPrice = env('TOKEN_PRICE');
    }

    private function connectCoingate()
    {
        CoinGate::config(array(
            'environment' => env('COINGATE_ENVIRONMENT'),
            'app_id' => env('COINGATE_APP_ID'),
            'api_key' => env('COINGATE_KEY'),
            'api_secret' => env('COINGATE_SECRET')
        ));
    }

    public function store(Request $request): \Illuminate\Http\RedirectResponse
    {

        // do basic validation to prevent spam

        $order = new Order();
        $order->order_id = $order->generateID();
        $order->currency_id = (int)$request->currency;
        $order->amount = $request->amount;
        $order->rate = $order->calcRate($this->receiveCurrency);
        $order->gross = $order->calcGross();
        $order->fee = $order->calcFee($this->coingateFee);
        $order->setStatus('pending');
        $order->user_id = Auth::user()->id;
        $order->hash = md5($order->order_id);
        $order->save();

        $this->connectCoingate();

        $post_params = array(
           'order_id' => $order->id,
           'price' => $request->amount,
           'currency' => $order->type->short_title,
           'receive_currency' => 'USD',
           'callback_url' => route('payment.callback', $order->hash),
           'cancel_url' => route('payment.cancel', ['order_id' => $order->order_id]),
           'success_url' => route('payment.success', ['order_id' => $order->order_id]),
           'title' => 'Order #' . $order->order_id, // For client
           'description' => 'SWA token purchase.'
        );

        $cgOrder = \CoinGate\Merchant\Order::create($post_params);

        if ($cgOrder) {
            $order = Order::find($order->id);
            $order->coingate_id = $cgOrder->id;
            $order->invoice = $cgOrder->payment_url;
            $order->setStatus('pending');
            $order->save();

            $url = $cgOrder->payment_url;
        } else {
            $order = Order::find($order->id);
            $order->setStatus('failed');
            $order->save();

            // Create flash message with failed order message
            $url = route('dashboard.index');
        }

        return redirect($url);
    }

    public function callback(string $hash, Request $request): bool
    {
        $order = Order::where('coingate_id', $request->id)->where('hash', $hash)->first();

        if ($order) {
            $order->net = $request->receive_amount;
            $order->tokens = $order->calcTokens($this->tokenPrice, $this->bonusPercentage);
            $order->bonus = $order->calcBonus($order->tokens, $this->bonusPercentage);
            $order->setStatus($request->status);
            $order->hash = null; // Remove hash to save space
            $order->save();
        }

        return true;
    }


    public function success(int $order_id)
    {
        return view('payment.success', ['order_id' => $order_id]);
    }

    public function cancel(int $order_id)
    {
        return view('payment.cancel', ['order_id' => $order_id]);
    }
}
