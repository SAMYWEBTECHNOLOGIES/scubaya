<!-- Left side column. contains the logo and sidebar -->
<aside class="main-sidebar">
    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">
        <!-- Sidebar user panel -->
        <div class="user-panel">
            <div class="pull-left image">
                <img src="{{asset('assets/images/user2-160x160.jpg')}}" class="img-circle" alt="User Image">
            </div>
            <div class="pull-left info">
                <p>{{\Illuminate\Support\Facades\Crypt::decrypt(Auth::user()->first_name)}}</p>
                <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
            </div>
        </div>

        <!-- sidebar menu: : style can be found in sidebar.less -->
        <ul class="sidebar-menu" data-widget="tree">
            <li class="treeview-dashboard">
                <a href="{{ route('scubaya::user::dashboard')}}">
                    <i class="fa fa-dashboard"></i>
                    <span>Dashboard</span>
                    <span class="pull-right-container">
                        <i class="fa fa-angle-left pull-right"></i>
                    </span>
                </a>
            </li>
            <li class="treeview">
                <a href="#">
                    <i class="fa fa-user"></i>
                    <span>Profile</span>
                    <span class="pull-right-container">
                        <i class="fa fa-angle-left pull-right"></i>
                    </span>
                </a>
                <ul class="treeview-menu">
                    <li><a href="{{route('scubaya::user::settings::personal_information',[Auth::id()])}}"><i class="fa fa-circle-o"></i> Personal Information</a></li>
                    <li><a href="{{route('scubaya::user::settings::account_settings',[Auth::id()])}}"><i class="fa fa-circle-o"></i> Account Settings</a></li>
                    <li><a href="{{route('scubaya::user::settings::preferences',[Auth::id()])}}"><i class="fa fa-circle-o"></i> Preferences</a></li>
                    <li><a href="{{route('scubaya::user::settings::privacy_settings',[Auth::id()])}}"><i class="fa fa-circle-o"></i> Privacy Settings</a></li>
                </ul>
            </li>
            <li class="treeview">
                <a href="#">
                    <i class="fa fa-ship"></i>
                    <span>Diver</span>
                    <span class="pull-right-container">
                        <i class="fa fa-angle-left pull-right"></i>
                    </span>
                </a>
                <ul class="treeview-menu">
                    {{--<li><a href="#"><i class="fa fa-circle-o"></i> Personal Information</a></li>
                    <li><a href="#"><i class="fa fa-circle-o"></i> Account Settings</a></li>
                    <li><a href="#"><i class="fa fa-circle-o"></i> Preferences</a></li>
                    <li><a href="#"><i class="fa fa-circle-o"></i>Privacy Settings</a></li>--}}
                </ul>
            </li>

            <li class="treeview">
                <a href="#">
                    <i class="fa fa-tasks"></i>
                    <span>Bookings</span>
                    <span class="pull-right-container">
                        <i class="fa fa-angle-left pull-right"></i>
                    </span>
                </a>
                <ul class="treeview-menu">
                    <li><a href="{{route('scubaya::user::bookings::my_bookings',[Auth::id()])}}"><i class="fa fa-circle-o"></i>My Bookings</a></li>
                </ul>
            </li>

            <li class="treeview">
                <a href="#">
                    <i class="fa fa-bars"></i>
                    <span>Dive Logs</span>
                    <span class="pull-right-container">
                      <i class="fa fa-angle-left pull-right"></i>
                    </span>
                </a>
                <ul class="treeview-menu">
                    <li><a href="{{route('scubaya::user::dive_logs::index',[Auth::id()])}}"><i class="fa fa-circle-o"></i> My Dive Logs</a></li>
                    <li><a href="{{route('scubaya::user::dive_logs::create',[Auth::id()])}}"><i class="fa fa-circle-o"></i> Log New Dive</a></li>
                </ul>
            </li>
        </ul>
    </section>
    <!-- /.sidebar -->
</aside>