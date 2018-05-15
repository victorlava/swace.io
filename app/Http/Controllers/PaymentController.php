<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use CoinGate\CoinGate;
use CoinGate\Merchant\Order as MerchantOrder;
use App\Order;
use App\Currency;
use App\Flash;
use App\Http\Requests\OrderCallbackRequest;
use App\Http\Requests\StoreOrderRequest;

class PaymentController extends Controller
{
    private $receiveCurrency;
    private $coingateFee;
    private $bonusPercentage;
    private $tokenPrice;

    public function __construct()
    {
        $this->receiveCurrency = env('SWACE_RECEIVE_CURRENCY');
        $this->coingateFee = env('SWACE_COINGATE_FEE'); // Percentage;
        $this->bonusPercentage = env('SWACE_BONUS_PERCENTAGE');
        $this->tokenPrice = env('SWACE_TOKEN_PRICE');
    }

    private function coingateConfig()
    {
        CoinGate::config(array(
            'environment' => env('COINGATE_ENVIRONMENT'),
            'auth_token' => env('COINGATE_TOKEN')
        ));
    }

    public function store(StoreOrderRequest $request): \Illuminate\Http\RedirectResponse
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

                Flash::create('error', 'Our provider rejected your order. Please contact our support.');
                $url = route('dashboard.index');
            }
        } else {
            Flash::create('error', 'We are having issues with our provider. Please try again.');
            $url = route('dashboard.index');
        }

        return redirect($url);
    }

    public function callback(string $hash, OrderCallbackRequest $request): bool
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
           'order_id' => (string)$data['order']->order_id,
           'price_amount' => (float)$data['amount'],
           'price_currency' => strtoupper($data['order']->type->short_title),
           'receive_currency' => $this->receiveCurrency,
           'callback_url' => route('payment.callback', $data['order']->hash),
           'cancel_url' => route('payment.cancel', ['order_id' => $data['order']->order_id]),
           'success_url' => route('payment.success', ['order_id' => $data['order']->order_id]),
           'title' => 'Order #' . $data['order']->order_id, // For client
           'description' => 'SWA token purchase.'
       ];
    }


    public function success(string $id)
    {
        Flash::create('success', "Order #$id created succesfully.");

        return redirect()->route('dashboard.index');
    }

    public function cancel(string $id)
    {
        Flash::create('danger', "Order #$id have been canceled.");

        return redirect()->route('dashboard.index');
    }
}
