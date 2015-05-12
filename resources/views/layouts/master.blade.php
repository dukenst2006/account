<!doctype html>
<html class="no-js" lang="">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="_token" content="{{ csrf_token() }}" />
    <title>@yield('title')</title>
    @yield('meta')

    <link href="/plugins/pace/pace-theme-flash.css" rel="stylesheet" type="text/css"/>
    <link href="/plugins/boostrapv3/css/bootstrap.min.css" rel="stylesheet" type="text/css"/>
    <link href="/plugins/boostrapv3/css/bootstrap-theme.min.css" rel="stylesheet" type="text/css"/>
    <link href="/plugins/font-awesome/css/font-awesome.css" rel="stylesheet" type="text/css"/>
    @yield('before-styles-end')
    <link href="{!! elixir('css/style.css') !!}" rel="stylesheet" type="text/css"/>
    <link href="{!! elixir('css/responsive.css') !!}" rel="stylesheet" type="text/css"/>
    @yield('after-styles-end')
</head>
<body>

<div class="container-fluid">
    @include('partials.messages')
    @yield('content')
</div>

<script src='/plugins/jquery-1.8.3.min.js' type='text/javascript'></script>
<script src='/plugins/boostrapv3/js/bootstrap.min.js' type='text/javascript'></script>
<script src='/plugins/pace/pace.min.js' type='text/javascript'></script>
@yield('before-scripts-end')
<script src='/js/core.js' type='text/javascript'></script>
@yield('after-scripts-end')

@if (App::environment('production'))
    @include('partials.ga')
@endif
</body>
</html>