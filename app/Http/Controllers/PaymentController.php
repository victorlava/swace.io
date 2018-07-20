<?php

namespace App\Http\Controllers;

use App\Flash;
use App\Http\Requests\OrderCallbackRequest;
use App\Http\Requests\StoreOrderRequest;
use App\Jobs\SendOrderEmail;
use App\Order;
use CoinGate\CoinGate;
use CoinGate\Merchant\Order as MerchantOrder;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class PaymentController extends Controller
{
    private $receiveCurrency;
    private $coingateFee;
    private $bonusPercentage;
    private $tokenPrice;
    private $minTokenAmount;
    private $maxTokenAmount;

    public function __construct()
    {
        $this->receiveCurrency = env('SWACE_RECEIVE_CURRENCY');
        $this->coingateFee = env('SWACE_COINGATE_FEE'); // Percentage;
        $this->bonusPercentage = env('SWACE_BONUS_PERCENTAGE');
        $this->tokenPrice = env('SWACE_TOKEN_PRICE');
        $this->minTokenAmount = env('SWACE_MIN_BUY_AMOUNT');
        $this->maxTokenAmount = env('SWACE_MAX_BUY_AMOUNT');

        $this->initCoingateConfig();
    }

    private function initCoingateConfig()
    {
        CoinGate::config(array(
            'environment' => env('COINGATE_ENVIRONMENT'),
            'auth_token' => env('COINGATE_TOKEN')
        ));
    }

    public function store(StoreOrderRequest $request): \Illuminate\Http\RedirectResponse
    {
//        if (!Coingate::testConnection()) {
//            $message = 'We are having issues with our provider. Please try again later.';
//
//            Flash::create('error', $message);
//
//            return redirect(route('dashboard.index'));
//        }

        $order = new Order();
        $order->user()->associate(Auth::user());
        $order->createNew([
            'receive_currency' => $this->receiveCurrency,
            'bonus_percentage' => $this->bonusPercentage,
            'fee' => $this->coingateFee
        ], $request->all());

        try {
            $coingateOrder = MerchantOrder::create(
                $this->prepareProviderParams($order)
            );

            dd($coingateOrder);

            if ($coingateOrder) {
                $order->pending($coingateOrder);

                $url = $coingateOrder->payment_url;

                dispatch(new SendOrderEmail($order));
            } else {
                $order->failed('Rejected by provider');

                $message = 'Our provider rejected your order. Please contact our support.';

                Flash::create('error', $message);

                $url = route('dashboard.index');
            }
        } catch (\Coingate\ApiError $e) {
            $order->failed($e->getMessage());

            $message = 'Something went wrong with our provider, please contact our support.';

            Flash::create('error', $message);
            $url = route('dashboard.index');
        }

        return redirect($url);
    }

    public function callback(OrderCallbackRequest $request): string
    {
        $order = Order::where('coingate_id', $request->id)->where('hash', $request->token)->first();

        if ($order) {
            $responseCode = Response::HTTP_BAD_REQUEST;

            $order->paid([
                'request' => $request,
                'token_price' => $this->tokenPrice,
                'bonus' => $this->bonusPercentage
            ]);

            $raw = json_encode($request->all());
            $response = new \App\Response();
            $response->create([
                'coingate_id' => $request->id,
                'order_id' => $request->order_id,
                'response' => $raw
            ]);
        } else {
            $responseCode = Response::HTTP_NOT_FOUND;
        }

        return response(null, $responseCode, ['Content-Type' =>'text/plain']);
    }

    private function prepareProviderParams(Order $order): array
    {
        $oid = $order->order_id;

        return [
            'order_id' => $oid,
            'price_amount' => $order->amount,
            'price_currency' => strtoupper($order->currency->short_title),
            'receive_currency' => $this->receiveCurrency,
            'callback_url' => route('payment.callback', $order->hash),
            'cancel_url' => route('payment.cancel', ['order_id' => $oid]),
            'success_url' => route('payment.success', ['order_id' => $oid]),
            'title' => 'Order #' . $oid, // For client
            'description' => sprintf('%s SWA token purchase.', $order->tokens_expected)
        ];
    }

    public function success(string $id)
    {
        Flash::create('success', "Order updated successfully.");

        $order = Order::where('order_id', $id)->first();

        if (!$order) {
            throw new BadRequestHttpException();
        }

        return redirect()->route('dashboard.index');
    }

    public function cancel(string $id)
    {
        Flash::create('danger', "Order has been canceled.");

        $order = Order::where('order_id', $id)->first();

        if (!$order) {
            throw new BadRequestHttpException();
        }

        return redirect()->route('dashboard.index');
    }
}
