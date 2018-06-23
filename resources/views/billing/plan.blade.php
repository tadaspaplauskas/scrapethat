@extends('layouts.app', ['title' => 'Billing'])

@section('content')

<script src="https://js.braintreegateway.com/web/dropin/1.11.0/js/dropin.min.js"></script>
<div id="dropin-container"></div>
<button id="submit-button">Request payment method</button>
<script>
var button = document.querySelector('#submit-button');

braintree.dropin.create({
  authorization: 'sandbox_p8rgvxjr_ck9p76mgm87s3pgz',
  container: '#dropin-container'
}, function (createErr, instance) {
  button.addEventListener('click', function () {
    instance.requestPaymentMethod(function (requestPaymentMethodErr, payload) {
      // Submit payload.nonce to your server
    });
  });
});
</script>

@endsection
