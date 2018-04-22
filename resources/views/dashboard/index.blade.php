@extends('layouts.app')

@section('content')

@php
    $classDisabled = $verified ? '' : ' disabled';
@endphp

<div class="container">
    <div class="row">
        <div class="col-md-12">
            @if(Session::has('message'))
            <div class="alert {!! Session::get('type') == 'success' ? 'alert-success' : 'alert-danger' !!}" role="alert">
                {{ Session::get("message") }}
            </div>
            @endif
        </div>
    </div>

    <div class="row{{ $classDisabled }}">
        <div class="col-md-12">
            <h1 class="float-left">Dashboard page</h1>
            <div class="col-md-7 float-right">
                <div class="row">
                    <div class="col-md-9 text-right">
                        If you are interested in investing more than <strong>1,000 $</strong> and you want to claim your tokens you need to pass the <a href="#"><strong>KWC</strong></a>.
                    </div>
                    <div class="col-md-3">
                        <a href="{{ route('dashboard.create') }}" class="btn btn-danger btn-lg">Buy tokens</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-5{{ $classDisabled }}">
        <div class="col-md-12">
            <h4>Transaction history</h4>
        </div>
        <div class="col-md-12 mt-3">
            <table class="table">
              <thead class="thead-light">
                <tr>
                  <th scope="col">#</th>
                  <th scope="col">Date</th>
                  <th scope="col">Amount</th>
                  <th scope="col">Rate</th>
                  <th scope="col">Gross</th>
                  <th scope="col">Fee</th>
                  <th scope="col">Net</th>
                  <th scope="col">Tokens</th>
                  <th scope="col">Bonus</th>
                  <th scope="col">Status</th>
                </tr>
              </thead>
              <tbody>
                @if(count($orders) > 0)
                    @foreach($orders as $order)
                    <tr>
                      <th scope="row">
                          {{ $order->order_id }}
                      </th>
                      <td>{{ $order->created_at->format('Y-m-d H:i') }}</td>
                      <td>{{ $order->amount }} {{ strtoupper($order->type->short_title) }}</td>
                      <td>{{ $order->rate }} $</td>
                      <td>{{ $order->gross }} $</td>
                      <td>{{ $order->fee }} $</td>
                      <td>{{ $order->net }} $</td>
                      <td>{{ $order->tokens }}</td>
                      <td>{{ $order->bonus }}</td>
                      <td>
                           <span class="badge{{ $order->status->class() }}">{{ $order->status->title }}</span>
                      </td>
                    </tr>
                    @endforeach
                @else:
                <tr>
                    <td colspan="11">
                        Your transaction history is empty.
                    </td>
                </tr>
                @endif
              </tbody>
            </table>
        </div>
    </div>

    <div class="row mt-5{{ $classDisabled }}">
        <div class="col-md-12">
            <h4>Pre-sale progress</h4>
            <p>We have collected <span class="badge badge-success">{{ number_format($collected) }} $</span> out of <span class="badge badge-warning">{{ number_format($sale) }} $</span></p>
        </div>
        <div class="col-md-12">
            <div class="progress" style="height: 40px;">
              <div class="progress-bar bg-success progress-bar-striped progress-bar-animated" role="progressbar" style="width: {{ $percentage }}%" aria-valuenow="{{ $percentage }}" aria-valuemin="0" aria-valuemax="100">
                  {{ number_format($percentage, 2) }} %
              </div>
            </div>
        </div>
    </div>
</div>
@endsection
