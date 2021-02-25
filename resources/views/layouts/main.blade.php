<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>

      @include('layouts.headsection')
      @stack('headScripts')

    </head>
    <body class="@stack('bodyClasses')" id="@stack('bodyIds')">

      @include('layouts.header')

      <main>
        <section class="precontent">
          @yield('precontent')
        </section>
        <section class="content @stack('contentClasses')">
          @yield('content')
        </section>
        <section class="postcontent">
          @yield('postcontent')
        </section>
      </main>

      @include('layouts.footer')
    </body>
</html>
