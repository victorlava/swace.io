<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Jobs\SendPaymentCompleted;

class Order extends Model
{

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'order_id', 'amount', 'rate', 'gross', 'fee', 'net', 'tokens',
        'bonus', 'invoice', 'coingate_id', 'currency_id', 'status_id',
        'user_id', 'hash', 'created_at'
    ];

    public static $receiveAmount;

    public function create(array $data)
    {
        $this->order_id = $this->generateID($data['user_id']);
        $this->currency_id = (int)$data['request']->currency;
        $this->amount = $data['request']->amount;
        $this->rate = $this->calcRate($data['receive_currency']);
        $this->gross = $this->calcGross();
        $this->fee = $this->calcFee($data['fee']);
        $this->setStatus('pending');
        $this->user_id = $data['user_id'];
        $this->hash = md5($this->order_id . (string)$data['user_id'] . (string)time());
        $this->save();
    }

    public function pending(array $data)
    {
        $this->coingate_id = $data['id'];
        $this->invoice = $data['url'];
        $this->setStatus('pending');
        $this->save();
    }

    public function failed()
    {
        $this->setStatus('failed');
        $this->save();
    }

    public function paid(array $data)
    {

        $this->net = $data['request']->receive_amount;
        $tokens = $this->calcTokens($data['token_price']);
        $bonus = $this->calcBonus($tokens, $data['bonus']);
        $this->tokens = $tokens;
        $this->bonus = $bonus;
        $this->currency = $data['request']->pay_currency;
        $this->setStatus($data['request']->status);
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

    public function user()
    {
        return $this->hasOne('App\User', 'id', 'user_id');
    }

    public static function getCollectedAmount()
    {
        $amount = Order::whereHas('status', function ($query) {
            $query->where('title', 'Paid');
        })->sum('gross');

        Cache::store('file')->put('collected_amount', $amount, 10);

        return $amount;
    }

    public function generateID(int $userID): string
    {
        $string = preg_replace('/[0-9]O+/', '', md5($userID + time()));
        $string = str_replace('O', '', $string);

        $stringArray = str_split($string);
        $lastIndex = count($stringArray) - 1;
        $order = [];
        foreach (range(1, 12) as $key => $value) {
            $number = rand(1, 9);
            $char = $stringArray[rand(0, $lastIndex)];
            $chars = [$number, strtoupper($char)];
            $order[$key] = $chars[rand(0, 1)];
        }
        $order_id = implode('', $order);

        return $order_id;
    }

    public function calcRate(string $receiveCurrency): float
    {
        return (float)file_get_contents(env('COINGATE_PUBLIC_API') . $this->type->short_title . '/' . $receiveCurrency);
    }

    public function calcGross(): float
    {
        return $this->rate * $this->amount;
    }

    public function calcFee(float $fee): float
    {
        return ($this->gross * $fee) / 100;
    }

    public function calcTokens(float $price): float
    {
        $tokens = $this->gross / $price;

        return $tokens;
    }

    public function calcBonus(float $tokens, float $bonus): int
    {
        $bonus = ($tokens * $bonus) / 100;

        return round($bonus, 2, PHP_ROUND_HALF_DOWN);
    }

    public function calcBonusPercentage(int $tokens, int $bonus_tokens): int
    {
        return round(($bonus_tokens * 100) / $tokens);
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
                // dispatch(new SendPaymentCompleted($this->user));
                break;

            case 'canceled':
                $code = 5;
                break;

        }

        $this->status_id = Status::find($code)->id;
    }
}
