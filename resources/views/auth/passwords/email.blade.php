@extends('layouts.guest')

@section('menu')
<span class="d-block d-md-inline-block py-3 mr-md-4">Don't have an account?</span>
<a class="btn  btn-dark text-uppercase p-3 px-4" href="{{ route('register') }}">Sign up</a>
@endsection

@section('content')
<form class="form-signin" method="POST" action="{{ route('password.email') }}">
    @csrf

    <a class="navbar-brand mb-4" href="#">
        <img src="{{ asset('images/swace-logo-color-white-bg.svg') }}" alt="">
    </a>

    <div class="text-left mt-5 mb-3">
        <h1 class="">Forgot password?</h1>
    </div>

    @if (session('status'))
        <div class="alert alert-success">
            {{ session('status') }}
        </div>
    @endif

    <div class="form-label-group">
        <input type="email" id="email" class="form-control  my-2 py-3 px-3{{ $errors->has('email') ? ' is-invalid' : '' }}" placeholder="you@example.com" name="email" value="{{ old('email') }}" required autofocus>
        @if ($errors->has('email'))
            <span class="invalid-feedback">
                <strong>{{ $errors->first('email') }}</strong>
            </span>
        @endif
    </div>

    <button class="btn btn-primary btn-block text-uppercase mt-3 p-3 mb-4" type="submit">Reset password</button>

    <div class="text-left muted-link">
        <a class="btn-link d-inline-block" href="{{ route('login') }}"> <i class="icon icon-arrow-left mr-1"></i> Sign in</a>
    </div>
</form>
@endsection
