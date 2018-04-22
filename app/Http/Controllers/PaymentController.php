<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use CoinGate\CoinGate as Gateway;
use App\Order;
use App\Currency;

class PaymentController extends Controller
{

    private function connect() {
        Gateway::config(array(
          'environment' => env('COINGATE_ENVIRONMENT'),
          'app_id'      => env('COINGATE_APP_ID'),
          'api_key'     => env('COINGATE_KEY'),
          'api_secret'  => env('COINGATE_SECRET')
        ));
    }

    public function store(Request $request) {

        // do basic validation to prevent spam

        $orderModel = new Order();
        $orderModel->currency_id = $request->currency;
        $orderModel->rate = 1.25;
        $orderModel->gross = 222;
        $orderModel->fee = 1.2;
        $orderModel->net = 222;
        $orderModel->tokens = 2000;
        $orderModel->bonus = 20;
        $orderModel->status_id = 1; // Failed by default
        $orderModel->user_id = Auth::user()->id;
        $orderModel->save();

        $this->connect();

        $customOrderID = '32315';
        $token = 'need to generate token here';
        $currency = Currency::findOrFail($request->currency);
        $tokenAmount = 21;

        $post_params = array(
           'order_id'          => $orderModel->id,
           'price'             => $request->amount,
           'currency'          => $currency->short_title,
           'receive_currency'  => 'USD',
           'callback_url'      => route('payment.callback', $token),
           'cancel_url'        => route('payment.cancel', ['order_id' => $customOrderID,
                                                            'token_amount' => $tokenAmount]),
           'success_url'       => route('payment.success', ['order_id' => $customOrderID,
                                                            'token_amount' => $tokenAmount]),
           'title'             => 'Order #' . $orderModel->id,
           'description'       => 'SWA token purchase.'
        );

        $order = \CoinGate\Merchant\Order::create($post_params);

        if ($order) {

            $orderModel = Order::find($orderModel->id);
            $orderModel->coingate_id = $order->id;
            $orderModel->invoice = $order->payment_url;
            $orderModel->status_id = 2; // Pending
            $orderModel->save();

            $url = $order->payment_url;


        } else {

            $orderModel = Order::find($orderModel->id);
            $orderModel->status_id = 1; // Failed
            $orderModel->save();

            // Create flash message with failed order message
            $url = route('dashboard.index');
        }

        return redirect($url);
    }

    public function callback(Request $request) {

    }


    public function success($order_id, $token_amount) {

        return view('payment.success', ['order_id' => $order_id,
                                        'token_amount' => $token_amount]);
    }

    public function cancel($order_id, $token_amount) {
        return view('payment.cancel', ['order_id' => $order_id,
                                        'token_amount' => $token_amount]);
    }


}
