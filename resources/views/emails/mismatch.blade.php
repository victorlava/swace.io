@extends('layouts.email')

@section('title')
    Swace Login Notification
@endsection

@section('header')
    Swace Login Notification
@endsection

@section('content')
    You initially registered with the IP:
    <strong>{{ $ip }}</strong>, but there was just a log-in from a different IP address:
    <strong>{{ $currentIP }}</strong>. If it was not you that initiated this request, then your account may have been hacked. Please contact the Swace team immediately at
    <a
            href="mailto:{{ env("MAIL_SUPPORT") }}">{{ env("MAIL_SUPPORT") }}</a>.
@endsection
