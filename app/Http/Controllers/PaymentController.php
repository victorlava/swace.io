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

    public function __construct()
    {
        $this->receiveCurrency = 'USD';
        $this->coingateFee = 1; // Percentage;
        $this->bonusPercentage = 30;
    }

    private function connect()
    {
        CoinGate::config(array(
            'environment' => env('COINGATE_ENVIRONMENT'),
            'app_id' => env('COINGATE_APP_ID'),
            'api_key' => env('COINGATE_KEY'),
            'api_secret' => env('COINGATE_SECRET')
        ));
    }

    public function store(Request $request)
    {

        // do basic validation to prevent spam

        $orderModel = new Order();
        $orderModel->order_id = rand(100, 10000000); // external
        $orderModel->currency_id = (int)$request->currency;
        $orderModel->amount = $request->amount;
        $orderModel->rate = $orderModel->calcRate($this->receiveCurrency);
        $orderModel->gross = $orderModel->calcGross();
        $orderModel->fee = $orderModel->calcFee($this->coingateFee);
        $orderModel->user_id = Auth::user()->id;
        $orderModel->save();

        $this->connect();

        $token = 'need to generate token here';
        $currency = Currency::findOrFail($request->currency);
        $tokenAmount = 21;

        $post_params = array(
           'order_id' => $orderModel->id,
           'price' => $request->amount,
           'currency' => $currency->short_title,
           'receive_currency' => 'USD',
           'callback_url' => route('payment.callback', $token),
           'cancel_url' => route('payment.cancel', ['order_id' => $orderModel->order_id]),
           'success_url' => route('payment.success', ['order_id' => $orderModel->order_id]),
           'title' => 'Order #' . $orderModel->order_id, // For client
           'description' => 'SWA token purchase.'
        );

        $order = \CoinGate\Merchant\Order::create($post_params);

        if ($order) {
            $orderModel = Order::find($orderModel->id);
            $orderModel->coingate_id = $order->id;
            $orderModel->invoice = $order->payment_url;
            $orderModel->setStatus('pending');
            $orderModel->save();

            dd($order);

            $url = $order->payment_url;
        } else {
            $orderModel = Order::find($orderModel->id);
            $orderModel->setStatus('failed');
            $orderModel->save();

            // Create flash message with failed order message
            $url = route('dashboard.index');
        }

        return redirect($url);
    }

    public function callback(string $token, Request $request)
    {
        // Calculations

        $order = Order::where('id', $request->order_id)->first();
        $order->net = $request->receive_amount;
        $order->tokens = $order->calcTokens($this->tokenPrice, $this->bonusPercentage);
        $order->bonus = $this->bonusPercentage;
        $order->setStatus($request->status);
        $order->save();
    }


    public function success(int $order_id, int $token_amount)
    {
        return view('payment.success', ['order_id' => $order_id,
                                        'token_amount' => $token_amount]);
    }

    public function cancel(int $order_id, int $token_amount)
    {
        return view('payment.cancel', ['order_id' => $order_id,
                                        'token_amount' => $token_amount]);
    }
}
