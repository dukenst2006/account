<!-- BEGIN TOP NAVIGATION BAR -->
<div class="navbar-inner">
    <div class="header-seperation">
        <ul class="nav pull-left notifcation-center" id="main-menu-toggle-wrapper" style="display:none">
            {{-- Hide sidebar-expansion icon for guests on mobile --}}
            @if(Auth::user() != null)
            <li class="dropdown"> <a id="main-menu-toggle" href="#main-menu"  class="" >
                    <div class="iconset top-menu-toggle-white"></div>
                </a> </li>
            @endif
        </ul>
        <!-- BEGIN LOGO -->
        <a href="/"><img src="/img/logo.png" class="logo" alt="" width="106" height="36"/></a>
        <!-- END LOGO -->
        <ul class="nav pull-right notifcation-center">
            <li class="dropdown" id="header_task_bar"> <a href="/" class="dropdown-toggle active" data-toggle="">
                    <div class="iconset top-home"></div>
                </a> </li>
        </ul>
    </div>
    <!-- END RESPONSIVE MENU TOGGLER -->
    <div class="header-quick-nav" >
        <!-- BEGIN TOP NAVIGATION MENU -->
        <div class="pull-left">
            {{-- Hide sidebar-expansion icon for guests --}}
            @if(Auth::user() != null)
            <ul class="nav quick-section">
                <li class="quicklinks"> <a href="#" class="" id="layout-condensed-toggle" >
                        <div class="iconset top-menu-toggle-dark"></div>
                    </a> </li>
            </ul>
            @endif
        </div>
        <!-- END TOP NAVIGATION MENU -->
        <!-- BEGIN CHAT TOGGLER -->
        <div class="pull-right">
            {{-- Hide logged-in menu for guests --}}
            @if(Auth::user() != null)
            <ul class="nav quick-section group-section">
                @if(Auth::user()->isA(\App\Role::HEAD_COACH) && Auth::user()->groups->count() > 0)
                    <li class="group-menu">
                        <div class="group-menu">
                            <div class="groupname semi-bold">
                                <a href="/group/{{ Session::group()->id }}/edit">{{ Session::group()->name }}</a>
                            </div>
                            <div class="grouptype" class="faded"> {{ Session::group()->program->name }}</div>
                        </div>
                        <div class="iconset top-down-arrow" id="my-groups" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true"></div>
                        <ul class="dropdown-menu pull-right" role="menu" aria-labelledby="my-groups">
                            @foreach(Auth::user()->groups()->with('program')->get() as $group)
                                @if($group->id != Session::group()->id)
                                <li>
                                    <a href="/group/{{ $group->id }}/swap">
                                        <div class="groupname semi-bold"> {{ $group->name }} </div>
                                        <span class="grouptype" class="faded"> {{ $group->program->name }}</span>
                                    </a>
                                </li>
                                @endif
                            @endforeach
                            <li class="create-option">
                                <a class="btn btn-primary btn-mini" href="/group/create/search">Add Group</a>
                            </li>
                        </ul>
                    </li>
                @endif
                <li class="quicklinks"><span class="h-seperate"></span></li>
                <li class="quicklinks">
                    <a data-toggle="dropdown" class="dropdown-toggle pull-right" href="#" id="user-options">
                        <div class="iconset top-settings-dark "></div>
                    </a>
                    <ul class="dropdown-menu pull-right" role="menu" aria-labelledby="user-options">
                        <li><a href="/account/edit">My Account</a> </li>
                        <li><a href="/account/address">My Address Book</a> </li>
                        <li><a href="/account/receipts">My Receipts</a> </li>
                        @if(Auth::user()->isA(\App\Role::HEAD_COACH))
                            <li><a href="/account/notifications">Notification Preferences</a> </li>
                        @endif
                        <li class="divider"></li>
                        <li><a href="/logout"><i class="fa fa-power-off"></i>&nbsp;&nbsp;Log Out</a></li>
                    </ul>
                </li>
            </ul>
            @else
                <ul class="nav quick-section guest-nav">
                    <li class="semi-bold">
                        Login with...
                    </li>
                    <li>
                        <span><a href="/login/facebook?returnUrl={{ Request::path() }}" class="btn btn-block btn-info"><i class="fa fa-facebook"></i></a></span>
                    </li>
                    <li>
                        <span><a href="/login/google?returnUrl={{ Request::path() }}" class="btn btn-block btn-danger"><i class="fa fa-google"></i></a></span>
                    </li>
                    <li>
                        <span><a href="/login/twitter?returnUrl={{ Request::path() }}" class="btn btn-block btn-success"><i class="fa fa-twitter"></i></a></span>
                    </li>
                    <li>
                        or your<br/><a href="/login?returnUrl={{ Request::path() }}" style="padding-top: 0px">email and password</a>
                    </li>
                </ul>
            @endif
        </div>
        <!-- END CHAT TOGGLER -->
    </div>
    <!-- END TOP NAVIGATION MENU -->
</div>
<!-- END TOP NAVIGATION BAR -->