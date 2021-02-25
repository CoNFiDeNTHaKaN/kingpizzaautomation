<table class="email-template" style="width: 100%; font-family: Quicksand, sans-serif; font-weight: 500; line-height: 1.4; font-size: 18px; color: #404040; background: #eee; padding: 10px;">
  <tr>
    <td>
      <table style=" max-width: 600px; width: 100%; margin: 0 auto; background: white;">
        <tr>
          <td colspan="2">
            <a href="{{ route('home') }}" style=" max-width: 300px; display: block; margin: 10px auto 20px auto;">
              @inlinesvg('/images/build/logo.svg')
            </a>
          </td>
        </tr>
          <tr>
            <td colspan="2" style="padding:10px;">
              @yield('content')
            </td>
          </tr>
            <tr>
              <td>
                <b>&copy; {{ date('Y') }} Eat Kebab UK</b>
              </td>
              <td style="text-align:right">
                <b><a href="{{ route('showPage', ['path'=>'contact-us']) }}">contact us</a></b>
              </td>
            </tr>

      </table>

    </td>
  </tr>
</table>
