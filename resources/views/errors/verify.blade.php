@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">{{ __('Verify E-mail') }}</div>

                <div class="card-body">
                    <p>
                        {{ __('You need to verify your e-mail address in order to continue to this page.') }}
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
