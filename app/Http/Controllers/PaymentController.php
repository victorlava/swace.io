<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use CoinGate\CoinGate as Gateway;
use App\Order as Order;

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
        $orderModel->type = $request->currency;
        $orderModel->rate = 1.25;
        $orderModel->gross = 222;
        $orderModel->fee = 1.2;
        $orderModel->net = 222;
        $orderModel->tokens = 2000;
        $orderModel->bonus = 20;
        $orderModel->status = 'failed';
        $orderModel->invoice = 'link';
        $orderModel->user_id = Auth::user()->id;
        $orderModel->save();

        $this->connect();

        $customOrderID = '32315';
        $token = 'need to generate token here';
        $tokenAmount = 21;

        $post_params = array(
           'order_id'          => $orderModel->id,
           'price'             => $request->amount,
           'currency'          => $request->currency,
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
            // dd($order);
            // echo $order->status;
            dd($order);
            // Create an order in our system
            return redirect($order->payment_url);

        } else {
            # Order Is Not Valid
        }

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
