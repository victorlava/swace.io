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
    private $pagination;

    public function __construct()
    {
        $this->pagination = 10;
        $this->totalUsers = $this->countUsers();
        $this->filters = [
                            'contributed' => ['Not contributed', 'Contributed'],
                            'verified' => ['Un-verified','Verified'],
                        ];
    }

    private function countUsers(): int
    {
        $number = User::all()->count();

        return $number;
    }

    public function index()
    {
        $users = User::paginate($this->pagination);
        $formGET = '/?contributed=none&verified=none';

        return view('admin.user.index', [   'contributed' => -1,
                                            'verified' => -1,
                                            'filters' => $this->filters,
                                            'users' => $users,
                                            'total' => $this->totalUsers,
                                            'formGET' => $formGET]);
    }

    public function filter(Request $request)
    {
        $contributed = $request->get('contributed');
        $verified = $request->get('verified');
        $formGET = '/?contributed=' . $contributed . '&verified=' . $verified;

        if ($contributed != 'none' && $verified != 'none') {
            $users = User::where('contributed', (int)$contributed)
                           ->where('verified', (int)$verified)
                           ->paginate($this->pagination);
        } elseif ($contributed != 'none') {
            $verified = -1;
            $users = User::where('contributed', (int)$contributed)
                            ->paginate($this->pagination);
        } elseif ($verified != 'none') {
            $contributed = -1;
            $users = User::where('verified', (int)$verified)
                            ->paginate($this->pagination);
        } else { // Both contributed and verified is none, redirect to index
            return redirect()->route('admin.users.index');
        }

        $users->appends(['contributed' => $contributed,
                         'verified' => $verified])->links();

        return view('admin.user.index', [   'contributed' => $contributed,
                                            'verified' => $verified,
                                            'filters' => $this->filters,
                                            'users' => $users,
                                            'total' => $this->totalUsers,
                                            'formGET' => $formGET]);
    }

    public function log(int $id)
    {
        $user = User::with('logs')->findOrFail($id);

        return view('admin.user.log', ['user' => $user]);
    }

    public function transaction(int $id)
    {
        $user = User::with('orders')->findOrFail($id);

        return view('admin.user.transaction', ['user' => $user ]);
    }

    public function export(Request $request)
    {
        $fields = [ 'id', 'first_name',
                    'last_name', 'phone',
                    'email', 'verified',
                    'contributed', 'created_at',
                    'verified_at'
                ];

        $request_users = $request->get('users');

        if ($request_users > 0) {
            $users = User::whereIn('id', $request_users)->get();
        } else {
            $contributed = $request->get('contributed');
            $verified = $request->get('verified');


            if ($contributed != 'none' && $verified != 'none') {
                $users = User::where('contributed', (int)$contributed)
                               ->where('verified', (int)$verified)
                               ->get();
            } elseif ($contributed != 'none') {
                $users = User::where('contributed', (int)$contributed)->get();
            } elseif ($verified != 'none') {
                $users = User::where('verified', (int)$verified)->get();
            } else {
                $users = User::all();
            }
        }

        $csv = new \Laracsv\Export();
        $csv->build($users, $fields);
        $csv->download();
    }
}
