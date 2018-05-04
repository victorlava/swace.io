<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use App\Order;

class UserController extends Controller
{
    private $filters;

    public function __construct()
    {
        $this->filters = [
                            'contributed' => ['Not contributed', 'Contributed'],
                            'verified' => ['Un-verified','Verified'],
                        ];
    }

    public function index()
    {
        $users = User::all();

        return view('admin.user.index', [   'contributed' => false,
                                            'verified' => false,
                                            'filters' => $this->filters,
                                            'users' => $users]);
    }

    public function filter(Request $request)
    {
        $contributed = $request->get('contributed');
        $verified = $request->get('verified');

        if ($contributed != 'none' && $verified != 'none') {
            $users = User::where('contributed', (int)$contributed)
                           ->where('verified', (int)$verified)->get();
        } elseif ($contributed != 'none') {
            $verified = -1;
            $users = User::where('contributed', (int)$contributed)->get();
        } elseif ($verified != 'none') {
            $contributed = -1;
            $users = User::where('verified', (int)$verified)->get();
        } else { // Both contributed and verified is none, redirect to index
            return redirect()->route('admin.users.index');
        }

        return view('admin.user.index', [   'contributed' => $contributed,
                                            'verified' => $verified,
                                            'filters' => $this->filters,
                                            'users' => $users]);
    }

    public function log(int $user_id)
    {
        $user = User::findOrFail($user_id);


        return view('admin.user.log', ['user' => $user]);
    }

    // public function filter(Request $request)
    // {
    //     $users = User::where($type, $value)->get();
    //
    //     return view('admin.user.index', ['users' => $users]);
    // }

    public function transaction(int $user_id)
    {
        $orders = Order::where('user_id', $user_id)->get();

        return view('admin.user.transaction', [ 'user_id' => $user_id,
                                                'orders' => $orders]);
    }
}
