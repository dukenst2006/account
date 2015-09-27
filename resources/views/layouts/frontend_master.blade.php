<!doctype html>
<html class="no-js" lang="">
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
</head>
<body class="error-body no-top lazy">

<div class="container-fluid">
    @yield('content')
</div>

<div id="footer">
    <div class="error-container">
        <br/><br/><br/><br/><br/>
        <div class="copyright"> <?=date('Y')?> <i class="fa fa-copyright"></i> Bible Bowl</div>
        <div class="text-center p-b-20">
            <a href="/privacy-policy">Privacy Policy</a> &middot;
            <a href="/terms-of-use">Terms of Use</a>
        </div>
    </div>
</div>
@foreach(\BibleBowl\Presentation\Html::$includeJs as $pathToJs)
    <script src="{!! $pathToJs !!}" type="text/javascript"></script>
@endforeach
<script type="text/javascript">
    {!! \BibleBowl\Presentation\Html::$js !!}
</script>
</body>
</html>