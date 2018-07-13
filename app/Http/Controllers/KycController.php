<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Kyc;
use App\User;
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
                $statusCode = $this->kycProvider->getStatus($request->token);
                if (in_array($statusCode, Kyc::VERIFIED_STATUS_CODES)) {
                    auth()->user()->update(['kyc' => Kyc::STATUS_VERIFIED]);

                    Flash::create('success', 'KYC verification successful');

                } else {
                    auth()->user()->update(['kyc' => Kyc::STATUS_AUTO_FAILED]);
                }

                dispatch(new \App\Jobs\SendKycStatusChangedEmail(auth()->user()));
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
            $email = $this->kycProvider->getEmailByToken($request->token);
            
            if($email) {
                $user = User::whereEmail($email)->first();

                $statusCode = $this->kycProvider->getStatus($request->token);
                if (in_array($statusCode, Kyc::VERIFIED_STATUS_CODES)) {
                    $user->update(['kyc' => Kyc::STATUS_VERIFIED]);
                } else {
                    $user->update(['kyc' => Kyc::STATUS_MANUAL_FAILED]);
                }

                dispatch(new \App\Jobs\SendKycStatusChangedEmail(auth()->user()));
            }
        }

        return response(null, 200);
    }
}
