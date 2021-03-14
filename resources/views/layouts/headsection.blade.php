<script>
    window.dataLayer = window.dataLayer || [];
    function gtag(){dataLayer.push(arguments);}
    gtag('js', new Date());

    gtag('config', 'UA-158811144-1');
</script>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
<meta name="description" content="@yield('pageTitle') | Eat Kebab Online">

<title>@yield('pageTitle') | Eat Kebab Online</title>

<!-- Favicons-->
<link rel="shortcut icon" href="{{asset('img/favicon.ico')}}" type="image/x-icon">
<link rel="apple-touch-icon" type="image/x-icon" href="{{asset('img/apple-touch-icon-57x57-precomposed.png')}}">
<link rel="apple-touch-icon" type="image/x-icon" sizes="72x72" href="{{asset('img/apple-touch-icon-72x72-precomposed.png')}}">
<link rel="apple-touch-icon" type="image/x-icon" sizes="114x114" href="{{asset('img/apple-touch-icon-114x114-precomposed.png')}}">
<link rel="apple-touch-icon" type="image/x-icon" sizes="144x144" href="{{asset('img/apple-touch-icon-144x144-precomposed.png')}}">

<!-- GOOGLE WEB FONT -->
<link href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700&display=swap" rel="stylesheet">

<!-- BASE CSS -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.11.2/css/all.css" integrity="sha256-46qynGAkLSFpVbEBog43gvNhfrOj+BmwXdxFgVK/Kvc=" crossorigin="anonymous" />
<link href="{{asset('css/bootstrap_customized.min.css')}}" rel="stylesheet">
<link href="{{asset('css/style.css')}}" rel="stylesheet">
<link href="{{asset('css/custom.css')}}" rel="stylesheet">
<!-- CUSTOM PAGE CSS -->
@yield('pageCss')