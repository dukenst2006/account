<?php

// absolute terrible location for this... but it works
Gravatar::setDefaultImage(url('img/default-avatar.png'))

?><!-- BEGIN SIDEBAR -->
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
        <br/>
        <!-- END MINI-PROFILE -->
        <!-- BEGIN SIDEBAR MENU -->
        <ul>
            <li class="start
            @if(Route::current()->uri() == 'dashboard')
                    active
                @endif">
                <a href="/dashboard"> <i class="icon-custom-home"></i>  <span class="title">Dashboard</span></a>
            </li>
            @can(App\Ability::MANAGE_ROSTER)
                <li class="start
                @if(Route::current()->uri() == 'roster')
                        active
                    @endif">
                    <a href="/roster"> <i class="fa fa-user"></i>  <span class="title">Player Roster</span> </a>
                </li>
                <li @if(Route::current()->uri() == 'roster/map')
                    class="active"
                        @endif> <a href="/roster/map"><i class="icon-custom-map"></i> <span class="title">Player Map</span> </a>
                </li>
                <li @if(Route::current()->uri() == 'memory-master')
                    class="active"
                        @endif> <a href="/memory-master"><i class="fa fa-certificate"></i> <span class="title">Memory Master</span> </a>
                </li>
            @endcan
            @can(App\Ability::MANAGE_TEAMS)
                <li class="start
                @if(Route::current()->uri() == 'team')
                        active
                    @endif">
                    <a href="/teamsets"> <i class="fa fa-users"></i>  <span class="title">Teams</span> </a>
                </li>
            @endcan
            @can(App\Ability::CREATE_TOURNAMENTS)
                <li class="start
                        @if(Route::current()->uri() == 'admin/tournaments')
                    active
                @endif">
                <a href="/admin/tournaments"> <i class="fa fa-trophy"></i> <span class="title">Tournaments</span></a>
            </li>
            @endcan
            @if(
                Bouncer::allows(App\Ability::MANAGE_GROUPS) ||
                Bouncer::allows(App\Ability::MANAGE_PLAYERS) ||
                Bouncer::allows(App\Ability::MANAGE_USERS) ||
                Bouncer::allows(App\Ability::VIEW_REPORTS) ||
                Bouncer::allows(App\Ability::MANAGE_SETTINGS)
            )
                <p class="menu-title">ADMIN</p>
                @can(App\Ability::MANAGE_GROUPS)
                    <li class="
                        @if(Route::current()->uri() == 'admin/groups')
                            active
                        @endif">
                        <a href="/admin/groups"> <i class="fa fa-home"></i> <span class="title">Groups</span></a>
                    </li>
                @endcan
                @can(App\Ability::MANAGE_PLAYERS)
                <li class="
                            @if(Route::current()->uri() == 'admin/players')
                        active
                    @endif">
                    <a href="/admin/players"> <i class="fa fa-users"></i> <span class="title">Players</span></a>
                </li>
                @endcan
                @can(App\Ability::MANAGE_USERS)
                    <li class="
                        @if(Route::current()->uri() == 'admin/users')
                            active
                        @endif">
                        <a href="/admin/users"> <i class="fa fa-user"></i> <span class="title">Users</span></a>
                    </li>
                @endcan
                <?php $isReportsOpen = false; ?>
                @can(App\Ability::VIEW_REPORTS)
                    @if(str_contains(Route::current()->uri(), 'reports/growth'))
                        <?php $isReportsOpen = true; ?>
                    @endif
                    <li
                        @if(starts_with(Route::current()->uri(), 'admin/reports'))
                            class="active @if($isReportsOpen) open @endif"
                        @endif> <a href="javascript:;"> <i class="fa icon-custom-chart"></i> <span class="title">Reports</span> <span class="arrow @if($isReportsOpen) open @endif"></span> </a>
                        <ul class="sub-menu">
                            <li
                                @if(str_contains(Route::current()->uri(), 'reports/growth'))
                                    class="active"
                                @endif>
                                <a href="/admin/reports/growth">Growth</a>
                            </li>
                            <li
                                @if(str_contains(Route::current()->uri(), 'reports/seasons'))
                                    class="active"
                                @endif>
                                <a href="/admin/reports/seasons">Seasons</a>
                            </li>
                            <li
                                @if(str_contains(Route::current()->uri(), 'reports/financials'))
                                    class="active"
                                @endif>
                                <a href="/admin/reports/financials">Financials</a>
                            </li>
                            <li
                                @if(str_contains(Route::current()->uri(), 'reports/registration-surveys'))
                                    class="active"
                                @endif>
                                <a href="/admin/reports/registration-surveys">Registration Surveys</a>
                            </li>
                        </ul>
                    </li>
                @endcan
                @can(App\Ability::MANAGE_SETTINGS)
                    <li class="
                        @if(Route::current()->uri() == 'admin/settings')
                            active
                        @endif">
                        <a href="/admin/settings"> <i class="fa fa-gears"></i> <span class="title">Settings</span></a>
                    </li>
                @endcan
            @endif
        </ul>
        <div class="clearfix"></div>
        <!-- END SIDEBAR MENU -->
    </div>
</div>
<!-- END SIDEBAR -->