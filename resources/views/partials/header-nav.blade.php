<nav>
  <ul>
    <li class="hide-manager">
      <a href="{{ route('restaurants.clearLocation') }}">change location</a>
    </li>
    @if (Auth::check())
      @if (Auth::user()->is_restaurant)
        <li>
          <a href="{{ route('manager.index') }}"><i class="fas fa-store-alt fa-lg"></i> Restaurant managers</a>
        </li>
      @else
        <li>
          <a href="{{ route('user.account') }}"><b>my account</b></a>
        </li>
      @endif
    @endif
    @if (Auth::check())
      <li>
        <a href="{{ route('user.logout') }}">logout</a>
      </li>
    @else
      <li>
        <a href="{{ route('user.login') }}">log in</a>
      </li>
    @endif
    <li>
      <a href="{{ route('showPage', ['path'=>'help']) }}">
        <img src="{{ asset('images/build/help-icon-circle.png') }}">
        help
      </a>
    </li>
  </ul>
</nav>
