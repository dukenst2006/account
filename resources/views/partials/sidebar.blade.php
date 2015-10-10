<!-- BEGIN SIDEBAR -->
<div class="page-sidebar" id="main-menu">
    <!-- BEGIN MINI-PROFILE -->
    <div class="page-sidebar-wrapper scrollbar-dynamic" id="main-menu-wrapper">
        <div class="user-info-wrapper">
            <div class="profile-wrapper"> <a href="//www.gravatar.com" target="_blank"><img src="{{ Gravatar::src(Auth::user()->email, 69) }}"  alt="" width="69" height="69" /></a> </div>
            <div class="user-info">
                <div class="greeting">Welcome</div>
                <div class="username">{{ Auth::user()->first_name }} <span class="semi-bold">{{ Auth::user()->last_name }}</span></div>
            </div>
        </div>
        <!-- END MINI-PROFILE -->
        <!-- BEGIN SIDEBAR MENU -->
        <ul>
            <li class="start
            @if(Route::current()->getUri() == 'dashboard')
                    active
                @endif">
                <a href="/dashboard"> <i class="icon-custom-home"></i>  <span class="title">Dashboard</span></a>
            </li>
            @if (Auth::user()->hasRole(\BibleBowl\Role::HEAD_COACH))
                <li class="start
                @if(Route::current()->getUri() == 'roster')
                        active
                    @endif">
                    <a href="/roster"> <i class="fa fa-users"></i>  <span class="title">Player Roster</span> </a>
                </li>
                <li @if(Route::current()->getUri() == 'roster/map')
                        class="active"
                    @endif> <a href="/roster/map"><i class="icon-custom-map"></i> <span class="title">Player Map</span> </a>
                </li>
            @endif
            @if (Auth::user()->can(\BibleBowl\Permission::VIEW_REPORTS) || Auth::user()->hasRole(\BibleBowl\Role::DIRECTOR))
                <p class="menu-title">ADMIN</p>
                @if (Auth::user()->hasRole(\BibleBowl\Role::DIRECTOR))
                    <li class="
                        @if(Route::current()->getUri() == 'admin/groups')
                            active
                        @endif">
                        <a href="/admin/groups"> <i class="fa fa-group"></i> <span class="title">Groups</span></a>
                    </li>
                    <li class="
                        @if(Route::current()->getUri() == 'admin/users')
                            active
                        @endif">
                        <a href="/admin/users"> <i class="fa fa-user"></i> <span class="title">Users</span></a>
                    </li>
                @endif
                @if (Auth::user()->can(\BibleBowl\Permission::VIEW_REPORTS))
                    <li class="
                        @if(starts_with(Route::current()->getUri(), 'admin/reports'))
                            active
                        @endif">
                        <a href="javascript:;"> <i class="icon-custom-chart"></i> <span class="title">Reports</span><span class="arrow
                            @if(starts_with(Route::current()->getUri(), 'admin/reports'))
                                open
                            @endif"></span></a>
                        <ul class="sub-menu">
                            <li
                            @if(ends_with(Route::current()->getUri(), 'growth'))
                            class="active"
                            @endif> <a href="/admin/reports/growth">Growth</a></li>
                        </ul>
                    </li>
                @endif
            @endif
        </ul>
        <div class="clearfix"></div>
        <!-- END SIDEBAR MENU -->
    </div>
</div>
<!-- END SIDEBAR -->