<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    private $exchangeUrl;

    public static $receiveAmount;

    public function __construct()
    {
        $this->exchangeUrl = 'https://api.coingate.com/v2/rates/merchant/';
    }

    public function create(array $data)
    {
        $this->order_id = $this->generateID();
        $this->currency_id = (int)$data['request']->currency;
        $this->amount = $data['request']->amount;
        $this->rate = $this->calcRate($data['receive_currency']);
        $this->gross = $this->calcGross();
        $this->fee = $this->calcFee($data['fee']);
        $this->setStatus('pending');
        $this->user_id = $data['user_id'];
        $this->hash = md5($this->order_id);
        $this->save();
    }

    public function pending(array $data)
    {
        $this->coingate_id = $data['id'];
        $this->invoice = $data['url'];
        $this->setStatus('pending');
        $this->save();
    }

    public function failed(array $data)
    {
        $this->setStatus('failed');
        $this->save();
    }

    public function paid(array $data)
    {
        $this->net = $data['request']->receive_amount;
        $this->tokens = $this->calcTokens($data['token_price'], $data['bonus']);
        $this->bonus = $this->calcBonus($this->tokens, $data['bonus']);
        $this->setStatus($data['request']->status);
        $this->hash = null; // Remove hash to save space
        $this->save();
    }

    public function type()
    {
        return $this->hasOne('App\Currency', 'id', 'currency_id');
    }

    public function status()
    {
        return $this->hasOne('App\Status', 'id', 'status_id');
    }

    public static function getCollectedAmount()
    {
        $amount = Order::whereHas('status', function ($query) {
            $query->where('title', 'Paid');
        })->sum('gross');

        Cache::store('file')->put('collected_amount', $amount, 10);

        return $amount;
    }

    public function generateID(): int
    {
        return rand(100, 10000000); // Improve this
    }

    public function calcRate(string $receiveCurrency): float
    {
        return (float)file_get_contents($this->exchangeUrl . $this->type->short_title . '/' . $receiveCurrency);
    }

    public function calcGross(): float
    {
        return $this->rate * $this->amount;
    }

    public function calcFee(float $fee): float
    {
        return ($this->gross * $fee) / 100;
    }

    public function calcTokens(float $price, float $bonus): int
    {
        return floor((($this->net / $price) * $bonus) / 100);
    }

    public function calcBonus(int $tokens, float $bonus): int
    {
        return floor(($tokens * $bonus) / 100);
    }

    public function setStatus(string $status)
    {
        $status = strtolower($status);
        $code = 1; // Default;

        switch ($status) {
            case 'failed':
                $code = 1;
                break;

            case 'pending':
                $code = 2;
                break;

            case 'expired':
                $code = 3;
                break;

            case 'paid':
                $code = 4;
                break;

            case 'canceled':
                $code = 5;
                break;

        }

        $this->status_id = Status::find($code)->id;
    }
}
