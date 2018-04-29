<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Sale;
use App\Order;

class SaleController extends Controller
{
    public function store()
    {
        // Scheduled task - cron job here

        $amount = Order::where('status_id', 4)->sum('gross');

        $sale = new Sale();
        $sale->amount = $amount;
        $sale->save();
    }
}
