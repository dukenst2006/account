<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="_token" content="{{ csrf_token() }}" />
    <title>@yield('title')</title>
    @yield('meta')

    <link href="/plugins/pace/pace-theme-flash.css" rel="stylesheet" type="text/css"/>
    @yield('before-styles-end')
    <link href="{!! elixir('css/core.css') !!}" rel="stylesheet" type="text/css"/>
    @yield('after-styles-end')
</head>
<body>

    <div class="header navbar navbar-inverse ">
        @include('partials.navbar')
    </div>

    <div class="page-container row-fluid">
        @include('partials.sidebar')

        <div class="page-content">
            @include('partials.messages')
            @yield('content')
        </div>
    </div>

<!--[if lt IE 9]>
<script src="/assets/plugins/respond.js"></script>
<![endif]-->


@if (App::environment('production'))
    @include('partials.ga')
@endif
</body>
</html>