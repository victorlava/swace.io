<?php

  $companyClass = '';
  $personalClass = ' checked';

  if(old('personal', $personal) == '0') {
      $companyClass = ' checked';
      $personalClass = '';
  } else {
    $personalClass = ' checked';
  }

?>

<div id="toggle_form" class="row">
  <div class="col-lg-6 my-2 py-3 px-3">
    <div class="custom-control custom-radio custom-control-inline">
      <input type="radio" id="personal" name="personal" class="custom-control-input" value="1"{{ $personalClass }}>
      <label class="custom-control-label" for="personal">Personal</label>
    </div>
  </div>
  <div class="col-lg-6 my-2 py-3 px-3">
    <div class="custom-control custom-radio custom-control-inline">
      <input type="radio" id="company" name="personal" class="custom-control-input" value="0"{{ $companyClass }}>
      <label class="custom-control-label" for="company">Business</label>
    </div>
  </div>
</div>

<div id="company_form" class="row @if($personalClass !== '') d-none @endif">
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
    <div class="col-lg-6">
        <input type="text" id="company_city" class="form-control my-2 py-3 px-3{{ $errors->has('company_city') ? ' is-invalid' : '' }}" placeholder="Company City" name="company_city" value="{{ old('company_city', $company_city) }}" >

        @if ($errors->has('company_city'))
            <span class="invalid-feedback">
                <strong>{{ $errors->first('company_city') }}</strong>
            </span>
        @endif
    </div>
    <div class="col-lg-6">
        <input type="text" id="company_address" class="form-control my-2 py-3 px-3{{ $errors->has('company_address') ? ' is-invalid' : '' }}" placeholder="Company address" name="company_address" value="{{ old('company_address', $company_address) }}" >

        @if ($errors->has('company_address'))
            <span class="invalid-feedback">
                <strong>{{ $errors->first('company_address') }}</strong>
            </span>
        @endif
    </div>
</div>

@section('footer')
<script type="text/javascript">
$('document').ready(function() {
  $('#toggle_form input').on('change', function() {
    $('#company_form').toggleClass('d-none');
  })
});
</script>
@endsection
