@extends('layouts.email')

@section('title')
    Password reset
@endsection

@section('header')
    Password Reset
@endsection

@section('content')
    We have received a password reset request from the following IP address
    <strong>{{ $ip }}</strong>. You can change your password using the link below, which will be valid for 15 minutes.
    <br/>
    <br/>

    If you did not initiate this request, please contact the Swace team immediately at <a
            href="mailto:{{ env("MAIL_SUPPORT") }}">{{ env("MAIL_SUPPORT") }}</a>.
@endsection

@section('cta')
    <a class="mcnButton " title="Confirm Email Address" href="{{ route('password.reset', $token) }}?email={{ $email }}"
       target="_blank"
       style="font-weight: bold;letter-spacing: normal;line-height: 100%;text-align: center;text-decoration: none;color: #FFFFFF;">Reset
        email</a>
@endsection

