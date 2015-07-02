<!-- BEGIN SIDEBAR -->
<div class="page-sidebar" id="main-menu">
    <!-- BEGIN MINI-PROFILE -->
    <div class="page-sidebar-wrapper scrollbar-dynamic" id="main-menu-wrapper">
        <div class="user-info-wrapper">
            <div class="profile-wrapper"> <a href="//www.gravatar.com" target="_blank"><img src="{{ Gravatar::src(Auth::user()->email, 69) }}"  alt="" width="69" height="69" /></a> </div>
            <div class="user-info">
                <div class="greeting">Welcome</div>
                <div class="username">{{ Auth::user()->first_name }} <span class="semi-bold">{{ Auth::user()->last_name }}</span></div>
                <div class="status">Status<a href="#">
                        <div class="status-icon green"></div>
                        Online</a></div>
            </div>
        </div>
        <!-- END MINI-PROFILE -->
        <!-- BEGIN SIDEBAR MENU -->
        <ul>
            <li class="start"> <a href="/dashboard"> <i class="icon-custom-home"></i>  <span class="title">Dashboard</span></a></li>
        </ul>
        <div class="clearfix"></div>
        <!-- END SIDEBAR MENU -->
    </div>
</div>
<!-- END SIDEBAR -->