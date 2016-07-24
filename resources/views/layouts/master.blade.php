<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <link rel="shortcut icon" href="/img/favicon.png">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="_token" content="{{ csrf_token() }}" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />
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
            @if(Auth::user() != null)
                heap.identify('{{ Auth::user()->id }}');
                heap.addUserProperties({
                    'Name': "{{ Auth::user()->full_name }}",
                    'Email': "{{ Auth::user()->email }}"
                });
            @endif
            @if(Session::group() != null)
                heap.addUserProperties({
                    'Group': "{{ Session::group()->name }}"
            });
            @endif
        </script>
    @endif
</head>

{{-- Hide sidebar for guests --}}
@if(Auth::user() != null)
<body>
@else
<body class="hide-sidebar">
@endif

    <div class="header navbar navbar-inverse">
        @include('partials.navbar')
    </div>

    <div class="page-container row-fluid">
        @if(Auth::user() != null)
        @include('partials.sidebar')
        @endif

        <div class="page-content">
            @yield('content')
        </div>
    </div>

    <!--[if lt IE 9]>
    <script src="/assets/plugins/respond.js"></script>
    <![endif]-->
    <script src="{!! elixir('js/backend.js') !!}" type="text/javascript"></script>
    <script type="text/javascript">
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        {!! \BibleBowl\Presentation\Html::$jsData !!}
    </script>
    @foreach(\BibleBowl\Presentation\Html::$includeJs as $pathToJs)
        <script src="{!! $pathToJs !!}" type="text/javascript"></script>
    @endforeach
    <script type="text/javascript">
        {!! \BibleBowl\Presentation\Html::$js !!}

        // Include the UserVoice JavaScript SDK (only needed once on a page)
        UserVoice=window.UserVoice||[];(function(){var uv=document.createElement('script');uv.type='text/javascript';uv.async=true;uv.src='//widget.uservoice.com/Zb0frmuahAO5JrkHDUH03w.js';var s=document.getElementsByTagName('script')[0];s.parentNode.insertBefore(uv,s)})();
        UserVoice.push(['set', {
            accent_color: '#448dd6',
            trigger_color: 'white',
            trigger_background_color: '#6aba2e',
            screenshot_enabled: true
        }]);

        @if(Auth::user() != null)
        // Identify the user and pass traits
        UserVoice.push(['identify', {
            id:  {{ Auth::user()->id }},
            email:  '{{ Auth::user()->email }}',
            name:  '{{ Auth::user()->full_name }}',
            created_at:  {{ Auth::user()->created_at->timestamp }},

            @if(Auth::user()->is(\BibleBowl\Role::HEAD_COACH))
                account: {
                    id: {{ Session::group()->id }},
                    name: '[{{ Session::group()->program->abbreviation }}] {{ Session::group()->name }}',
                    created_at: {{ Session::group()->created_at->timestamp }}
                }
            @endif
        }]);
        @endif

        // Add default trigger to the bottom-right corner of the window:
        UserVoice.push(['addTrigger', { mode: 'contact', trigger_position: 'bottom-right' }]);

        // Autoprompt for Satisfaction and SmartVote (only displayed under certain conditions)
        UserVoice.push(['autoprompt', {}]);
    </script>
</body>
</html>