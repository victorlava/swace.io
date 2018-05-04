<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Order;

class TransactionController extends Controller
{
    public function index()
    {
        $orders = Order::all();

        return view('admin.transaction.index', ['orders' => $orders]);
    }
}
