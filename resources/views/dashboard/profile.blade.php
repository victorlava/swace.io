@extends('layouts.dashboard')

@section('head')
<title>Account settings | Swace</title>
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
                <form class="form-signin" method="POST" action="{{ route('profile.store') }}" autocomplete="off">
                    @csrf

                    <div class="text-left mt-4 mb-3">
                        <h1 class="">Account settings</h1>
                    </div>

                    @if($personal == 0)
                    <div class="row">
                        <div class="col-lg-12">
                            <input type="text" id="company_name" class="form-control my-2 py-3 px-3{{ $errors->has('company_name') ? ' is-invalid' : '' }}" placeholder="Company name" name="company_name" value="{{ old('company_name', $company_name) }}" >

                            @if ($errors->has('company_name'))
                                <span class="invalid-feedback">
                                    <strong>{{ $errors->first('company_name') }}</strong>
                                </span>
                            @endif
                        </div>
                        <div class="col-lg-12">
                            <input type="text" id="company_code" class="form-control my-2 py-3 px-3{{ $errors->has('company_code') ? ' is-invalid' : '' }}" placeholder="Company code" name="company_code" value="{{ old('company_code', $company_code) }}" >

                            @if ($errors->has('company_code'))
                                <span class="invalid-feedback">
                                    <strong>{{ $errors->first('company_code') }}</strong>
                                </span>
                            @endif
                        </div>
                        <div class="col-lg-12">
                            <input type="text" id="company_vat" class="form-control my-2 py-3 px-3{{ $errors->has('company_vat') ? ' is-invalid' : '' }}" placeholder="VAT" name="company_vat" value="{{ old('company_vat', $company_vat) }}" >

                            @if ($errors->has('company_vat'))
                                <span class="invalid-feedback">
                                    <strong>{{ $errors->first('company_vat') }}</strong>
                                </span>
                            @endif
                        </div>
                        <div class="col-lg-12">
                            <input type="text" id="company_address" class="form-control my-2 py-3 px-3{{ $errors->has('company_address') ? ' is-invalid' : '' }}" placeholder="Company address" name="company_address" value="{{ old('company_address', $company_address) }}" >

                            @if ($errors->has('company_address'))
                                <span class="invalid-feedback">
                                    <strong>{{ $errors->first('company_address') }}</strong>
                                </span>
                            @endif
                        </div>
                    </div>
                    @endif

                    <div class="row">
                        <div class="col-lg-6">
                            <input type="text" id="first_name" name="first_name" class="form-control my-2 py-3 px-3{{ $errors->has('first_name') ? ' is-invalid' : '' }}" placeholder="First name" value="{{ old('first_name', $first_name) }}" {{ $disabled }} required >

                            @if ($errors->has('first_name'))
                                <span class="invalid-feedback">
                                    <strong>{{ $errors->first('first_name') }}</strong>
                                </span>
                            @endif
                        </div>
                        <div class="col-lg-6">
                            <input type="text" id="last_name" name="last_name" class="form-control  my-2 py-3 px-3{{ $errors->has('last_name') ? ' is-invalid' : '' }}" placeholder="Last name" value="{{ old('last_name', $last_name) }}" {{ $disabled }} required >

                            @if ($errors->has('last_name'))
                                <span class="invalid-feedback">
                                    <strong>{{ $errors->first('last_name') }}</strong>
                                </span>
                            @endif
                        </div>
                    </div>

                    <div class="form-group">
                      <?php echo $timezone->selectForm(old('timezone', $current_timezone), null, array('class' => 'form-control my-2 py-3 px-3', 'name' => 'timezone')) ?>
                    </div>

                    <div class="form-group">
                        <input type="email" id="email" class="form-control my-2 py-3 px-3{{ $errors->has('email') ? ' is-invalid' : '' }}" placeholder="E-mail address" value="{{ $email }}" disabled required >

                        @if ($errors->has('email'))
                            <span class="invalid-feedback">
                                <strong>{{ $errors->first('email') }}</strong>
                            </span>
                        @endif
                    </div>
                    <div class="form-group">
                        <input type="text" id="mobile" class="form-control my-2 py-3 px-3{{ $errors->has('mobile') ? ' is-invalid' : '' }}" placeholder="Phone number" value="{{ $mobile }}" disabled >

                        @if ($errors->has('mobile'))
                            <span class="invalid-feedback">
                                <strong>{{ $errors->first('mobile') }}</strong>
                            </span>
                        @endif
                    </div>
                    <hr>
                    <h4 class="mt-4 mb-3">Change password</h4>
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
