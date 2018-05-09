@extends('layouts.dashboard')

@section('head')
<title>Reset Password | Swace</title>
@endsection

@section('content')
<main class="mb-5">
    <div class="canvas">
        <div class="bubble-1"></div>
        <div class="bubble-2"></div>
    </div>

    <div class="container ">
        <div class="row mt-3 mx-0">

            <div class="col-md-6 light-block p-4 p-sm-5 p-md-4 p-lg-5">
                <form class="form-signin" method="POST" action="{{ route('password.request') }}" autocomplete="off">
                    @csrf

                    <input id="email" type="hidden" class="form-control" name="email" value="{{ request()->get('email') }}" required autofocus>
                    <input type="hidden" name="token" value="{{ $token }}">

                    @if ($errors->has('email'))
                        <span class="invalid-feedback">
                            <strong>{{ $errors->first('email') }}</strong>
                        </span>
                    @endif

                    <div class="text-left mt-4 mb-3">
                        <h1 class="">Reset password</h1>
                    </div>

                    <div class="form-group">
                        <input type="password" id="password" name="password" class="form-control my-2 py-3 px-3{{ $errors->has('password') ? ' is-invalid' : '' }}" placeholder="New password" value="{{ old('password') }}">

                        @if ($errors->has('password'))
                            <span class="invalid-feedback">
                                <strong>{{ $errors->first('password') }}</strong>
                            </span>
                        @endif
                    </div>
                    <div class="form-group">
                        <input type="password" id="password-confirm" name="password_confirmation" class="form-control my-2 py-3 px-3" placeholder="Confirm password" >
                    </div>

                    <button class="btn btn-primary btn-block text-uppercase mt-3 p-3 mb-4" type="submit">Save</button>

                </form>
            </div>

            <div class="col-md-6 text-center text-md-right">
                <div class="content">

                </div>
            </div>

        </div>
    </div>
</main>
@endsection
