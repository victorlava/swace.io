@extends('layouts.email')

@section('title')
    Payment completed successfully!
@endsection

@section('header')
    Payment completed successfully!
@endsection

@section('content')
    Your payment has been completed successfully.<br />

    Thank you for your payment. It has been processed and received by our partner CoinGate.<br/><br/>

    You can view the number of purchased SWA tokens and your overall balance by logging into your dashboard at <a href="{{ route('dashboard.index') }}">{{ env("MAIL_APP_URL") }}</a>.
@endsection
