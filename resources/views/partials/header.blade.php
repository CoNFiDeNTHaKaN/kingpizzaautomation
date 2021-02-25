@include('partials.mobile-menu')
<header class="header flex flex--v-center">
  <div class="wrapper">

    <div class="row">
        <div class="d-md-none col-2">
            @include('partials.mini-basket')
        </div>
        <div class="col-8 col-md-5 header__left">
          <a href="/" class="header__logo" title="Back to Eat Kebab Online home">
            @inlinesvg('/images/build/logo.svg')
          </a>
        </div>
        <div class="d-none d-md-block col-md-7 header__right t-right">
          @include('partials.header-nav')
        </div>
        <div class="d-md-none header__right col-2">
            <div class="filter-toggle">
                <i class="fas fa-filter"></i>
            </div>

            <div class="mobile-menu-toggle">
                <i class="fas fa-bars"></i>
            </div>
        </div>
    </div>
  </div>
</header>
