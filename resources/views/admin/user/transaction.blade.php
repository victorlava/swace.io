@extends('layouts.admin')

@section('content')

<div class="container">

    <div class="row mt-5">
        <h1 class="mr-auto">Transaction history of <strong>{{ $user->full_name() }}</strong> (#{{ $user->id }})</h1>
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
