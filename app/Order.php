<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Jobs\SendPaymentCompleted;

class Order extends Model
{
    public const COINGATE_STATUS_FAILED = 1;
    public const COINGATE_STATUS_PENDING = 2;
    public const COINGATE_STATUS_EXPIRED = 3;
    public const COINGATE_STATUS_PAID = 4;
    public const COINGATE_STATUS_CANCELED = 5;
    public const COINGATE_STATUS_NEW = 6;

    /** @var string[] */
    protected $fillable = [
        'order_id',
        'amount',
        'rate',
        'gross',
        'fee',
        'net',
        'tokens_expected',
        'tokens',
        'bonus_percentage',
        'receive_currency',
        'bonus',
        'invoice',
        'coingate_id',
        'currency_id',
        'status_id',
        'user_id',
        'hash',
    ];

    public function createNew(array $data, array $request)
    {
        $orderId = $this->generateID($this->user->id);

        $this->amount = (float)$request['amount'];
//        $this->rate = $this->calcRate($data['receive_currency']);
//        $this->gross = $this->calcGross();
//        $this->fee = $this->calcFee($data['fee']);

        $this->fill([
            'order_id' => $orderId,
            'currency_id' => (int)$request['currency'],
            'tokens_expected' => (float)$request['tokens'],
            'bonus_percentage' => $data['bonus_percentage'] / 100,
            'hash' => md5($orderId . $this->user->id . time()),
        ]);

        $this->setStatus(self::COINGATE_STATUS_NEW);

        $this->save();
    }

    public function pending(\CoinGate\Merchant\Order $merchantOrder): void
    {
        $this->coingate_id = $merchantOrder->id;
        $this->invoice = $merchantOrder->payment_url;
        $this->hash = $merchantOrder->token;
        $this->receive_currency = $merchantOrder->receive_currency;

        $this->setStatus(self::COINGATE_STATUS_PENDING);
        $this->save();
    }

    public function failed(string $comment): void
    {
        $this->setStatus(self::COINGATE_STATUS_FAILED);
        $this->comment = $comment;
        $this->save();
    }

    public function canceled(string $comment): void
    {
        $this->setStatus(self::COINGATE_STATUS_CANCELED);
        $this->comment = $comment;
        $this->save();
    }

    public function paid(array $data)
    {
        $this->net = $data['request']->receive_amount;
        $tokens = $this->calcTokens($data['token_price']);
        $bonus = $this->calcBonus($tokens, $data['bonus']);
        $this->tokens = $tokens;
        $this->bonus = $bonus;
        $this->pay_currency = $data['request']->pay_currency;
        $this->setStatus($data['request']->status);
        $this->save();
    }

    public function currency()
    {
        return $this->belongsTo(Currency::class, 'currency_id');
    }

    public function status()
    {
        return $this->belongsTo(Status::class, 'status_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function generateID(int $userID): string
    {
        $string = preg_replace('/[0-9O]+/', '', md5($userID . time()));

        $stringArray = str_split($string);
        $lastIndex = count($stringArray) - 1;
        $order = [];
        foreach (range(1, 12) as $key => $value) {
            $number = random_int(1, 9);
            $char = $stringArray[random_int(0, $lastIndex)];
            $chars = [$number, strtoupper($char)];
            $order[$key] = $chars[random_int(0, 1)];
        }

        return implode('', $order);
    }

    public function calcRate(string $receiveCurrency): float
    {
        return (float)file_get_contents(env('COINGATE_PUBLIC_API') . $this->currency->short_title . '/' . $receiveCurrency);
    }

    public function calcGross(): float
    {
        return $this->rate * $this->amount;
    }

    public function calcFee(float $fee): float
    {
        return $this->gross * $fee / 100;
    }

    public function calcTokens(float $tokenPrice): float
    {
        $tokens = $this->gross / $tokenPrice;

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
//        $status = strtolower($status);
//        $code = 1; // Default;
//
//        switch ($status) {
//            case 'failed':
//                $code = 1;
//                break;
//
//            case 'pending':
//                $code = 2;
//                break;
//
//            case 'expired':
//                $code = 3;
//                break;
//
//            case 'paid':
//                $code = 4;
//                dispatch(new SendPaymentCompleted($this->user));
//                break;
//
//            case 'canceled':
//                $code = 5;
//                break;
//
//        }

        if ($status === self::COINGATE_STATUS_PAID) {
            dispatch(new SendPaymentCompleted($this->user));
        }

        $this->status_id = $status;
    }
}
