@extends('layouts.email')

@section('title')
    Password changed
@endsection

@section('header')
    Password Changed
@endsection

@section('content')
    Your password has been changed.<br/>
    <br/>
    The IP address from which your password was changed is <strong>{{ $ip }}</strong>.<br/>
    <br/>
    If you did not initiate this request, please contact the Swace team immediately at <a
            href="mailto:{{ env("MAIL_SUPPORT") }}">{{ env("MAIL_SUPPORT") }}</a>.
@endsection
