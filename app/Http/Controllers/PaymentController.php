<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use CoinGate\CoinGate as Gateway;

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

        $this->connect();

        $post_params = array(
           'order_id'          => 'YOUR-CUSTOM-ORDER-ID-115',
           'price'             => 2050.99,
           'currency'          => 'USD',
           'receive_currency'  => 'EUR',
           'callback_url'      => 'https://example.com/payments/callback?token=6tCENGUYI62ojkuzDPX7Jg',
           'cancel_url'        => 'https://example.com/cart',
           'success_url'       => 'https://example.com/account/orders',
           'title'             => 'Order #112',
           'description'       => 'Apple Iphone 6'
        );

        $order = \CoinGate\Merchant\Order::create($post_params);

        if ($order) {
            dd($order);
            echo $order->status;
        } else {
            # Order Is Not Valid
        }

    }
    
}
