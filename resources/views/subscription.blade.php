@extends('layouts.app', ['title' => 'Billing'])

@section('content')

<h5>Upgrade</h5>

<script src="https://js.braintreegateway.com/web/dropin/1.11.0/js/dropin.min.js"></script>

<form method="POST" action="{{ route('subscribe') }}" id="form">

    {{ csrf_field() }}

    <select name="plan">
        @foreach ($plans as $key => $plan)
            <option value="{{ $key }}">{{ $plan }}</option>
        @endforeach
    </select>

    <div class="row">
        <div id="dropin-container" class="columns six"></div>
    </div>

    <input type="hidden" name="nonce" id="nonce">

    <button type="button" id="submit-button" class="button-primary">Submit</button>
</form>

<script>
var button = document.querySelector('#submit-button');
var nonceField = document.querySelector('#nonce');
var form = document.querySelector('#form');

braintree.dropin.create({
  authorization: 'sandbox_p8rgvxjr_ck9p76mgm87s3pgz',
  container: '#dropin-container'
}, function (createErr, instance) {
  button.addEventListener('click', function () {
    instance.requestPaymentMethod(function (requestPaymentMethodErr, payload) {
      nonceField.value = payload.nonce;
      form.submit();
    });
  });
});
</script>

@endsection
