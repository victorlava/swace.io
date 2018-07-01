<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use App\Order;
use App\Flash;
use DB;

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
        $formGET = '/?contributed=none&verified=none&amount_from=0&amount_to=0';

        return view('admin.user.index', [   'contributed' => -1,
                                            'verified' => -1,
                                            'filters' => $this->filters,
                                            'users' => $users,
                                            'total' => $this->totalUsers,
                                            'formGET' => $formGET]);
    }

    private function filter_amount(object $query, int $amount_from, int $amount_to) {


      if($amount_from !== 0 && $amount_to === 0) {
        /* If "from" is not 0, and "to" is 0, then do search from "from" to "infinity" */
        $amount_to = 99999999;
      } else if($amount_from === 0 && $amount_to === 0) {
        return false;
      }

      $query->select('users.id', DB::raw('SUM(gross) as total_amount'))
            ->from('orders')
            ->whereRaw('orders.user_id = users.id')
            ->whereRaw('status_id = 3') // Paid
            ->groupBy('users.id')
            ->havingRaw('total_amount >= ?', [$amount_from])
            ->havingRaw('total_amount <= ?', [$amount_to]);
  
    }

    public function filter(Request $request)
    {
        $contributed = $request->get('contributed');
        $verified = $request->get('verified');
        $amount_from = $request->get('amount_from');
        $amount_to = $request->get('amount_to');
        $formGET = '/?contributed=' . $contributed . '&verified=' . $verified . '&amount_from=' . $amount_from . '&amount_to=' . $amount_to;


        if ($contributed != 'none' && $verified != 'none') {
            $users = User::where('contributed', (int)$contributed)
                            ->where('verified', (int)$verified)
                            ->whereExists(function ($query) use ($amount_from, $amount_to){
                                  $this->filter_amount($query, (int)$amount_from, (int)$amount_to);
                            })
                            ->paginate($this->pagination);
            // dd($users);
        } elseif ($contributed != 'none') {
            $verified = -1;
            $users = User::where('contributed', (int)$contributed)
                            ->whereExists(function ($query) use ($amount_from, $amount_to){
                                  $this->filter_amount($query, (int)$amount_from, (int)$amount_to);
                            })
                            ->paginate($this->pagination);
        } elseif ($verified != 'none') {
            $contributed = -1;
            $users = User::where('verified', (int)$verified)
                            ->whereExists(function ($query) use ($amount_from, $amount_to){
                                  $this->filter_amount($query, (int)$amount_from, (int)$amount_to);
                            })
                            ->paginate($this->pagination);
        } else { // Both contributed and verified is none, redirect to index
            return redirect()->route('admin.users.index');
        }

        $users->appends(['contributed' => $contributed,
                         'verified' => $verified])->links();

        return view('admin.user.index', [   'contributed' => $contributed,
                                            'verified' => $verified,
                                            'amount_from' => $amount_from,
                                            'amount_to' => $amount_to,
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
