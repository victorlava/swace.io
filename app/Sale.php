<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Sale extends Model
{
    public $updated_at = false;

    // Check the latest sale amount
    public static function collectedAmount(): float
    {
        return (Sale::latest()->first()) ? Sale::latest()->first()->amount : 1;
    }

    public static function collectedPercentage(float $amount, float $totalAmount): float
    {
        return (100 * $amount) / $totalAmount;
    }
}
