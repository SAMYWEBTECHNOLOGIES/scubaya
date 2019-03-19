
<section id="head-section">
    <div id="merchant_navigation" class="navbar navbar-default navbar-fixed-top">
        @if(Auth::guard('merchant')->check())
        <div class="container">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="#">
                    <img src="{{asset('assets/images/logo/Scubaya-text-logo-original-white.png')}}" width="110px" alt="Welcome to Scubaya" class="image">
                </a>
            </div>
            <div class="navbar-collapse collapse">
                <ul class="nav navbar-nav">
                    <li><a href="{{ route('scubaya::merchant::dashboard', [Auth::guard('merchant')->user()->id]) }}" class="smoothScroll">Dashboard</a></li>

                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Bookings <span class="caret"></span></a>
                        <ul class="dropdown-menu">
                            <li><a href="#">All Bookings</a></li>
                            <li><a href="#">New Booking</a></li>
                            <li><a href="#">Icall Feed</a></li>
                            <li><a href="#">Icall Import</a></li>
                            <li><a href="#">Booking Settings</a></li>
                        </ul>
                    </li>

                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Reports <span class="caret"></span></a>
                        <ul class="dropdown-menu">
                            <li><a href="#">Charts</a></li>
                            <li><a href="#">Arriving Today</a></li>
                            <li><a href="#">Departure Today</a></li>
                            <li><a href="#">Cleaning Schedule</a></li>
                            <li><a href="#">Percentage Booked</a></li>
                            <li><a href="#">Report Settings</a></li>
                        </ul>
                    </li>

                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Invoices <span class="caret"></span></a>
                        <ul class="dropdown-menu">
                            <li><a href="#">All Invoices</a></li>
                            <li><a href="#">SCBY Invoices</a></li>
                            <li><a href="#">Guest Invoices</a></li>
                            <li><a href="#">Open Invoices</a></li>
                            <li><a href="#">Invoice Settings</a></li>
                        </ul>
                    </li>

                    {{--<li class="dropdown">--}}
                        {{--<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Guests <span class="caret"></span></a>--}}
                        {{--<ul class="dropdown-menu">--}}
                            {{--<li><a href="#">All Guests</a></li>--}}
                            {{--<li><a href="#">New Guest</a></li>--}}
                            {{--<li><a href="#">Guest Types</a></li>--}}
                            {{--<li><a href="#">Guest Settings</a></li>--}}
                        {{--</ul>--}}
                    {{--</li>--}}

                    {{--<li class="dropdown">--}}
                        {{--<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Hotel <span class="caret"></span></a>--}}
                        {{--<ul class="dropdown-menu">--}}
                            {{--<li><a href="{{ route('scubaya::merchant::all_rooms', [Auth::guard('merchant')->user()->id]) }}">All Rooms</a></li>--}}
                            {{--<li><a href="{{ route('scubaya::merchant::create_room', [Auth::guard('merchant')->user()->id]) }}">New Room</a></li>--}}
                            {{--<li><a href="{{ route('scubaya::merchant::room_features', [Auth::guard('merchant')->user()->id]) }}">Room Features</a></li>--}}
                            {{--<li><a href="{{ route('scubaya::merchant::room_types', [Auth::guard('merchant')->user()->id]) }}">Room Types</a></li>--}}
                            {{--<li><a href="{{ route('scubaya::merchant::all_tariffs', [Auth::guard('merchant')->user()->id]) }}">Room Pricing</a></li>--}}
                            {{--<li><a href="{{ route('scubaya::merchant::hotel_information', [Auth::guard('merchant')->user()->id]) }}">Hotel General Information</a></li>--}}
                        {{--</ul>--}}
                    {{--</li>--}}

                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Shop <span class="caret"></span></a>
                        <ul class="dropdown-menu">
                            <li><a href="#">All Shop Items</a></li>
                            <li><a href="#">New Product</a></li>
                            <li><a href="#">Product Features</a></li>
                            <li><a href="#">Rental Products</a></li>
                            <li><a href="#">Sell Products</a></li>
                            <li><a href="#">Courses</a></li>
                            <li><a href="#">Tours</a></li>
                            <li><a href="#">Product Settings</a></li>
                        </ul>
                    </li>

                    {{--<li class="dropdown">--}}
                        {{--<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Dive Center <span class="caret"></span></a>--}}
                        {{--<ul class="dropdown-menu">--}}
                            {{--<li><a href="#">Dashboard</a></li>--}}
                            {{--<li><a href="{{route('scubaya::merchant::instructor',[Auth::guard('merchant')->user()->id])}}">Instructors</a></li>--}}
                            {{--<li><a href="#">Work Planning</a></li>--}}
                            {{--<li><a href="#">Dive Center Planning</a></li>--}}
                        {{--</ul>--}}
                    {{--</li>--}}

                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Settings <span class="caret"></span></a>
                        <ul class="dropdown-menu">
                            <li><a href="#">Account Details</a></li>
                            {{--<li><a href="#">Account Configuration</a></li>--}}
                            {{--<li><a href="{{ route('scubaya::merchant::account_verification', [Auth::guard('merchant')->user()->id]) }}">Account Verification</a></li>--}}
                            {{--<li><a href="#">Websites</a></li>--}}
                            {{--<li><a href="#">Contact Information</a></li>--}}
                            {{--<li><a href="#">Users</a></li>--}}
                            {{--<li><a href="#">Payment Gateways</a></li>--}}
                            {{--<li><a href="{{ route('scubaya::merchant::pricing_settings', [Auth::guard('merchant')->user()->id]) }}">Pricing Settings</a></li>--}}
                            {{--<li><a href="#">API Keys</a></li>--}}
                            {{--<li><a href="#">Email Templates</a></li>--}}
                            {{--<li><a href="#">Media Center</a></li>--}}
                        </ul>
                    </li>
                </ul>
                <ul class="nav navbar-nav navbar-right">
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><span class="fa fa-user"></span> {{ ucfirst(Auth::guard('merchant')->user()->first_name) }} <span class="caret"></span></a>
                        <ul class="dropdown-menu">
                            <li><a href="{{ route('scubaya::
                            instructor::profile',[Auth::id]) }}">Profile</a></li>
                            <li><a href="{{ route('scubaya::merchant::logout') }}">Logout</a></li>
                        </ul>
                    </li>
                </ul>
            </div><!--/.nav-collapse -->
        </div>
        @endif
    </div>
</section>