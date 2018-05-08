@extends('layouts.guest')

@section('head')
<script src='https://www.google.com/recaptcha/api.js'></script>
@endsection

@section('content')

<form class="form-signin" method="POST" action="{{ route('login') }}" autocomplete="off">
    @csrf

    <a class="navbar-brand mb-4" href="#">
        <img src="{{ asset('images/swace-logo-color-white-bg.svg') }}" alt="">
    </a>

    <div class="text-left mt-5 mb-3">
        <h1 class="">Sign in</h1>
    </div>

    <div class="form-label-group">
        <input type="email" id="email" class="form-control my-2 py-3 px-3{{ $errors->has('email') ? ' is-invalid' : '' }}" name="email" value="{{ old('email') }}" placeholder="Email address" required autofocus>

        @if ($errors->has('email'))
            <span class="invalid-feedback">
                <strong>{{ $errors->first('email') }}</strong>
            </span>
        @endif
    </div>

    <div class="form-label-group">
        <input type="password" id="password"  class="form-control my-2 py-3 px-3{{ $errors->has('password') ? ' is-invalid' : '' }}" name="password" placeholder="Password" required>

        @if ($errors->has('password'))
            <span class="invalid-feedback">
                <strong>{{ $errors->first('password') }}</strong>
            </span>
        @endif
    </div>

    @if($fail_counter > 2)
    <div class="form-label-group">
        <div class="g-recaptcha form-control{{ $errors->has('recaptcha') ? ' is-invalid' : '' }}" data-sitekey="{{ $recaptcha }}"></div>

        @if ($errors->has('recaptcha'))
            <span class="invalid-feedback">
                <strong>{{ $errors->first('recaptcha') }}</strong>
            </span>
        @endif
    </div>
    @endif

    <button class="btn btn-primary btn-block text-uppercase mt-3 p-3 mb-4" type="submit">Sign in</button>

    <div class="text-right muted-link">
        <a class="btn-link d-inline-block text-right py-1" href="{{ route('password.request') }}">Forgot password?</a>
    </div>
</form>

@endsection
