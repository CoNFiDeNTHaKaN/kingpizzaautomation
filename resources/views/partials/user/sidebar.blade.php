<p>Welcome back, {{ $user->first_name }}</p>
<nav>
  <ul>
    <li><a href="{{ route('user.account') }}">My Account</a></li>
    <li><a href="{{ route('user.orders') }}">My Orders</a></li>
    <li><a href="{{ route('user.editInfo') }}">Edit my info</a></li>
    <li><a href="{{ route('user.savedAddresses') }}">Saved addresses</a></li>
    <li><a href="{{ route('user.savedCards') }}">Saved cards</a></li>
  </ul>
</nav>
