@extends('layouts.main')
@section('page-name', 'subscription-order')

@section('content')

<main class="app-main">

    <div class="container">

        <section class="section-payment">
            <div class="row">
                <div class="col-md-6 col-md-offset-3">
                <div class="section-payment__inner">

                    <h1 class="section-payment__heading">
                        You are subscribing to the {{ $plan->name }} membership
                    </h1>

                    <hr>

                    @if (!Auth::user()->hasCardOnFile())

                    <form class="section-payment__form"
                          action="{{ url('user/subscription/order') }}"
                          method="post" id="payment-form">

                        {{ csrf_field() }}

                        {{-- Stripe Stuff --}}
                        <div class="form-row">
                            <label for="card-element">Credit or debit card</label>
                            <div id="card-element"></div>
                            <div id="card-errors" role="alert"></div>
                        </div>
                        <div class="flex">
                            <div class="form-row">
                                <label>Card Holder</label>
                                <input type="text" name="card_holder" placeholder="e.g. John Smith">
                            </div>
                            <div class="form-row">
                                <label>Billing Postcode</label>
                                <input type="text" name="billing_postcode" placeholder="e.g. SC1 1TB">
                            </div>
                        </div>

                        <hr>

                        <p class="section-payment__summary">
                            You are subscribing to the {{ $plan->name }} subscription for £{{ $plan->price }} per month. You will be billed for your first month shortly after clicking on the Subscribe button below and will continue to be billed monthly from today's date until you actively cancel your subscription. For more information, please read our <a href="{{ url('/legal/terms-and-conditions') }}">Terms &amp; Conditions</a>. If you are happy to continue, please click the Subscribe button.
                        </p>

                        <input type="hidden" name="plan" value="{{ $plan->subkey }}">

                            <div class="section-paymant__cards">
                                <button class="btn btn-primary green">Subscribe</button>
                                    <div class="section-payment__visa"></div>
                                    <div class="section-payment__mastercard"></div>
                                    <div class="section-payment__american"></div>
                                    <div class="section-payment__discover"></div>
                                    <div class="section-payment__diners"></div>
                                    <div class="section-payment__jcb"></div>
                            </div>

                    </form>
                    @else



                    <div class="section-payment__existing-card">

                        <img src="{{ asset('images/' . Auth::user()->card_brand . '.png') }}">

                        <span>XXXX XXXX XXXX {{ Auth::user()->card_last_four }}</span>



                    </div>

                    <div class="section-payment__link"><a href="{{ url('user/billing/update-card') }}">Change Billing Card?</a></div>
                    

                    <p class="section-payment__summary">
                        You are subscribing to the {{ $plan->name }} subscription for £{{ $plan->price }} per month. You will be billed for your first month shortly after clicking on the Subscribe button below and will continue to be billed monthly from today's date until you actively cancel your subscription. For more information, please read our <a href="{{ url('/legal/terms-and-conditions') }}">Terms &amp; Conditions</a>. If you are happy to continue, please click the Subscribe button.
                    </p>

                    <form method="POST" action="{{ url('user/subscription/order') }}">

                        {{ csrf_field() }}

                        <input type="hidden" name="plan" value="{{ $plan->subkey }}">
                        <button class="btn btn-primary green" type="submit">Subscribe</button>

                    </form>

                    @endif

                </div>

            </div>
        </section>
    </div>



</main>



@endsection

@if (!Auth::user()->hasCardOnFile())
@section('footer-assets')

<style type="text/css">
    .StripeElement {
      background-color: white;
      padding: 8px 12px;
      border-radius: 4px;
      border: 1px solid transparent;
      box-shadow: 0 1px 3px 0 #e6ebf1;
      -webkit-transition: box-shadow 150ms ease;
      transition: box-shadow 150ms ease;
    }

    .StripeElement--focus {
      box-shadow: 0 1px 3px 0 #cfd7df;
    }

    .StripeElement--invalid {
      border-color: #fa755a;
    }

    .StripeElement--webkit-autofill {
      background-color: #fefde5 !important;
    }
</style>

<script src="https://js.stripe.com/v3/"></script>

<script type="text/javascript">

    // Create a Stripe client
    var stripe = Stripe('{{ getenv('STRIPE_KEY') }}');

    // Create an instance of Elements
    var elements = stripe.elements();

    // Custom styling can be passed to options when creating an Element.
    // (Note that this demo uses a wider set of styles than the guide below.)
    var style = {
      base: {
        color: '#32325d',
        lineHeight: '24px',
        fontFamily: '"Helvetica Neue", Helvetica, sans-serif',
        fontSmoothing: 'antialiased',
        fontSize: '16px',
        '::placeholder': {
          color: '#aab7c4'
        }
      },
      invalid: {
        color: '#fa755a',
        iconColor: '#fa755a'
      }
    };

    // Create an instance of the card Element
    var card = elements.create('card', {style: style});

    // Add an instance of the card Element into the `card-element` <div>
    card.mount('#card-element');

    card.addEventListener('change', function(event) {
        var displayError = document.getElementById('card-errors');
        if (event.error) {
        displayError.textContent = event.error.message;
        } else {
        displayError.textContent = '';
        }
    });

    // Create a token or display an error when the form is submitted.
    var form = document.getElementById('payment-form');
    form.addEventListener('submit', function(event) {
      event.preventDefault();

      stripe.createToken(card).then(function(result) {
        if (result.error) {
          // Inform the user if there was an error
          var errorElement = document.getElementById('card-errors');
          errorElement.textContent = result.error.message;
        } else {
          // Send the token to your server
          stripeTokenHandler(result.token);
        }
      });
    });

    function stripeTokenHandler(token) {
        // Insert the token ID into the form so it gets submitted to the server
        var form = document.getElementById('payment-form');
        var hiddenInput = document.createElement('input');
        hiddenInput.setAttribute('type', 'hidden');
        hiddenInput.setAttribute('name', 'stripeToken');
        hiddenInput.setAttribute('value', token.id);
        form.appendChild(hiddenInput);

        // Submit the form
        form.submit();
    }

</script>
@endsection
@endif
