@extends('layouts.email')

@section('title')
    KYC verification status
@endsection

@section('header')
  @if($user->kyc == \App\Kyc::STATUS_VERIFIED)
  KYC passed
  @elseif($user->kyc == \App\Kyc::STATUS_AUTO_FAILED)
  Automatic KYC not passed
  @elseif($user->kyc == \App\Kyc::STATUS_MANUAL_FAILED)
  KYC not passed
  @endif
@endsection

@section('content')
  @if($user->kyc == \App\Kyc::STATUS_VERIFIED)
  Congratulations, you've successfully passed our KYC process! Congratulations, you've successfully passed our KYC process! You will now be able to purchase SWA tokens at swace.io.<br /><br />

  If you did not initiate a KYC process with us, please contact the Swace team immediately at hello@swaceapp.com.<br /><br />

  Yours, the Swace team
  @elseif($user->kyc == \App\Kyc::STATUS_AUTO_FAILED)
  Unfortunately, the automatic KYC process has failed. We will try manual testing and will let you know within 24 hours via email and a dashboard notification. However, we recommend that you try going through the automatic process again using your cellphone, as most smartphone cameras are able to produce a high-quality very legible image.<br /><br />

  If you did not initiate a KYC process with us, please contact the Swace team immediately at hello@swaceapp.com.<br /><br />

  Yours, the Swace team  
  @elseif($user->kyc == \App\Kyc::STATUS_MANUAL_FAILED)
  We regret to inform you that you have failed to pass both the automatic and the manual KYC process, and in accordance with Lithuania's regulations we will not be able to accept payments from you at this stage. You are welcome to try again!<br /><br />

  If you did not initiate a KYC process with us, please contact the Swace team immediately at hello@swaceapp.com.<br /><br />

  Yours, the Swace team
  @endif
@endsection
