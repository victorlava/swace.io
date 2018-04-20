@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <h1>{{ __('Buy tokens') }}</h1>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">{{ __('Fill the form') }}</div>

                <div class="card-body">
                    <form method="POST" action="{{ route('payment.store') }}">
                        @csrf

                        <div class="form-group row">
                            <label for="amount" class="col-sm-4 col-form-label text-md-right">{{ __('Cryptocurrency') }}:</label>

                            <div class="col-md-6">
                                <div class="row">
                                    <div class="col-md-9">
                                        <input id="amount" type="text" class="form-control{{ $errors->has('amount') ? ' is-invalid' : '' }}" name="amount" value="{{ old('amount') }}" placeholder="0.005" autofocus>
                                        @if ($errors->has('amount'))
                                            <span class="invalid-feedback">
                                                <strong>{{ $errors->first('amount') }}</strong>
                                            </span>
                                        @endif
                                    </div>

                                    <div class="col-md-3">
                                        <select id="currency" class="form-control{{ $errors->has('currency') ? ' is-invalid' : '' }}" name="currency">
                                            <option value="btc">BTC</option>
                                            <option value="ltc">LTC</option>
                                            <option value="eth">ETH</option>
                                            <option value="xvg">XVG</option>
                                        </select>
                                        @if ($errors->has('currency'))
                                            <span class="invalid-feedback">
                                                <strong>{{ $errors->first('currency') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="currency" class="col-sm-4 col-form-label text-md-right">
                                {{ __('Rate') }}:
                            </label>

                            <div class="col-md-6">
                                <p id="rate">1,253 $</p>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="currency" class="col-sm-4 col-form-label text-md-right">
                                {{ __('Tokens to receive') }}:
                            </label>

                            <div class="col-md-6">
                                <p id="tokens">
                                    <strong>450 SWA~</strong>
                                </p>
                            </div>
                        </div>

                        <div class="form-group row mb-0">
                            <div class="col-md-8 offset-md-4">
                                <button type="submit" class="btn btn-primary btn-lg">
                                    {{ __('Create an order') }}
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection
