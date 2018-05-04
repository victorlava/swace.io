<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use App\Order;

class UserController extends Controller
{
    public function index()
    {
        $users = User::all();


        return view('admin.user.index', ['users' => $users]);
    }

    public function log(int $user_id)
    {
        $user = User::findOrFail($user_id);


        return view('admin.user.log', ['user' => $user]);
    }

    public function transaction(int $user_id)
    {
        $orders = Order::where('user_id', $user_id)->get();

        return view('admin.user.transaction', [ 'user_id' => $user_id,
                                                'orders' => $orders]);
    }
}
