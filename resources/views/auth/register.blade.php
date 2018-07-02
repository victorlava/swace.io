@extends('layouts.guest')

@section('menu')
<span class="d-block d-md-inline-block py-3 mr-md-4">Already have an account?</span>
<a class="btn  btn-dark text-uppercase p-3 px-4" href="{{ route('login') }}">Sign in</a>
@endsection

@section('content')
<form class="form-signin" method="POST" action="{{ route('register') }}">
    @csrf
    <input type="hidden" name="tz" id="tz">

    <a class="navbar-brand mb-4" href="#">
        <img src="{{ asset('images/swace-logo-color-white-bg.svg') }}" alt="">
    </a>

    <div class="text-left mt-5 mb-3">
        <h1 class="">Contribute</h1>
    </div>


    @component('components/business-form', ['personal' => '',
                                            'company_name' => '',
                                            'company_code' => '',
                                            'company_vat' => '',
                                            'company_address' => '',
                                            'company_city' => ''])
    @endcomponent

    <div class="row">
        <div class="col-lg-6">
            <input type="text" id="first_name" class="form-control my-2 py-3 px-3{{ $errors->has('first_name') ? ' is-invalid' : '' }}" placeholder="First name" name="first_name" value="{{ old('first_name') }}" required >

            @if ($errors->has('first_name'))
                <span class="invalid-feedback">
                    <strong>{{ $errors->first('first_name') }}</strong>
                </span>
            @endif
        </div>
        <div class="col-lg-6">
            <input type="text" id="last_name" class="form-control my-2 py-3 px-3{{ $errors->has('last_name') ? ' is-invalid' : '' }}" placeholder="Last name" name="last_name" value="{{ old('last_name') }}" required >

            @if ($errors->has('last_name'))
                <span class="invalid-feedback">
                    <strong>{{ $errors->first('last_name') }}</strong>
                </span>
            @endif
        </div>
    </div>

    <div class="form-group">
        <input type="email" id="email" class="form-control my-2 py-3 px-3{{ $errors->has('email') ? ' is-invalid' : '' }}" placeholder="you@example.com" name="email" value="{{ old('email', Session::get('email')) }}" required >

        @if ($errors->has('email'))
            <span class="invalid-feedback">
                <strong>{{ $errors->first('email') }}</strong>
            </span>
        @endif
    </div>

    <div class="form-group">
        <input type="password" id="password"  class="form-control my-2 py-3 px-3{{ $errors->has('password') ? ' is-invalid' : '' }}" placeholder="Password" name="password" value="{{ old('password') }}" required>

        @if ($errors->has('password'))
            <span class="invalid-feedback">
                <strong>{{ $errors->first('password') }}</strong>
            </span>
        @endif
    </div>
    <div class="form-group">
        <input type="text" id="phone" class="form-control my-2 py-3 px-3{{ $errors->has('phone') ? ' is-invalid' : '' }}" placeholder="Phone number" name="phone" value="{{ old('phone') }}">

        @if ($errors->has('phone'))
            <span class="invalid-feedback">
                <strong>{{ $errors->first('phone') }}</strong>
            </span>
        @endif
    </div>

        <div class="custom-control custom-checkbox my-4 mr-sm-3">
            <input type="checkbox" class="custom-control-input {{ $errors->has('terms') ? ' is-invalid' : '' }}" id="agreeTC" name="terms" {{ old('terms') ? ' checked' : '' }}>
            <label class="custom-control-label pl-2" for="agreeTC">I agree with token sale <a href="https://swace.io/downloads/Token_Sale_TC_Swace.pdf" target="_blank">Terms & Conditions</a> </label>

            @if ($errors->has('terms'))
            <span class="invalid-feedback">
                <strong>{{ $errors->first('terms') }}</strong>
            </span>
            @endif
        </div>

        <div class="custom-control custom-checkbox my-4 mr-sm-3">
            <input type="checkbox" class="custom-control-input {{ $errors->has('resident') ? ' is-invalid' : '' }}" id="notUSA" name="resident" {{ old('resident') ? ' checked' : '' }}>
            <label class="custom-control-label pl-2" for="notUSA">I'm NOT a resident, citizen, registered person from United States of America</label>

            @if ($errors->has('resident'))
            <span class="invalid-feedback">
                <strong>{{ $errors->first('resident') }}</strong>
            </span>
            @endif
        </div>

    <button class="btn btn-primary btn-block text-uppercase mt-3 p-3 mb-4" type="submit">Create new account</button>
</form>

@endsection

@section('footer')
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.20.1/moment.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment-timezone/0.5.14/moment-timezone.min.js"></script>
<script>
   $(function () {
       $('#tz').val(moment.tz.guess());
   })
</script>
@endsection
