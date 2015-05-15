<!-- BEGIN SIDEBAR -->
<div class="page-sidebar" id="main-menu">
    <!-- BEGIN MINI-PROFILE -->
    <div class="page-sidebar-wrapper scrollbar-dynamic" id="main-menu-wrapper">
        <div class="user-info-wrapper">
            <div class="profile-wrapper"> <img src="/img/profiles/avatar.jpg"  alt="" data-src="/img/profiles/avatar.jpg" data-src-retina="/img/profiles/avatar2x.jpg" width="69" height="69" /> </div>
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
        <p class="menu-title">BROWSE <span class="pull-right"><a href="javascript:;"><i class="fa fa-refresh"></i></a></span></p>
        <ul>
            <li class="start "> <a href="index.html" > <i class="icon-custom-home"></i> <span class="title">Dashboard</span> <span class="selected"></span> <span class="arrow open"></span> </a>
                <ul class="sub-menu">
                    <li > <a href="dashboard_v1.html"> Dashboard v1 </a> </li>
                    <li class="active"> <a href="index.html "> Dashboard v2 <span class=" label label-info pull-right m-r-30">NEW</span></a></li>
                </ul>
            </li>
            <li class=""> <a href="widgets.html"> <i class="fa fa-th"></i> <span class="title">Widgets</span> <span class="label label-important pull-right ">HOT</span></a> </li>
            <li class=""> <a href="email.html"> <i class="fa fa-envelope"></i> <span class="title">Email</span> <span class=" badge badge-disable pull-right ">203</span></a> </li>
            <li class=""> <a href="../../frontend/index.html"> <i class="fa fa-flag"></i>  <span class="title">Frontend</span></a></li>
            <li class="active open "> <a href="javascript:;"> <i class="fa fa fa-adjust"></i> <span class="title">Themes</span> <span class="arrow open"></span> </a>
                <ul class="sub-menu">
                    <li > <a href="theme_coporate.html">Coporate </a> </li>
                    <li > <a href="theme_simple.html">Simple</a> </li>
                    <li > <a href="theme_elegant.html">Elegant</a> </li>
                </ul>
            </li>
            <li class=""> <a href="javascript:;"> <i class="fa fa-file-text"></i> <span class="title">Layouts</span> <span class="arrow "></span> </a>
                <ul class="sub-menu">
                    <li > <a href="layout_options.html"> Layout Options </a> </li>
                    <li > <a href="boxed_layout.html">Boxed Layout </a> </li>
                    <li > <a href="boxed_layout_v2.html">Inner Boxed Layout </a> </li>
                    <li > <a href="extended_layout.html">Extended Layout</a> </li>
                    <li > <a href="RTL.html">RTL Layout</a> </li>
                    <li > <a href="horizontal_menu.html">Horizontal Menu</a> </li>
                    <li > <a href="horizontal_menu_boxed.html">Horizontal Menu & Boxed</a> </li>
                </ul>
            </li>
            <li class=""> <a href="javascript:;"> <i class="icon-custom-ui"></i> <span class="title">UI Elements</span> <span class="arrow "></span> </a>
                <ul class="sub-menu">
                    <li > <a href="typography.html"> Typography </a> </li>
                    <li > <a href="messages_notifications.html"> Messages & Notifications </a> </li>
                    <li > <a href="notifications.html"> Notifications </a> </li>
                    <li > <a href="icons.html">Icons</a> </li>
                    <li > <a href="buttons.html">Buttons</a> </li>
                    <li > <a href="tabs_accordian.html"> Tabs & Accordions </a> </li>
                    <li > <a href="sliders.html">Sliders</a> </li>
                    <li > <a href="group_list.html">Group list </a> </li>
                </ul>
            </li>
            <li class=""> <a href="javascript:;"> <i class="icon-custom-form"></i> <span class="title">Forms</span> <span class="arrow "></span> </a>
                <ul class="sub-menu">
                    <li > <a href="form_elements.html">Form Elements </a> </li>
                    <li > <a href="form_validations.html">Form Validations</a> </li>
                </ul>
            </li>
            <li class=""> <a href="javascript:;"> <i class="icon-custom-portlets"></i> <span class="title">Grids</span> <span class="arrow "></span> </a>
                <ul class="sub-menu">
                    <li > <a href="grids_simple.html">Simple Grids</a> </li>
                    <li > <a href="grids_draggable.html">Draggable Grids </a> </li>
                </ul>
            </li>
            <li class=""> <a href="javascript:;"> <i class="icon-custom-thumb"></i> <span class="title">Tables</span> <span class="arrow "></span> </a>
                <ul class="sub-menu">
                    <li > <a href="tables.html"> Basic Tables </a> </li>
                    <li > <a href="datatables.html"> Data Tables </a> </li>
                </ul>
            </li>
            <li class=""> <a href="javascript:;"> <i class="icon-custom-map"></i> <span class="title">Maps</span> <span class="arrow "></span> </a>
                <ul class="sub-menu">
                    <li > <a href="google_map.html"> Google Maps </a> </li>
                    <li > <a href="vector_map.html"> Vector Maps </a> </li>
                </ul>
            </li>
            <li class=""> <a href="charts.html"> <i class="icon-custom-chart"></i> <span class="title">Charts</span> </a> </li>
            <li class=""> <a href="javascript:;"> <i class="icon-custom-extra"></i> <span class="title">Extra</span> <span class="arrow "></span> </a>
                <ul class="sub-menu">
                    <li > <a href="user-profile.html"> User Profile </a> </li>
                    <li > <a href="time_line.html"> Time line </a> </li>
                    <li > <a href="support_ticket.html"> Support Ticket </a> </li>
                    <li > <a href="gallery.html"> Gallery</a> </li>
                    <li class=""><a href="calender.html"> Calendar</a> </li>
                    <li > <a href="search_results.html"> Search Results </a> </li>
                    <li > <a href="invoice.html"> Invoice </a> </li>
                    <li > <a href="404.html"> 404 Page </a> </li>
                    <li > <a href="500.html"> 500 Page </a> </li>
                    <li > <a href="blank_template.html"> Blank Page </a> </li>
                    <li > <a href="login.html"> Login </a> </li>
                    <li > <a href="login_v2.html">Login v2</a> </li>
                    <li > <a href="lockscreen.html"> Lockscreen </a> </li>
                </ul>
            </li>
            <li class=""> <a href="javascript:;"> <i class="fa fa-folder-open"></i> <span class="title">Menu Levels</span> <span class="arrow "></span> </a>
                <ul class="sub-menu">
                    <li > <a href="javascript:;"> Level 1 </a> </li>
                    <li > <a href="javascript:;"> <span class="title">Level 2</span> <span class="arrow "></span> </a>
                        <ul class="sub-menu">
                            <li > <a href="javascript:;"> Sub Menu </a> </li>
                            <li > <a href="ujavascript:;"> Sub Menu </a> </li>
                        </ul>
                    </li>
                </ul>
            </li>
            <li class="hidden-lg hidden-md hidden-xs" id="more-widgets" > <a href="javascript:;"> <i class="fa fa-plus"></i></a>
                <ul class="sub-menu">
                    <li class="side-bar-widgets">
                        <p class="menu-title">FOLDER <span class="pull-right"><a href="#" class="create-folder"><i class="icon-plus"></i></a></span></p>
                        <ul class="folders" >
                            <li><a href="#">
                                    <div class="status-icon green"></div>
                                    My quick tasks </a> </li>
                            <li><a href="#">
                                    <div class="status-icon red"></div>
                                    To do list </a> </li>
                            <li><a href="#">
                                    <div class="status-icon blue"></div>
                                    Projects </a> </li>
                            <li class="folder-input" style="display:none">
                                <input type="text" placeholder="Name of folder" class="no-boarder folder-name" name="" id="folder-name">
                            </li>
                        </ul>
                        <p class="menu-title">PROJECTS </p>
                        <div class="status-widget">
                            <div class="status-widget-wrapper">
                                <div class="title">Freelancer<a href="#" class="remove-widget"><i class="icon-custom-cross"></i></a></div>
                                <p>Redesign home page</p>
                            </div>
                        </div>
                        <div class="status-widget">
                            <div class="status-widget-wrapper">
                                <div class="title">envato<a href="#" class="remove-widget"><i class="icon-custom-cross"></i></a></div>
                                <p>Statistical report</p>
                            </div>
                        </div>
                    </li>
                </ul>
            </li>
        </ul>
        <div class="side-bar-widgets">
            <p class="menu-title">FOLDER <span class="pull-right"><a href="#" class="create-folder"> <i class="fa fa-plus"></i></a></span></p>
            <ul class="folders" >
                <li><a href="#">
                        <div class="status-icon green"></div>
                        My quick tasks </a> </li>
                <li><a href="#">
                        <div class="status-icon red"></div>
                        To do list </a> </li>
                <li><a href="#">
                        <div class="status-icon blue"></div>
                        Projects </a> </li>
                <li class="folder-input" style="display:none">
                    <input type="text" placeholder="Name of folder" class="no-boarder folder-name" name="" >
                </li>
            </ul>
            <p class="menu-title">PROJECTS </p>
            <div class="status-widget">
                <div class="status-widget-wrapper">
                    <div class="title">Freelancer<a href="#" class="remove-widget"><i class="icon-custom-cross"></i></a></div>
                    <p>Redesign home page</p>
                </div>
            </div>
            <div class="status-widget">
                <div class="status-widget-wrapper">
                    <div class="title">envato<a href="#" class="remove-widget"><i class="icon-custom-cross"></i></a></div>
                    <p>Statistical report</p>
                </div>
            </div>
        </div>
        <div class="clearfix"></div>
        <!-- END SIDEBAR MENU -->
    </div>
</div>
<div class="footer-widget">
    <div class="progress transparent progress-small no-radius no-margin">
        <div data-percentage="79%" class="progress-bar progress-bar-success animate-progress-bar" ></div>
    </div>
    <div class="pull-right">
        <div class="details-status"> <span data-animation-duration="560" data-value="86" class="animate-number"></span>% </div>
        <a href="lockscreen.html"><i class="fa fa-power-off"></i></a></div>
</div>
<!-- END SIDEBAR -->