@extends('layouts.admin')

@section('content')

<div class="container">

    <div class="row mt-5">
      <div class="mr-auto">
        <h1 class="mr-auto">Transaction history of <strong>{{ $user->full_name() }}</strong> (#{{ $user->id }})</h1>
        <ul>
          <li>Type: @if($user->personal) Personal @else Company @endif</li>
          @if($user->company_name)<li>Company Name: {{ $user->company_name }}</li> @endif
          @if($user->company_code)<li>Company Code: {{ $user->company_code }}</li> @endif
          @if($user->company_vat)<li>Company VAT: {{ $user->company_vat }}</li> @endif
          @if($user->company_address)<li>Company Address: {{ $user->company_address }}</li>@endif
        </ul>
      </div>
        <div class="col-md-3">
            <div class="row">
                <div class="col-md-12">
                    <a href="{{ URL::previous() }}" class="float-right btn btn-danger btn-lg">Back</a>
                </div>
            </div>
        </div>
        <div class="col-md-12 mt-3">
            @component('admin/components/transaction-user', ['orders' => $user->orders])
            @endcomponent
        </div>
    </div>
</div>
@endsection
