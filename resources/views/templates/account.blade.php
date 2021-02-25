<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>

      @include('partials.head')
      @stack('headScripts')

    </head>
    <body class="@stack('bodyClasses') my-account ">

      @include('partials.header')

      <main>
        <section class="precontent">
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        @if (Session::has('success'))
            <div class="alert alert-success">
                <ul>
                    <li>{{ Session::get('success') }}</li>
                </ul>
            </div>
        @endif

          @yield('precontent')
        </section>
        <section class="content">

          <div class="wrapper">
          <div class="row">
            <aside class="col-12 col-sm-4 col-md-3">
              @include('partials.user.sidebar')
            </aside>
            <section class="col-12 col-sm-8 col-md-9">
              @yield('content')
            </section>
          </div>
          </div>
        </section>
        <section class="postcontent">
          @yield('postcontent')
        </section>
      </main>

      @include('partials.footer')
    </body>
</html>
