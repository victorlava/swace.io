@extends('layouts.email')

@section('title')
    Verification
@endsection

@section('header')
    Verification
@endsection

@section('content')
    Please confirm your email address by clicking the link below.<br/>
    <br/>
    We may need to send you critical information about our service and it is important that we have an accurate email address.
    <br/>
@endsection

@section('cta')
    <a class="mcnButton " title="Confirm Email Address" href="{{ route('email.verification', $email_token) }}"
       target="_blank"
       style="font-weight: bold;letter-spacing: normal;line-height: 100%;text-align: center;text-decoration: none;color: #FFFFFF;">Confirm
        Email Address</a>
@endsection
