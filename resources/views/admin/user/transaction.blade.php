@extends('layouts.admin')

@section('content')

<div class="container">

    <div class="row mt-5">
        <h1 class="float-left">User (#{{ $user_id }}) transaction history</h1>
        <div class="col-md-7 float-right">
            <div class="row">
                <div class="col-md-12">
                    <a href="{{ URL::previous() }}" class="float-right btn btn-danger btn-lg">Back</a>
                </div>
            </div>
        </div>
        <div class="col-md-12 mt-3">
            @component('admin/components/transaction-table', ['orders' => $orders])
            @endcomponent
        </div>
    </div>
</div>
@endsection
