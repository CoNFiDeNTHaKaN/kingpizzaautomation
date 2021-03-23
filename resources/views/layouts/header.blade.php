<header class="header black_nav clearfix element_to_stick">
    <div class="container">
        <div id="logo">
            <a href="{{route('home')}}">
                <img src="{{asset('video/logo_sticky.svg')}}" height="60" alt="">
            </a>
        </div>
        <div class="layer"></div><!-- Opacity Mask Menu Mobile -->
        <ul id="top_menu">
        
        </ul>
        <!-- /top_menu -->
        <a href="#0" class="open_close">
            <i class="icon_menu"></i><span>Menu</span>
        </a>
       <nav class="main-menu">
            <div id="header_menu">
                <a href="#0" class="open_close">
                    <i class="icon_close"></i><span>Menu</span>
                </a>
                <a href="{{route('home')}}"><img src="{{asset('video/logo.svg')}}"  height="60" alt=""></a>
            </div>
            <ul>
                <li>
                    <a href="{{ route('showPage', ['path'=>'help']) }}">
                    🙏Help
                    </a>
                </li>

                <li>
                    <a href="{{ route('restaurants.clearLocation') }}">Change Location</a>
                </li>
                @if (Auth::check())
                    @if (Auth::user()->is_restaurant)
                    <li>
                    <a href="{{ route('manager.index') }}"><i class="fas fa-store-alt fa-lg"></i> Restaurant managers</a>
                    </li>
                    @else
                    <li>
                        <a href="{{ route('user.orders') }}"><b>My Orders</b></a>
                    </li>
                    @endif
                @endif

                @if (Auth::check())
                <li class="submenu">
                    <a href="#0" class="show-submenu"><b>My Account</b></a>
                    <ul>
                        <li>
                            <a href="{{ route('user.editInfo') }}">Edit My Info</a>
                        </li>
                        <!--
                        <li>
                            <a href="{{ route('user.savedAddresses') }}">Saved Addresses</a>
                        </li>
                        -->
                        <li>
                            <a href="{{ route('user.savedCards') }}">Saved Cards</a>
                        </li>
                        <li>
                            <a href="{{ route('user.logout') }}">Logout</a>
                        </li>
                    </ul>
                </li>
                
                 @else
                 <li>
                <a href="{{ route('user.login') }}">Login</a>
                </li>
                @endif
            </ul>
       </nav>
    </div>
</header>
