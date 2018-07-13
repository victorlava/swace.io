<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Sale
{
    public static function collectedAmount(): float
    {
        $amount = Cache::store('file')->get('collected_amount');

        return (Cache::store('file')->has('collected_amount') ? $amount : 0);
    }

    public static function collectedPercentage(float $amount, float $totalAmount): float
    {
        return (100 * $amount) / $totalAmount;
    }
}
