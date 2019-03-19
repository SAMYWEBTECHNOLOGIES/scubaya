<aside class="control-sidebar control-sidebar-dark">
    <!-- Create the tabs -->
    <ul class="nav nav-tabs nav-justified control-sidebar-tabs">
        <li class="active"><a href="#control-sidebar-home-tab" data-toggle="tab"><i class="fa fa-home"></i></a></li>
        {{--<li><a href="#control-sidebar-settings-tab" data-toggle="tab"><i class="fa fa-gears"></i></a></li>--}}
    </ul>
    <!-- Tab panes -->
    <div class="tab-content">
        <!-- Home tab content -->
        <div class="tab-pane active" id="control-sidebar-home-tab" >

            <ul class="treeview-menu">
                <li><a href="{{route('scubaya::user::settings::personal_information',[Auth::id()])}}"><i class="glyphicon glyphicon-user"></i> <span>Personal Information</span></a></li>
                <li><a href="{{route('scubaya::user::settings::account_settings',[Auth::id()])}}"><i class="glyphicon glyphicon-wrench"></i> <span>Account Settings</span></a></li>
                <li><a href="{{route('scubaya::user::settings::preferences',[Auth::id()])}}"><i class="glyphicon glyphicon-wrench"></i> <span>Preferences</span></a></li>
                <li><a href="{{route('scubaya::user::settings::privacy_settings',[Auth::id()])}}"><i class="glyphicon glyphicon-lock"></i> <span>Privacy Settings</span></a></li>
            </ul>


            <!-- /.control-sidebar-menu -->

        </div>
        <!-- /.tab-pane -->
        <!-- Stats tab content -->

        <!-- Settings tab content -->

        <!-- /.tab-pane -->
    </div>
</aside>
<!-- /.control-sidebar -->
<!-- Add the sidebar's background. This div must be placed
     immediately after the control sidebar -->
<div class="control-sidebar-bg"></div>