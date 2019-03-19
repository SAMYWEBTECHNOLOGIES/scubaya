<nav class="navbar navbar-default navbar-fixed-top">
    <div class="container">
        <!-- Brand and toggle get grouped for better mobile display -->
        <div class="navbar-header">
            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="{{route('scubaya::admin::dashboard')}}"><img style="width:110px" src="{{ asset('assets/images/logo/scubaya_original_white.png') }}" alt="Scubaya.com"></a>
        </div>

        <!-- Collect the nav links, forms, and other content for toggling -->
        <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
            <ul class="nav navbar-nav">
                <li class="dropdown">
                    <a  class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" >Merchants<span class="caret"></span></a>
                    <ul class="dropdown-menu">
                        <li><a href="{{route('scubaya::admin::add_merchant')}}">Add Merchant</a></li>
                        <li><a href="{{route('scubaya::admin::merchants_accounts')}}">Accounts</a></li>
                        <li><a href="{{route('scubaya::admin::merchants_policies')}}">Policies</a></li>
                    </ul>
                </li>
            </ul>
            <ul class="nav navbar-nav">
                <li class="dropdown">
                    <a  class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" >Screening<span class="caret"></span></a>
                    <ul class="dropdown-menu">
                        <li><a href="#">Screening 1</a></li>
                        <li><a href="#">Screening 2</a></li>
                    </ul>
                </li>
            </ul>
            <ul class="nav navbar-nav">
                <li class="dropdown">
                    <a  class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" >Bookings<span class="caret"></span></a>
                    <ul class="dropdown-menu">
                        <li><a href="#">booking page 1</a></li>
                        <li><a href="#">booking page 2</a></li>
                    </ul>
                </li>
            </ul>

            <ul class="nav navbar-nav">
                <li class="dropdown">
                    <a  class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" >Finance<span class="caret"></span></a>
                    <ul class="dropdown-menu">
                        <li><a href="#">Tab1</a></li>
                        <li><a href="#">Tab2</a></li>
                    </ul>
                </li>
            </ul>
            <ul class="nav navbar-nav">
                <li class="dropdown">
                    <a class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" >Reports<span class="caret"></span></a>
                    <ul class="dropdown-menu">
                        <li><a href="#">Tab1</a></li>
                        <li><a href="#">Tab2</a></li>
                    </ul>
                </li>
            </ul>
            <ul class="nav navbar-nav">
                <li class="dropdown">
                    <a  class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" >Risk<span class="caret"></span></a>
                    <ul class="dropdown-menu">
                        <li><a href="#">Tab1</a></li>
                        <li><a href="#">Tab2</a></li>
                    </ul>
                </li>
            </ul>

            <ul class="nav navbar-nav">
                <li class="dropdown">
                    <a  class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" >Manage<span class="caret"></span></a>
                    <ul class="dropdown-menu">
                        <li><a href="{{route('scubaya::admin::manage_admins')}}">Admins</a></li>
                        <li><a href="{{route('scubaya::admin::manage::users')}}">Users</a></li>
                        <li><a href="{{route('scubaya::admin::user_groups')}}">User Groups</a></li>
                        <li><a href="{{route('scubaya::admin::manage::affiliates')}}">Affiliations</a></li>
                        <li><a href="{{route('scubaya::admin::manage::marine_life')}}">Marine Life</a></li>
                        <li><a href="{{route('scubaya::admin::manage::activities::index')}}">Activities</a></li>
                        <li><a href="{{route('scubaya::admin::manage::infrastructure::index')}}">Infrastructure</a></li>
                        <li><a href="{{route('scubaya::admin::manage::center_facility::index')}}">Dive Center Facility</a></li>
                        <li><a href="{{route('scubaya::admin::manage::speciality::index')}}">Specialities</a></li>
                        <li><a href="{{route('scubaya::admin::manage::gear::index')}}">Scuba Gear</a></li>
                        <li><a href="{{route('scubaya::admin::manage::payment_method::index')}}">Payment Methods</a></li>
                        <li><a href="{{route('scubaya::admin::manage::boat_types')}}">Boat types</a></li>
                        <li><a href="{{route('scubaya::admin::manage::destinations')}}">Destinations</a></li>
                        <li><a href="{{route('scubaya::admin::manage::dive_sites::index')}}">Dive Sites</a></li>
                        <li><a href="{{route('scubaya::admin::manage::popular_destinations')}}">Popular Destinations</a></li>
                        <li><a href="{{route('scubaya::admin::manage::popular_hotels')}}">Popular Hotels</a></li>
                        <li><a href="{{route('scubaya::admin::manage::popular_dive_centers')}}">Popular Dive Centers</a></li>
                        <li><a href="{{route('scubaya::admin::manage::dynamic_pages')}}">Dynamic Pages</a></li>
                        <li class="dropdown dropdown-submenu"><a href="#" class="dropdown-toggle" data-toggle="dropdown">Email Templates</a>
                            <ul class="dropdown-menu">
                                <li><a href="{{route('scubaya::admin::manage::merchant_email_template')}}">Merchant Templates</a></li>
                                <li><a href="{{route('scubaya::admin::manage::admin_email_template')}}">Admin Templates</a></li>
                                <li><a href="{{route('scubaya::admin::manage::user_email_template')}}">User Templates</a></li>
                            </ul>
                        </li>
                        <li><a href="{{route('scubaya::admin::manage::home_page')}}">Home Page</a></li>
                    </ul>
                </li>
            </ul>

            <ul class="nav navbar-nav">
                <li class="dropdown">
                    <a  class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" >Settings<span class="caret"></span></a>
                    <ul class="dropdown-menu">
                        <li><a href="{{route('scubaya::admin::currencies')}}">Currency</a></li>
                    </ul>
                </li>
            </ul>

            <ul class="nav navbar-nav navbar-right">
                <li>
                    <a href="#"><i class="fa fa-envelope" aria-hidden="false"></i></a>
                </li>
            </ul>

            <ul class="nav navbar-nav navbar-right">
                <li class=" dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                        <span class="fa fa-user"></span> 
                        <strong>{{ucfirst(Auth::user()->email)}}</strong>
                    </a>
                    <ul class="dropdown-menu admin-profile">
                        <li>
                            <div class="pull-left">
                                <a href="{{route('scubaya::admin::admin_profile')}}" class="btn btn-default btn-flat">Profile</a>
                            </div>
                            <div class="pull-right">
                                <a href="{{route('scubaya::admin::logout')}}" class="btn btn-default btn-flat">Logout</a>
                            </div>
                        </li>
                    </ul>
                </li>
            </ul>
        </div><!-- /.navbar-collapse -->
    </div><!-- /.container-fluid -->
</nav>

