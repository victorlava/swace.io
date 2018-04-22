@extends('layouts.app')

@section('head')
<!--  jQuery for simple api rate check on coingate,
    might not be neccesery on front-end -->
<script
  src="http://code.jquery.com/jquery-3.3.1.min.js"
  integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8="
  crossorigin="anonymous"></script>
@endsection

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
                                            @foreach($currencies as $currency)
                                            <option value="{{ $currency->id }}">{{ strtoupper($currency->short_title) }}</option>
                                            @endforeach
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
                                <p><span id="rate">0</span> $</p>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="currency" class="col-sm-4 col-form-label text-md-right">
                                {{ __('Tokens to receive') }}:
                            </label>

                            <div class="col-md-6">
                                <p>
                                    <strong><span id="tokens">0</span> SWA~</strong>
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
<!--  asdasd -->
<script type="text/javascript">
    $(document).ready(function(){


        $('#amount, #currency').on('input', function() {

            let amount = parseFloat($('#amount').val()),
                currency = $('#currency option:selected').text().toUpperCase(),
                amountUSD = 0,
                tokenPrice = {{ $token_price }},
                tokenAmount = 0; // Just for estimation!

                const proxyurl = "https://cors-anywhere.herokuapp.com/";
                const url = "https://api.coingate.com/v1/rates/merchant/USD/" + currency;
                fetch(proxyurl + url)
                .then(response => response.text())
                .then(contents => {
                    amountUSD = amount / contents;
                    if(isNaN(amountUSD)) { amountUSD = 0; }

                    $('#rate').text(amountUSD.toLocaleString('en', {
                        'maximumFractionDigits': 2
                    }));

                    tokenAmount = amountUSD / tokenPrice;
                    $('#tokens').text(Math.floor(tokenAmount));
                });


        });


    });
</script>
@endsection
