<footer class="footer">
  <div class="wrapper">
    <div class="row">
      <div class="footer__columns col-12 col-sm-6 col-md-3">
        <p>customer service</p>
        <ul>
          @guest
          <li><a href="{{ route('user.login') }}">log in</a></li>
          @endguest
          <li><a href="{{ route('showPage', ['path'=>'help']) }}">help</a></li>
          <li><a href="{{ route('manager.register') }}">restaurant sign up</a></li>
          <li><a href="{{ route('showPage', ['path'=>'about-us']) }}">about us</a></li>
          <li><a href="{{ route('showPage', ['path'=>'contact-us']) }}">contact us</a></li>
        </ul>
      </div>
      <div class="footer__columns col-12 col-sm-6 col-md-3">
        <p>our favourites</p>
        <ul>
          <li><a href="{{ route('restaurants.list', ['favourites' => 'kebabs']) }}">kebabs</a></li>
          <li><a href="{{ route('restaurants.list', ['favourites' => 'burgers']) }}">burgers</a></li>
          <li><a href="{{ route('restaurants.list', ['favourites' => 'pizzas']) }}">pizzas</a></li>
          <li><a href="{{ route('restaurants.list', ['favourites' => 'garlic-bread']) }}">garlic breads</a></li>
          <li><a href="{{ route('restaurants.list', ['favourites' => 'wraps']) }}">wraps</a></li>
          <li><a href="{{ route('restaurants.list', ['favourites' => 'pasta']) }}">pasta</a></li>
          <li><a href="{{ route('restaurants.list', ['favourites' => 'steak-ribs']) }}">steak &amp; ribs</a></li>
        </ul>
      </div>
      <div class="footer__columns col-12 col-sm-6 col-md-3">
        <p>the legal stuff</p>
        <ul>
          <li><a href="{{ route('showPage', ['path'=>'privacy-policy']) }}">privacy policy</a></li>
          <li><a href="{{ route('showPage', ['path'=>'terms']) }}">terms &amp; conditions</a></li>
          <li><a href="{{ route('showPage', ['path'=>'disclaimer']) }}">disclaimer</a></li>
        </ul>
        <div class="secure-payment">
          <div class="secure-payment__mastercard">
            <img src="{{asset('/images/build/mastercard-securecode.svg')}}" alt="MasterCard SecureCode">
          </div>
          <div class="secure-payment__visa">
            <img src="{{asset('/images/build/verified-by-visa.svg')}}" alt="Verified by Visa">
          </div>
          <div class="secure-payment__safekey">
            <img src="{{asset('/images/build/american-express-safekey.svg')}}" alt="AMEX Safekey">
          </div>
        </div>
      </div>
      <div class="footer__columns col-12 col-sm-6 col-md-3">
        <p>feedback</p>
        <ul>
          <li>help us improve our website <br> <a href="{{ route('showPage', ['path'=>'contact-us']) }}">send us feedback</a></li>
        </ul>
        <div class="footer__social">
          follow us<br>
            <a class="footer__social__link" href="facebook"><i class="fab fa-facebook-f"></i></a>
            <a class="footer__social__link" href="twitter"><i class="fab fa-twitter"></i></a>
            <a class="footer__social__link" href="youtube"><i class="fab fa-youtube"></i></a>

        </ul>
      </div>
    </div>
  </div>
</footer>
<footer class="subfooter">
  <div class="wrapper">
    <div class="row">
      <div class="col-12 col-sm-6">
        &copy; {{ date('Y') }} Eat Kebab UK
      </div>
      <div class="col-12 col-sm-6 t-right">
        website by website success
      </div>
    </div>
  </div>
</footer>

<script type="text/javascript" src="{{ asset('/js/app.js') }}"></script>
@stack('footerScripts')
