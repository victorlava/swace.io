@extends('layouts.dashboard')

@section('head')
<title>Swace</title>
@endsection

@section('content')
<header>
    <div class="container">
        <div class="row">
            <div class="col-sm-6"><h1>Token <span class="highlight">Pre-Sale</span>  is live <small>Ends: {{ $token_end_date }}</small></h1></div>
            <div class="col-sm-6 text-sm-right"><h1 class="">Bonus <span class="highlight">{{ $meta['bonus_percentage'] }}%</span> <small>Ends in {{ $days_left }} days</small> </h1></div>
        </div>
        <div class="progress my-4 mt-lg-5">
            <div class="target" style="left:74%"><span class="d-none d-lg-block info">PRESALE CAP 100 000 000 SWA</span></div>


            <div class="progress-bar" role="progressbar" style="width: {{ $percentage }}%" aria-valuenow="{{ $percentage }}" aria-valuemin="0" aria-valuemax="100"></div>
        </div>

        @if(Session::has('message'))
        <div class="alert alert-dismissible fade show {!! Session::get('type') == 'success' ? 'alert-success' : 'alert-danger' !!}" role="alert">
            {{ Session::get("message") }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">×</span>
            </button>
        </div>
        @endif
    </div>
</header>

<main class="mb-5">
    <div class="canvas">
        <div class="bubble-1"></div>
        <div class="bubble-2"></div>
    </div>

    <div class="container">
        <div class="row">
            <div class="col-lg-8">
                <div id="buy-form" class="light-block contribute p-4">
                    <form method="POST" action="{{ route('payment.store') }}">
                        @csrf

                        <div class="loader-overlay">
                            <div class="loader"></div>
                        </div>

                        @if(!$verified)
                        <div class="note-overlay active">
                            <div class="note py-5">
                                <h3 class="text-center mt-4">You have to confirm your email first.</h3>
                                <p class="text-center">An email with confirmation link was sent to {{ $email }}</p>
                            </div>
                        </div>
                        @endif

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group ">

                                    <label class="d-flex mb-3 mt-2 justify-content-between" for="">Your buy <span class="currency">SWA Tokens </span></label>
                                    <div class="input-group input-group-lg">

                                        <input type="number" class="form-control form-control-lg" id="swaAmount" placeholder="1000" required>
                                        <div class="input-group-append">
                                            <span class="input-group-text">SWA</span>
                                        </div>

                                    </div>

                                </div>

                                <div class="mt-4 mb-3">
                                    <div id="SWASlider"></div>
                                </div>
                                <div class="d-flex justify-content-between">
                                    <label class="small" for="customRange3">
                                        <div class="number text-left">1 000</div>Min. amount
                                    </label>
                                    <label class="small" for="customRange3">
                                        <div class="number text-right">5 000 000</div>Max. amount
                                    </label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="d-flex mb-3 mt-2 justify-content-between" for="">You pay <span id="currency-long" class="currency">{{ $currencies[0]->title }}</span></label>

                                    <div class="input-group input-group-lg">
                                        <input type="number" id="pay-amount" class="form-control form-control-lg{{ $errors->has('amount') ? ' is-invalid' : '' }}" name="amount" step="0.00000000000000001" placeholder="0.0005">
                                        <div id="crypto-toggler" class="input-group-append">
                                            <button class="btn btn-outline-secondary dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">{{ strtoupper($currencies[0]->short_title) }}</button>
                                            <div class="dropdown-menu dropdown-menu-right">
                                                @foreach($currencies as $currency)
                                                <a class="dropdown-item" href="#"
                                                                        data-short="{{ strtoupper($currency->short_title) }}"
                                                                        data-value="{{ $currency->id }}">
                                                {{ $currency->title }}
                                                </a>
                                                @endforeach
                                            </div>
                                            <select id="currency" class="form-control{{ $errors->has('currency') ? ' is-invalid' : '' }}" style="display: none;" name="currency">
                                                @foreach($currencies as $currency)
                                                <option value="{{ $currency->id }}" {{ ($loop->index == 0) ? 'selected' : '' }}>{{ strtoupper($currency->short_title) }}</option>
                                                @endforeach
                                            </select>
                                            @if ($errors->has('currency'))
                                                <span class="invalid-feedback">
                                                    <strong>{{ $errors->first('currency') }}</strong>
                                                </span>
                                            @endif
                                        </div>
                                        @if ($errors->has('amount'))
                                            <span class="invalid-feedback">
                                                <strong>{{ $errors->first('amount') }}</strong>
                                            </span>
                                        @endif
                                    </div>

                                </div>
                                <button type="submit" class="btn p-3 mt-4 btn-primary btn-block text-uppercase">Buy tokens</button>
                            </div>
                        </div>
                    </form>
                    <!-- end of form -->
                </div>

                <div class="alert alert-info mt-4 pt-3 pb-2 px-4" role="alert">
                    <h4 class="alert-heading pt-2 mb-1"><i class="icon icon-info-circled mr-1"></i> KYC Verification Required</h4>
                    <p>The coins you purchase will be distributed after you successfully complete the KYC process, which becomes available on July 6.</p>
                </div>

            </div>
            <div class="col-lg-4">


                <div class="row">
                    <div class="col-md-6 col-lg-12">
                        <div class="light-block mb-3">
                            <div id="countdown" class=" timer d-flex text-center justify-content-center">
                                <div class="time-block flex-fill">
                                    <div class="number days">{{ $days_left }}</div>
                                    <div class="label ">days</div>
                                </div>
                                <div class="separator">:</div>
                                <div class="time-block flex-fill">
                                    <div class="number hours">{{ $hours_left }}</div>
                                    <div class="label">hours</div>
                                </div>
                                <div class="separator">:</div>
                                <div class="time-block flex-fill">
                                    <div class="number minutes">{{ $minutes_left }}</div>
                                    <div class="label">min</div>
                                </div>
                                <div class="separator">:</div>
                                <div class="time-block flex-fill">
                                    <div class="number seconds">01</div>
                                    <div class="label">sec</div>
                                </div>
                            </div>

                            <div class="block-footer text-center">
                                Ends: {{ $token_end_date }}
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 col-lg-12">
                        <div class="dark-block d-flex p-3 mb-1">
                            <div class="swace-coin mr-3"></div>
                            <div class="info"><div class="number">{{ number_format($tokens, 0, '', ' ') }} SWA</div>
                            <div class="label text-uppercase">My Tokens</div></div>

                        </div>
                        <div class="dark-block p-3 mb-1">
                            <div class="number">135 000 000 SWA </div>
                            <div class="label">TOKEN PRESALE POOL INCLUDING 25% BONUS</div>
                        </div>
                        <div class="dark-block p-3 mb-1">
                            <div class="number">${{ number_format($meta['token_price'], 2, '.', ' ') }}</div>
                            <div class="label text-uppercase">Token price</div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
        <div class="mt-5">
            <h3>Your transaction history</h3>
            <div class="container-fluid d-none d-lg-block">
                <div class="row  text-uppercase table-head py-2 mt-3 mb-2">
                    <div class="col-lg-3 ">Date</div>
                    <div class="col-lg-2 ">Amount</div>
                    <div class="col-lg-3 ">USD Rate</div>
                    <div class="col-lg-2 ">SWA Tokens</div>
                    <div class="col-lg-2 ">Bonus</div>
                </div>
            </div>

            @if(count($orders) > 0)
                @foreach($orders as $order)
                <div class="transaction text-center text-lg-left mb-2">
                    <div class="container-fluid">
                        <div class="row align-items-center table-data py-4 py-lg-3">
                            <div class=" col-lg-3 date pb-4 pb-lg-0">
                                <span class="d-block ">
                                    {{ Carbon::parse($order->created_at)->timezone(Auth::user()->timezone) }}
                                </span>
                                <span class="badge badge-pill{{ $order->status->class() }}">{{ $order->status->title }}</span>
                                @if($order->status->title === 'Paid')
                                <a href="{{ $order->invoice }}.pdf" target="_blank" class="ml-2 badge badge-pill badge-secondary">View invoice</a>
                                @elseif($order->status->title === 'Failed' || $order->status->title === 'Expired' || $order->status->title === 'Canceled')

                                @else
                                <a href="{{ $order->invoice }}" target="_blank" class="ml-2 badge badge-pill badge-secondary">View order</a>
                                @endif
                            </div>
                            <div class="col-lg-2 amount-paid">{{ number_format($order->amount, 8)}} {{ strtoupper($order->type->short_title) }}</div>
                            <div class="col-lg-3 usd-info">
                                <div class="row py-2 py-lg-2">
                                    <div class="col-3 col-lg-6">${{ number_format($order->rate, 2, '.', ' ') }}<span class="d-block mb-lg-2 small text-uppercase">Rate</span></div>
                                    <div class="col-3 col-lg-6">${{ number_format($order->gross, 2, '.', ' ') }}<span class="d-block mb-lg-2 small text-uppercase">Gross</span></div>
                                    <div class="col-3 col-lg-6">{{ ($order->net) ? '$'.number_format($order->net, 2, '.', ' ') : '' }}<span class="d-block mb-lg-2 small text-uppercase">Net</span></div>
                                    <div class="col-3 col-lg-6">${{ number_format($order->fee, 2, '.', ' ') }}<span class="d-block mb-lg-2 small text-uppercase">Fee</span></div>
                                </div>
                            </div>
                            <div class="d-lg-none col-lg-1 icon-wrapper pb-3 pb-md-2">
                                <i class="d-lg-none icon icon-arrows-ccw"></i>
                            </div>
                            @if($order->net)
                            <div class="col-6 col-lg-2 amount-swa text-right text-lg-left">{{ number_format($order->tokens, 0, ' ', ' ') }} <span class="d-lg-none">SWA</span> </div>
                            <div class="col-6 col-lg-2 amount-swa text-left text-lg-left">+{{ number_format($order->bonus, 0, ' ', ' ') }} (<span class="highlight">{{ $order->calcBonusPercentage($order->tokens, $order->bonus)}}%</span>)</div>
                            @endif
                        </div>
                    </div>
                </div>
                @endforeach
            @endif
        </div>
    </div>
</main>
@endsection

@section('footer')
<script src="{{ asset('js/nouislider.min.js') }}" type="text/javascript"></script>

<script type="text/javascript">

    $(document).ready(function() {
        $('#swaAmount').val('');
        var deadline = new Date(Date.parse('{{ $token_end_date }}'));
        initializeClock('countdown', deadline);
    });

    function calculateTokens(price, cryptoCurrency) {

            overlay.classList.add('active');

            const url = "{{ URL::to('/') }}" + "/api/rates/" + cryptoCurrency;
            fetch(url)
            .then(response => response.text())
            .then(contents => {
                overlay.classList.remove('active');
                let amount = price / contents,
                    tokens = amount / TOKEN_PRICE;


                if(isNaN(tokens)) { tokens = 0; }

                inputNumber.value = tokens.toFixed(0);

            });
    }

    async function calculatePrice(tokens, cryptoCurrency) {
        tokens = Math.round(tokens);
        bonusTokens = (tokens*BONUS_PERCENTAGE)/100;
        bonusTokensPrice = bonusTokens*TOKEN_PRICE;

        let priceUSD = tokens*TOKEN_PRICE,
            fee = (priceUSD * FEE) / 100;

            overlay.classList.add('active');

            const url = "{{ URL::to('/') }}" + "/api/rates/" + cryptoCurrency;
            const result = await fetch(url)
            .then(response => response.text())
            .then(contents => {
                overlay.classList.remove('active');
                priceCrypto = priceUSD * contents

                if(isNaN(priceCrypto)) { priceCrypto = 0; }

                document.querySelector('#pay-amount').value = priceCrypto.toFixed(8);

                return priceCrypto;

            });

            return result;
    }

    function getSelectedCurrency() {
        return currencySelect.querySelector('option[selected]').textContent;
    }

    function getTokenAmount() {
        return parseInt(form.querySelector('#swaAmount').value);
    }

    const TOKEN_PRICE = {{ $meta['token_price'] }},
          FEE = {{ $meta['coingate_fee'] }},
          BONUS_PERCENTAGE = {{ $meta['bonus_percentage'] }};

    let form = document.querySelector('#buy-form'),
        overlay = form.querySelector('.loader-overlay'),
        cryptoTogglerDiv = document.querySelector('#crypto-toggler'),
        toggler = cryptoTogglerDiv.querySelector('.dropdown-toggle'),
        items = cryptoTogglerDiv.querySelectorAll('.dropdown-menu .dropdown-item'),
        currencySelect = cryptoTogglerDiv.querySelector('#currency');

    var html5Slider = document.getElementById('SWASlider');

    noUiSlider.create(html5Slider, {
        connect: [true, false],
        behaviour: 'tap',
        start: 0,
        range: {
            'min': 1000,
            'max': 5000000
        },
    });

    let inputNumber = document.getElementById('swaAmount'),
        payAmount = document.getElementById('pay-amount');

    html5Slider.noUiSlider.on('update', function( values, handle ) {
        var value = values[handle];
        inputNumber.value = Math.round(value);

    });

    inputNumber.addEventListener('change', function(){
        html5Slider.noUiSlider.set([this.value]);
    });

    payAmount.addEventListener('change', function() {
      calculateTokens(this.value, getSelectedCurrency());
    });

    html5Slider.noUiSlider.on('set', function( values, handle) {
        let currency = getSelectedCurrency();
        calculatePrice(values, currency);

        $('#pay-amount').parent().find('.invalid-feedback').hide();
        $('#pay-amount').removeClass('is-invalid');
    });

    items.forEach(function(value, index, array) {
        items[index].addEventListener('click', function() {
            let options = currencySelect.querySelectorAll('option'),
                selectedOption = currencySelect.querySelector('option[value="' + items[index].dataset.value + '"]');

            options.forEach(function(value, index, array) {
                options[index].removeAttribute('selected');
            })

            selectedOption.setAttribute('selected', true);
            let currency = getSelectedCurrency(),
                tokens = getTokenAmount();

            toggler.textContent = items[index].dataset.short;
            form.querySelector('#currency-long').textContent = items[index].textContent;

            calculatePrice(tokens, currency).then((result) => {
              calculateTokens(result, currency);
            });

        });
    });



</script>
<script src="{{ asset('js/main.js') }}" type="text/javascript"></script>
@endsection
