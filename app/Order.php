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


    public function type()
    {
        return $this->hasOne('App\Currency', 'id', 'currency_id');
    }

    public function status()
    {
        return $this->hasOne('App\Status', 'id', 'status_id');
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
