<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use CoinGate\CoinGate;
use CoinGate\Merchant\Order as MerchantOrder;
use App\Order;
use App\Currency;
use App\Http\Requests\OrderCallback;
use App\Http\Requests\StoreOrder;

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

    private function coingateConfig()
    {
        CoinGate::config(array(
            'environment' => env('COINGATE_ENVIRONMENT'),
            'app_id' => env('COINGATE_APP_ID'),
            'api_key' => env('COINGATE_KEY'),
            'api_secret' => env('COINGATE_SECRET')
        ));
    }

    public function store(StoreOrder $request): \Illuminate\Http\RedirectResponse
    {
        $this->coingateConfig();

        if (Coingate::testConnection()) { // In case of coingate failure, let's show a message to user
            $order = new Order();
            $order->create(['user_id' => Auth::user()->id,
                            'request' => $request,
                            'receive_currency' => $this->receiveCurrency,
                            'fee' => $this->coingateFee]);


            $orderParams = $this->prepParams(['order' => $order,
                                                'amount' => $request->amount]);

            $coingateOrder = MerchantOrder::create($orderParams);

            if ($coingateOrder) {
                $order = Order::findOrFail($order->id);
                $order->pending([   'id' => $coingateOrder->id,
                                    'url' => $coingateOrder->payment_url]);

                $url = $coingateOrder->payment_url;
            } else {
                $order = Order::findOrFail($order->id);
                $order->failed();

                // Create flash message with failed order message
                $url = route('dashboard.index');
            }
        } else {
            // Flash message: our provider is not available,
            // contact support
            $url = route('dashboard.index');
        }

        return redirect($url);
    }

    public function callback(string $hash, OrderCallback $request): bool
    {
        $order = Order::where('coingate_id', $request->id)->where('hash', $hash)->first();

        if ($order) {
            $order->paid(['request' => $request,
                          'token_price' => $this->tokenPrice,
                          'bonus' => $this->bonusPercentage]);
        }

        return true;
    }

    private function prepParams(array $data): array
    {
        return $preparedParams = [
           'order_id' => $data['order']->id,
           'price' => $data['amount'],
           'currency' => $data['order']->type->short_title,
           'receive_currency' => 'USD',
           'callback_url' => route('payment.callback', $data['order']->hash),
           'cancel_url' => route('payment.cancel', ['order_id' => $data['order']->order_id]),
           'success_url' => route('payment.success', ['order_id' => $data['order']->order_id]),
           'title' => 'Order #' . $data['order']->order_id, // For client
           'description' => 'SWA token purchase.'
       ];
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
