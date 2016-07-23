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

    @if (isset(\BibleBowl\Presentation\Html::$css))
        <style type="text/css">
            {!! \BibleBowl\Presentation\Html::$css !!}
        </style>
    @endif

    @if(app()->environment('production'))
        <script type="text/javascript">
            window.heap=window.heap||[],heap.load=function(e,t){window.heap.appid=e,window.heap.config=t=t||{};var r=t.forceSSL||"https:"===document.location.protocol,a=document.createElement("script");a.type="text/javascript",a.async=!0,a.src=(r?"https:":"http:")+"//cdn.heapanalytics.com/js/heap-"+e+".js";var n=document.getElementsByTagName("script")[0];n.parentNode.insertBefore(a,n);for(var o=function(e){return function(){heap.push([e].concat(Array.prototype.slice.call(arguments,0)))}},p=["addEventProperties","addUserProperties","clearEventProperties","identify","removeEventProperty","setEventProperties","track","unsetEventProperty"],c=0;c<p.length;c++)heap[p[c]]=o(p[c])};
            heap.load("3821699139");
        </script>
    @endif
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
            <a href="http://home.biblebowl.org/privacy-policy">Privacy Policy</a> &middot;
            <a href="http://home.biblebowl.org/terms-of-use">Terms of Use</a>
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