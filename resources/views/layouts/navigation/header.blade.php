<!--begin::Header-->
<div id="kt_header" class="header header-fixed">
    <!--begin::Container-->
    <div class="container d-flex align-items-stretch justify-content-between">
        <!--begin::Left-->
        <div class="d-flex align-items-stretch mr-3">
            <!--begin::Header Logo-->
            <div class="header-logo">
                <a href="/home">
                    {{-- <img alt="Logo" src="img/lfuggoc_logo.png" class="logo-default max-h-40px" /> --}}
                    <img alt="Logo" src="img/lfuggoc_logo.png" class="logo-sticky max-h-40px" />
                </a>
            </div>
            <!--end::Header Logo-->
            <!--begin::Header Menu Wrapper-->
            <div class="header-menu-wrapper header-menu-wrapper-left" id="kt_header_menu_wrapper">
                <!--begin::Header Menu-->
                <div id="kt_header_menu" class="header-menu header-menu-left header-menu-mobile header-menu-layout-default">
                    <!--begin::Header Nav-->
                    <ul class="menu-nav">

                        <li class="menu-item menu-item-submenu menu-item-rel">
                            <a href="{{ url('home') }}" class="menu-link">
                                <span class="menu-text">Dashboard</span>
                                <span class="menu-desc"></span>
                                <i class="menu-arrow"></i>
                            </a>
                        </li>
                        @if(session('role') == "Manager" || session('role') == "Administrator" || session('role') == "President")
                            <li class="menu-item menu-item-submenu menu-item-rel">
                                <a href="{{ url('employees') }}" class="menu-link">
                                    <span class="menu-text">Employees</span>
                                    <span class="menu-desc"></span>
                                    <i class="menu-arrow"></i>
                                </a>
                            </li>
                        @endif
                        @if(session('role') == "Administrator")
                            <li class="menu-item menu-item-submenu menu-item-rel">
                                <a href="{{ url('users') }}" class="menu-link">
                                    <span class="menu-text">Users</span>
                                    <span class="menu-desc"></span>
                                    <i class="menu-arrow"></i>
                                </a>
                            </li>

                            <li class="menu-item menu-item-submenu menu-item-rel" data-menu-toggle="click" aria-haspopup="true">
                                <a href="javascript:;" class="menu-link menu-toggle">
                                    <span class="menu-text">Settings</span>
                                    <i class="menu-arrow"></i>
                                </a>
                                <div class="menu-submenu menu-submenu-classic menu-submenu-left">
                                    <ul class="menu-subnav">
                                        <li class="menu-item" aria-haspopup="true">
                                            <a href="{{ url('rfid-settings-controllers') }}" class="menu-link">
                                                <span class="menu-text">RFID Controllers</span>
                                                <span class="menu-desc"></span>
                                            </a>
                                        </li>
                                        <li class="menu-item" aria-haspopup="true">
                                            <a href="{{ url('rfid-settings-doors') }}" class="menu-link">
                                                <span class="menu-text">RFID Doors</span>
                                                <span class="menu-desc"></span>
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            </li>
                            
                            <li class="menu-item menu-item-submenu menu-item-rel" data-menu-toggle="click" aria-haspopup="true">
                                <a href="javascript:;" class="menu-link menu-toggle">
                                    <span class="menu-text">Reports</span>
                                    <i class="menu-arrow"></i>
                                </a>
                                <div class="menu-submenu menu-submenu-classic menu-submenu-left">
                                    <ul class="menu-subnav">
                                        {{-- <li class="menu-item" aria-haspopup="true">
                                            <a href="{{ url('reports-employee-locations') }}" class="menu-link">
                                                <span class="menu-text">Employee Locations</span>
                                                <span class="menu-desc"></span>
                                            </a>
                                        </li> --}}
                                        <li class="menu-item" aria-haspopup="true">
                                            <a href="{{ url('reports-by-doors') }}" class="menu-link">
                                                <span class="menu-text">By Doors</span>
                                                <span class="menu-desc"></span>
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            </li>


                            <li class="menu-item menu-item-submenu menu-item-rel">
                                <a href="{{ url('activity-logs') }}" class="menu-link">
                                    <span class="menu-text">Activity Logs</span>
                                    <span class="menu-desc"></span>
                                    <i class="menu-arrow"></i>
                                </a>
                            </li>
                            
                        @endif 
                           
                    </ul>
                    <!--end::Header Nav-->
                </div>
                <!--end::Header Menu-->
            </div>
            <!--end::Header Menu Wrapper-->
        </div>
        <!--end::Left-->
        <!--begin::Topbar-->
        <div class="topbar">
            <!--begin::User-->
            <div class="dropdown">
                <!--begin::Toggle-->
                <div class="topbar-item">
                    <div class="btn btn-icon btn-hover-transparent-white d-flex align-items-center btn-lg px-md-2 w-md-auto" id="kt_quick_user_toggle">
                        <span class="text-white opacity-70 font-weight-bold font-size-base d-none d-md-inline mr-1">Hi,</span>
                        <span class="text-white opacity-90 font-weight-bolder font-size-base d-none d-md-inline mr-4">{{ Auth::user()->name }}</span>
                        <span class="symbol symbol-35 mr-7">
                            <span class="symbol-label text-white font-size-h5 font-weight-bold bg-white-o-30">{{ substr(session('user.first_name'),0,1) }}</span>
                        </span>
                    </div>
                </div>
                <!--end::Toggle-->
            </div>
            <!--end::User-->
        </div>
        <!--end::Topbar-->
    </div>
    <!--end::Container-->
</div>