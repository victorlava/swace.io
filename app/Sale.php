<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Sale
{
    public static function collectedAmount(): float
    {
    	$exists = Storage::disk('local')->exists('collected_amount.txt');

    	if ($exists) {
        	$amount = Storage::get('collected_amount.txt');
        } else {
        	$amount = 0;
        }

        return $amount;
    }

    public static function collectedPercentage(float $amount, float $totalAmount): float
    {
        return (100 * $amount) / $totalAmount;
    }
}
