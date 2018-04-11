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
            <h1>Dashboard page</h1>
        </div>
    </div>
</div>
@endsection
