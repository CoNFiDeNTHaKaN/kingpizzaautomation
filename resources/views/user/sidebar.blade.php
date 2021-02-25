<nav class="navbar navbar-expand-lg navbar-dark bg-default fixed-top" id="mainNav">
<a class="navbar-brand" href="{{route('home')}}"><img src="{{asset('img/logo.svg')}}" alt="" width="167" height="36"></a>
    <button class="navbar-toggler navbar-toggler-right" type="button" data-toggle="collapse" data-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarResponsive">
        <ul class="navbar-nav navbar-sidenav" id="exampleAccordion">
            <li class="nav-item" data-toggle="tooltip" data-placement="right" title="My account">
                <a class="nav-link" href="{{ route('user.account') }}">
                    <i class="fas fa-user"></i>
                    <span class="nav-link-text">My Account</span>
                </a>
            </li>

            <li class="nav-item" data-toggle="tooltip" data-placement="right" title="Edit my info">
                <a class="nav-link" href="{{ route('user.editInfo') }}">
                    <i class="fas fa-edit"></i>
                    <span class="nav-link-text">Edit My Info</span>
                </a>
            </li>

            @if (Auth::user()->is_restaurant)
            <li class="nav-item" data-toggle="tooltip" data-placement="right" title="Add listing + Menu List">
                <a class="nav-link" href="#">
                    <i class="fas fa-plus-circle"></i>
                    <span class="nav-link-text">Add listing + Menu List</span>
                </a>
            </li>
            @endif
            <li class="nav-item" data-toggle="tooltip" data-placement="right" title="Orders Page">
                <a class="nav-link" href="{{ route('user.orders') }}">
                    <i class="fas fa-shopping-basket"></i>
                    <span class="nav-link-text">Orders Page</span>
                </a>
            </li>
            

            <li class="nav-item" data-toggle="tooltip" data-placement="right" title="Saved Addresses">
                <a class="nav-link" href="{{ route('user.savedAddresses') }}">
                    <i class="fas fa-map-marker"></i>
                    <span class="nav-link-text">Saved Addresses</span>
                </a>
            </li>
            <li class="nav-item" data-toggle="tooltip" data-placement="right" title="Saved Cards">
                <a class="nav-link" href="{{ route('user.savedCards') }}">
                    <i class="fas fa-credit-card"></i>
                    <span class="nav-link-text">Saved Cards</span>
                </a>
            </li>
        </ul>
        <ul class="navbar-nav sidenav-toggler">
            <li class="nav-item">
                <a class="nav-link text-center" id="sidenavToggler">
                    <i class="fa fa-fw fa-angle-left"></i>
                </a>
            </li>
        </ul>
    </div>
</nav>