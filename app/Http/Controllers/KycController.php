<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Kyc;
use App\Flash;

class KycController extends Controller
{
    protected $kycProvider;

    public function __construct(Kyc $kycProvider)
    {
        $this->kycProvider = $kycProvider;
    }
    
    public function index()
    {
        if(auth()->user()->isKYC()) {
            Flash::create('success', 'You\'ve passed KYC verification earlier.');

            return redirect()->route('dashboard.index');
        }

        try {
            $url = $this->kycProvider->getUrl();
          
            return redirect()->away($url);
        } catch (\Exception $ex) {
            Flash::create('danger', 'Error while starting KYC verification.');

            info(auth()->user()->email . ' KYC error: ' . $ex->getMessage());
            return redirect()->route('dashboard.index');
        }
    }

    public function status(Request $request)
    {
        try {
            if ($request->has('token')) {
                $verified = $this->kycProvider->checkStatus($request->token);
                if ($verified) {
                    auth()->user()->update(['kyc' => true]);

                    Flash::create('success', 'KYC verification successful');
                } else {
                    $reason = $this->kycProvider->getReason(auth()->user()->email);
                    Flash::create('danger', $reason);
                }
            } else {
                Flash::create('danger', 'Missing KYC token.');
            }
        } catch (\Exception $ex) {
            Flash::create('danger', 'Cannot resolve KYC verification status.');

            info(auth()->user()->email . ' KYC status error:' . $ex->getMessage());
        }

        return redirect()->route('dashboard.index');
    }

    public function callback(Request $request)
    {
        if ($request->has('token')) {
            $user = $this->kycProvider->getVerifiedUserByToken($request->token);

            if($user) {
                $user->update(['kyc' => true]);
            }
        }

        return response(null, 200);
    }
}
