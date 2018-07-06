@extends('layouts.email')

@section('title')
    Order made
@endsection

@section('header')
    Order Made
@endsection

@section('content')
    Your order is being processed.<br/>
    <br/>
    If you haven’t completed your purchase and want to continue, you can do that by logging into your dashboard, then clicking ‘View order’ or the link to the order you wish to complete under ‘Your transaction history’.
    <br/>
    <a class="mcnButton " title="Continue order" href="{{ $order_link }}"
       target="_blank"
       style="font-weight: bold;letter-spacing: normal;line-height: 100%;text-align: center;text-decoration: none;color: #FFFFFF;">[Continue the order you started]</a>
    <br/>
    If you did not initiate this request, please contact the Swace team immediately at <a
            href="mailto:{{ env("MAIL_SUPPORT") }}">{{ env("MAIL_SUPPORT") }}</a>.
@endsection

@section('cta')
@endsection
