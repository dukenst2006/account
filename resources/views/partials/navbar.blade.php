<!-- BEGIN TOP NAVIGATION BAR -->
<div class="navbar-inner">
    <div class="header-seperation">
        <ul class="nav pull-left notifcation-center" id="main-menu-toggle-wrapper" style="display:none">
            <li class="dropdown"> <a id="main-menu-toggle" href="#main-menu"  class="" >
                    <div class="iconset top-menu-toggle-white"></div>
                </a> </li>
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
            <ul class="nav quick-section">
                <li class="quicklinks"> <a href="#" class="" id="layout-condensed-toggle" >
                        <div class="iconset top-menu-toggle-dark"></div>
                    </a> </li>
            </ul>
        </div>
        <!-- END TOP NAVIGATION MENU -->
        <!-- BEGIN CHAT TOGGLER -->
        <div class="pull-right">
            <ul class="nav quick-section group-section">
                @if(Auth::user()->hasRole(\BibleBowl\Role::HEAD_COACH) && Auth::user()->groups->count() > 0)
                    <li class="group-menu">
                        <div class="group-menu">
                            <div class="groupname semi-bold">
                                @if(Session::group()->isOwner(Auth::user()))
                                    <a href="/group/{{ Session::group()->id }}/edit">{{ Session::group()->name }}</a>
                                @else
                                    {{ Session::group()->name }}
                                @endif
                            </div>
                            <div class="grouptype" class="faded"> {{ Session::group()->type() }}</div>
                        </div>
                        <div class="iconset top-down-arrow" id="my-groups" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true"></div>
                        <ul class="dropdown-menu pull-right" role="menu" aria-labelledby="my-groups">
                            @foreach(Auth::user()->groups as $group)
                                @if($group->id != Session::group()->id)
                                <li>
                                    <a href="/group/{{ $group->id }}/swap">
                                        <div class="groupname semi-bold"> {{ $group->name }} </div>
                                        <span class="grouptype" class="faded"> {{ $group->type() }}</span>
                                    </a>
                                </li>
                                @endif
                            @endforeach
                            <li class="create-option">
                                <a class="btn btn-primary btn-mini" href="/group/create">Add Group</a>
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
                        <li><a href="/account/address">Address Book</a> </li>
                        <li class="divider"></li>
                        <li><a href="/logout"><i class="fa fa-power-off"></i>&nbsp;&nbsp;Log Out</a></li>
                    </ul>
                </li>
            </ul>
        </div>
        <!-- END CHAT TOGGLER -->
    </div>
    <!-- END TOP NAVIGATION MENU -->
</div>
<!-- END TOP NAVIGATION BAR -->