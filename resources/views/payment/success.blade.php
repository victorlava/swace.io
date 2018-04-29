@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <h1>{{ __('Order Created') }}</h1>
        </div>
    </div>

    <div class="row">
        <div class="col-md-8">
            <p>Your order <strong>#{{ $order_id }}</strong>.</p>
        </div>
    </div>
    <div class="row">
        <div class="col-md-8">
            <a href="{{ route('dashboard.index') }}" class="btn btn-primary btn-block">Return to dashboard</a>
        </div>
    </div>
</div>
@endsection
