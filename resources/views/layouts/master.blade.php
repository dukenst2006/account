<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <link rel="shortcut icon" href="/img/favicon.png">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="_token" content="{{ csrf_token() }}" />
    <title>@yield('title')</title>
    @yield('meta')

    <link href="{!! elixir('css/core.css') !!}" rel="stylesheet" type="text/css"/>
    @foreach(\BibleBowl\Presentation\Html::$includeCss as $pathToCss)
        <link href="{!! $pathToCss !!}" rel="stylesheet" type="text/css"/>
    @endforeach

    @if (isset(\BibleBowl\Presentation\Html::$css))
        <style type="text/css">
            {!! \BibleBowl\Presentation\Html::$css !!}
        </style>
    @endif
</head>
<body>

    <div class="header navbar navbar-inverse ">
        @include('partials.navbar')
    </div>

    <div class="page-container row-fluid">
        @include('partials.sidebar')

        <div class="page-content">
            @yield('content')
        </div>
    </div>

    <!--[if lt IE 9]>
    <script src="/assets/plugins/respond.js"></script>
    <![endif]-->
    <script src="/assets/plugins/jquery-1.8.3.min.js" type="text/javascript"></script>
    <script src="{!! elixir('js/backend.js') !!}" type="text/javascript"></script>
    @foreach(\BibleBowl\Presentation\Html::$includeJs as $pathToJs)
        <script src="{!! $pathToJs !!}" type="text/javascript"></script>
    @endforeach
    <script type="text/javascript">
        {!! \BibleBowl\Presentation\Html::$js !!}
    </script>
</body>
</html>