<footer>
    <div class="wave footer"></div>
    <div class="container margin_60_40 fix_mobile">
        <div class="row">
            <div class="col-lg-3 col-md-6">
                <h3 data-target="#collapse_1">Quick Links</h3>
                <div class="collapse dont-collapse-sm links" id="collapse_1">
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
            </div>
            <div class="col-lg-3 col-md-6">
                <h3 data-target="#collapse_2">Categories</h3>
                <div class="collapse dont-collapse-sm links" id="collapse_2">
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
            </div>
            <div class="col-lg-3 col-md-6">
                    <h3 data-target="#collapse_3">LEGAL</h3>
                <div class="collapse dont-collapse-sm links" id="collapse_3">
                    <ul>
                        <li><a href="{{ route('showPage', ['path'=>'privacy-policy']) }}">Privacy Policy</a></li>
                        <li><a href="{{ route('showPage', ['path'=>'terms']) }}">Terms & Conditions</a></li>
                        <li><a href="{{ route('showPage', ['path'=>'disclaimer']) }}">Disclaimer</a></li>
                        <li><img src="{{asset('/images/build/mastercard-securecode.svg')}}" alt="MasterCard SecureCode" height="30"></li>
                        <li><img src="{{asset('/images/build/verified-by-visa.svg')}}" alt="Verified by Visa" height="30"></li>
                        <li><img src="{{asset('/images/build/american-express-safekey.svg')}}" alt="AMEX Safekey" height="30"></li>
                    </ul>
                      
                    </div>
                </div>
            
            <div class="col-lg-3 col-md-6">
                    <h3 data-target="#collapse_4">Keep in touch</h3>
                <div class="collapse dont-collapse-sm" id="collapse_4">
                    <div id="newsletter">
                        <div id="message-newsletter"></div>
                        help us improve our website <br> <a href="{{ route('showPage', ['path'=>'contact-us']) }}">send us feedback</a>
                        </form>
                    </div>
                    <div class="follow_us">
                        <h5>Follow Us</h5>
                        <ul>
                            <li><a class="footer__social__link" href="facebook"><i class="fab fa-facebook-f"></i></a></li>
                            <li><a class="footer__social__link" href="twitter"><i class="fab fa-twitter"></i></a></li>
                            <li><a class="footer__social__link" href="youtube"><i class="fab fa-youtube"></i></a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <!-- /row-->
        <hr>
        <div class="row add_bottom_25">
            <div class="col-6">
                &copy; {{ date('Y') }} Eat Kebab UK
            </div>
            <div class="col-6 text-right">
                Website by <a href="http://www.domesticsoftware.co.uk/" target="_blank">Domestic Software</a>
            </div>
        </div>
    </div>
</footer>

<div id="toTop"></div><!-- Back to top button -->

<script src="{{asset('js/common_scripts.min.js')}}"></script>
<script src="{{asset('js/common_func.js')}}"></script>
@stack('footerScripts')