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
use App\Jobs\SendOrderEmail;

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
        $status = '';
        $message = '';

        if (Coingate::testConnection()) { // In case of coingate failure, let's show a message to user
            $order = new Order();
            $order->create(['user_id' => Auth::user()->id,
                            'request' => $request,
                            'receive_currency' => $this->receiveCurrency,
                            'fee' => $this->coingateFee]);


            $orderParams = $this->prepParams(['order' => $order,
                                                'amount' => $request->amount]);

            try {
                $coingateOrder = MerchantOrder::create($orderParams);

                if ($coingateOrder) {
                    $order = Order::findOrFail($order->id);
                    $order->pending([   'id' => $coingateOrder->id,
                                        'url' => $coingateOrder->payment_url]);

                    $url = $coingateOrder->payment_url;
                } else {
                    $order = Order::findOrFail($order->id);
                    $order->failed();

                    $status = 'canceled';
                    $message = 'Our provider rejected your order. Please contact our support.';

                    Flash::create('error', $message);

                    $url = route('dashboard.index');
                }
            } catch (\Coingate\ApiError $e) {
                $order = Order::findOrFail($order->id);
                $order->failed();

                $status = 'canceled';
                $message = 'Something went wrong with our provider, please contact our support.';

                Flash::create('error', $message);
                $url = route('dashboard.index');
            }
        } else {

            $status = 'canceled';
            $message = 'We are having issues with our provider. Please try again.';

            Flash::create('error', $message);
            $url = route('dashboard.index');
        }

        if($status === '') { $status = 'placed'; }


        dispatch(new SendOrderEmail(Auth::user(), $status, $message));

        return redirect($url);
    }

    public function callback(string $hash, OrderCallbackRequest $request): bool
    {

        $order = Order::where('coingate_id', $request->id)->where('hash', $hash)->first();

        if ($order) {

            $order->paid(['request' => $request,
                          'token_price' => $this->tokenPrice,
                          'bonus' => $this->bonusPercentage]);


            $raw = json_encode($request->all());
            $response = new \App\Response();
            $response->create([ 'coingate_id' => $request->id,
                                'order_id' => $request->order_id,
                                'response' => $raw]);
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
           'cancel_url' => route('payment.cancel', ['order_id' => $data['order']->hash]),
           'success_url' => route('payment.success', ['order_id' => $data['order']->hash]),
           'title' => 'Order #' . $data['order']->order_id, // For client
           'description' => 'SWA token purchase.'
       ];
    }


    public function success(string $id)
    {
        Flash::create('success', "Order updated succesfully.");

        $order = Order::where('hash', $id)->first();
        dispatch(new SendOrderEmail($order->user, 'placed', ''));

        return redirect()->route('dashboard.index');
    }

    public function cancel(string $id)
    {
        Flash::create('danger', "Order have been canceled.");

        $order = Order::where('hash', $id)->first();
        dispatch(new SendOrderEmail($order->user, 'canceled', ''));

        return redirect()->route('dashboard.index');
    }
}
