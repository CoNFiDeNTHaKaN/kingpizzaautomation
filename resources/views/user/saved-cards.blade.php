@extends('layouts.main')

@section('pageTitle', 'Cards | My account')

@section('content')

    <div class="content-wrapper">
        <div class="container">
            <div class="row">
                <div class="col-12 mt-5">
                  <h3>Your saved payment card</h3>

                  <p>To make it easier to order you can save your card details when you checkout with Eat Kebab Online - just tick the box on the payment form.</p>
                  <p class="t-green"><b>We never store any of your card details themselves - like your card number or CVV code - just a special key we can use to authorise payments when you want us to.</b> All of your data is safe.</p>
                  
                    @if (!empty($user->stripe_card_id))
                      <form onsubmit="return false;">
                        <div class="formitem">
                          <label for="line1">Card number</label>
                          <input id="line1" type="text" disabled value="•••• •••• •••• {{ $user->stripe_card_last4 }}">
                        </div>
                        <div class="formitem">
                          <label for="line2">Card type</label>
                          <input id="line2" type="text" disabled value="{{ $user->stripe_card_brand  }}">
                        </div>
                      </form>
                      <p>If your information is out of date you can overwrite it by checking the <b>save payment details</b> box when you next order from us.</p>
                      <p>If your details change or you'd rather we didn't fill it in automatically <b>you can delete this information by clicking the below button</b>.</p>
                      <p><a href="{{ route('user.deleteCard') }}" class="button button--red">Delete card information</a></p>
                    @else
                      <p><b>You have no saved card information.</b></p>
                    @endif
                </div>
            </div>
        </div>
    </div>

@endsection
