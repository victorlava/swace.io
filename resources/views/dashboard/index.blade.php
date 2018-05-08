@extends('layouts.dashboard')

@section('head')
<title>Swace</title>
@endsection

@section('content')

@php
    $classDisabled = $verified ? '' : ' disabled';
@endphp

<header>
    <div class="container">
        <div class="row">
            <div class="col-sm-6"><h1>Token <span class="highlight">Pre-Sale</span>  is live <small>Ends: 15 June, 2018 00:00 CET</small></h1></div>
            <div class="col-sm-6 text-sm-right"><h1 class="">Bonus <span class="highlight">{{ $meta['bonus_percentage'] }}%</span> <small>Ends in 2 days</small> </h1></div>
        </div>
        <div class="progress my-4 mt-lg-5">
            <div class="target"><span class="d-none d-lg-block info">Soft cap - $2 000 000</span></div>
            <div class="target"><span class="d-none d-lg-block info">Hard cap - ${{ number_format($meta['sale_amount'], 0, ' ',' ') }}</span></div>
            <div class="progress-bar" role="progressbar" aria-valuenow="{{ $percentage }}" aria-valuemin="0" aria-valuemax="100"></div>
        </div>
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
                <div class="light-block contribute p-4">
                    <!-- <div class="loader-overlay active">
                        <div class="loader"></div>
                    </div> -->

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group ">

                                <label class="d-flex mb-3 mt-2 justify-content-between" for="">Your buy <span class="currency">SWA Tokens </span></label>
                                <div class="input-group input-group-lg">

                                    <input type="number" class="form-control form-control-lg" id="swaAmount" placeholder="1000"   required>
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
                                <label class="d-flex mb-3 mt-2 justify-content-between" for="">You pay <span class="currency">Bitcoin</span></label>

                                <div class="input-group input-group-lg">
                                    <input type="number" class="form-control form-control-lg" placeholder="0.0005">
                                    <div class="input-group-append">
                                        <button class="btn  btn-outline-secondary dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">BTC</button>
                                        <div class="dropdown-menu dropdown-menu-right">
                                            <a class="dropdown-item" href="">Bitcoin</a>
                                            <a class="dropdown-item" href="">Ether</a>
                                            <a class="dropdown-item" href="">Litecoin</a>
                                        </div>
                                    </div>
                                </div>

                            </div>
                            <button type="button" class="btn p-3 mt-4 btn-primary btn-block text-uppercase">Buy tokens</button>
                        </div>
                    </div>
                </div>

                <div class="alert alert-info mt-4 pt-3 pb-2 px-4" role="alert">
                    <h4 class="alert-heading pt-2 mb-1"><i class="icon icon-info-circled mr-1"></i> Please start the verification to get your tokens activated.  </h4>
                    <p> You can also  purchase tokens first and start your KYC process later.</p>
                </div>

            </div>
            <div class="col-lg-4">


                <div class="row">
                    <div class="col-md-6 col-lg-12">
                        <div class="light-block mb-3">
                            <div id="countdown" class=" timer d-flex text-center justify-content-center">
                                <div class="time-block flex-fill">
                                    <div class="number days">26</div>
                                    <div class="label ">days</div>
                                </div>
                                <div class="separator">:</div>
                                <div class="time-block flex-fill">
                                    <div class="number hours">03</div>
                                    <div class="label">hours</div>
                                </div>
                                <div class="separator">:</div>
                                <div class="time-block flex-fill">
                                    <div class="number minutes">15</div>
                                    <div class="label">min</div>
                                </div>
                                <div class="separator">:</div>
                                <div class="time-block flex-fill">
                                    <div class="number seconds">17</div>
                                    <div class="label">sec</div>
                                </div>
                            </div>

                            <div class="block-footer text-center">
                                Ends: 15 June, 2018 00:00 CET
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 col-lg-12">
                        <div class="dark-block d-flex p-3 mb-1">
                            <div class="swace-coin mr-3"></div>
                            <div class="info"><div class="number">{{ number_format($user_tokens, 0, '', ' ') }} SWA</div>
                            <div class="label text-uppercase">My Tokens</div></div>

                        </div>
                        <div class="dark-block p-3 mb-1">
                            <div class="number">1 485 000 000 SWA</div>
                            <div class="label">TOKEN CROWDSALE POOL</div>
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
                                    {{ $order->created_at->format('H:i, d M, Y') }}
                                </span>
                                <span class="badge badge-pill{{ $order->status->class() }}">{{ $order->status->title }}</span><a href="{{ $order->invoice }}" class="ml-2 badge badge-pill badge-secondary">View invoice</a>
                            </div>
                            <div class="col-lg-2 amount-paid">{{ $order->amount }} {{ strtoupper($order->type->short_title) }}</div>
                            <div class="col-lg-2 usd-info">
                                <div class="d-lg-flex py-2">
                                    <div class="flex-fill d-inline-block pr-2">${{ number_format($order->rate, 2, '.', ' ') }} <span class="d-block d-lg-none small text-uppercase">Rate</span> </div>
                                    <div class="flex-fill d-inline-block pr-2 d-lg-none">${{ number_format($order->gross, 2, '.', ' ') }} <span class="d-block small text-uppercase">Gross</span> </div>
                                    <div class="flex-fill d-inline-block pr-2 d-lg-none">($order->net) ? '$'.number_format($order->net, 2, '.', ' ') : '' <span class="d-block small text-uppercase">Net</span> </div>
                                    <div class="flex-fill d-inline-block pr-2 d-lg-none">${{ number_format($order->fee, 2, '.', ' ') }} <span class="d-block small text-uppercase">Fee</span> </div>
                                </div>
                            </div>
                            <div class=" col-lg-1 icon-wrapper pb-3 pb-md-2">
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
