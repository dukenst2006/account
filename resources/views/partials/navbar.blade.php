
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
            <div class="chat-toggler"> <a href="#" class="dropdown-toggle" id="my-task-list" data-placement="bottom"  data-content='' data-toggle="dropdown" data-original-title="Notifications">
                    <div class="user-details">
                        <div class="username"> {{ Auth::user()->first_name }} <span class="bold">{{ Auth::user()->last_name }}</span> </div>
                    </div>
                    <div class="iconset top-down-arrow"></div>
                </a>
            </div>
            <ul class="nav quick-section ">
                <li class="quicklinks"> <a data-toggle="dropdown" class="dropdown-toggle  pull-right " href="#" id="user-options">
                        <div class="iconset top-settings-dark "></div>
                    </a>
                    <ul class="dropdown-menu  pull-right" role="menu" aria-labelledby="user-options">
                        <li><a href="/account/address"> My Addresses</a> </li>
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