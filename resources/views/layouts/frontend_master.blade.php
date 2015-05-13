<!doctype html>
<html class="no-js" lang="">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="_token" content="{{ csrf_token() }}" />
    <title>@yield('title')</title>
    @yield('meta')

    <link href="/plugins/boostrapv3/css/bootstrap.min.css" rel="stylesheet" type="text/css"/>
    <link href="/plugins/boostrapv3/css/bootstrap-theme.min.css" rel="stylesheet" type="text/css"/>
    <link href="/plugins/font-awesome/css/font-awesome.css" rel="stylesheet" type="text/css"/>
    @yield('before-styles-end')
    <link href="{!! elixir('css/style.css') !!}" rel="stylesheet" type="text/css"/>
    <link href="{!! elixir('css/responsive.css') !!}" rel="stylesheet" type="text/css"/>
    @yield('after-styles-end')
</head>
<body class="error-body no-top lazy">

<div class="container-fluid">
    @yield('content')
</div>

@yield('before-scripts-end')
@yield('after-scripts-end')

@if (App::environment('production'))
    @include('partials.ga')
@endif

<div id="footer">
    <div class="error-container">
        <br/><br/><br/><br/><br/>
        <div class="copyright"> <?=date('Y')?> <i class="fa fa-copyright"></i> Bible Bowl </div>
    </div>
</div>
</body>
</html>