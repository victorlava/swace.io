@extends('layouts.app')

@section('content')
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
    <div class="row">
        <div class="col-md-12">
            <h1 class="float-left">Dashboard page</h1>
            <div class="col-md-7 float-right">
                <div class="row">
                    <div class="col-md-9 text-right">
                        If you are interested in investing more than <strong>1,000 $</strong> and you want to claim your tokens you need to pass the <a href="#"><strong>KWC</strong></a>.
                    </div>
                    <div class="col-md-3">
                        <a href="#" class="btn btn-danger btn-lg">Buy tokens</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-5">
        <div class="col-md-12">
            <h4>Transaction history</h4>
        </div>
        <div class="col-md-12 mt-3">
            <table class="table">
              <thead class="thead-light">
                <tr>
                  <th scope="col">#</th>
                  <th scope="col">Date</th>
                  <th scope="col">Type</th>
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
                <tr>
                  <th scope="row">1</th>
                  <td>2018-05-12</td>
                  <td>BTC</td>
                  <td>12</td>
                  <td>0.005 $</td>
                  <td>32,452 $</td>
                  <td>1,252 $</td>
                  <td>31,052 $</td>
                  <td>3512</td>
                  <td>120</td>
                  <td>
                       <span class="badge badge-warning">In progress</span>
                  </td>
                </tr>
                <tr>
                  <th scope="row">2</th>
                  <td>2018-05-12</td>
                  <td>BTC</td>
                  <td>12</td>
                  <td>0.005 $</td>
                  <td>32,452 $</td>
                  <td>1,252 $</td>
                  <td>31,052 $</td>
                  <td>3512</td>
                  <td>120</td>
                  <td>
                      <!-- add invoice icon link here -->
                      <span class="badge badge-success">Completed</span>
                  </td>
                </tr>
                <tr>
                  <th scope="row">3</th>
                  <td>2018-05-12</td>
                  <td>BTC</td>
                  <td>12</td>
                  <td>0.005 $</td>
                  <td>32,452 $</td>
                  <td>1,252 $</td>
                  <td>31,052 $</td>
                  <td>3512</td>
                  <td>120</td>
                  <td><span class="badge badge-danger">Failed</span></td>
                </tr>
                <tr>
                    <td colspan="11">
                        Your transaction history is empty.
                    </td>
                </tr>
              </tbody>
            </table>
        </div>
    </div>

    <div class="row mt-5">
        <div class="col-md-12">
            <h4>Pre-sale progress</h4>
            <p>We got <span class="badge badge-success">124,052 $</span> out of <span class="badge badge-warning">1,000,000 $</span></p>
        </div>
        <div class="col-md-12">
            <div class="progress" style="height: 40px;">
              <div class="progress-bar bg-success progress-bar-striped progress-bar-animated" role="progressbar" style="width: 15%" aria-valuenow="15" aria-valuemin="0" aria-valuemax="100">
                  15%
              </div>
            </div>
        </div>
    </div>
</div>
@endsection
