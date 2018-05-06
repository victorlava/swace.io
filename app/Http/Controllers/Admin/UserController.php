<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use App\Order;
use App\Flash;

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

    public function transaction(int $user_id)
    {
        $user = User::findOrFail($user_id);
        // dd($user->orders);

        return view('admin.user.transaction', ['user' => $user ]);
    }

    public function export(Request $request)
    {
        $request_users = $request->get('users');
        if ($request_users > 0) {
            $users = User::whereIn('id', $request_users)->get();

            $fields = [ 'id', 'first_name',
                        'last_name', 'phone',
                        'email', 'verified',
                        'contributed', 'created_at',
                        'verified_at'
                    ];

            $csv = new \Laracsv\Export();
            $csv->build($users, $fields);
            $csv->download();
        } else {
            Flash::create('error', 'Please select data that you want to export.');

            return redirect()->route('admin.users.index');
        }
    }
}
