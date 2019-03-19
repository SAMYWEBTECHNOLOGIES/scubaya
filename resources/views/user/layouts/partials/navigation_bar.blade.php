<section id="user-head-section">
    <nav class="navbar navbar-default navbar-fixed-top">
        <div class="container">
            <!-- Brand and toggle get grouped for better mobile display -->
            <div class="navbar-header">
                <a class="navbar-brand" href="{{route('scubaya::admin::dashboard')}}"><img style="width:110px" src="{{ asset('assets/images/logo/scubaya_original_white.png') }}" alt="Scubaya.com"></a>
            </div>

            <!-- Collect the nav links, forms, and other content for toggling -->
            <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                <ul class="nav navbar-nav">
                    <li><a href="#" class="smoothScroll">User Profile</a></li>
                </ul>

                <ul class="nav navbar-nav">
                    <li class="dropdown">
                        <a href="#" class="smoothScroll">Dive Profile</a>
                    </li>
                </ul>

                <ul class="nav navbar-nav">
                    <li><a href="#" class="smoothScroll">Bookings</a></li>
                </ul>

                <ul class="nav navbar-nav">
                    <li><a href="#" class="smoothScroll">Dive log</a></li>
                </ul>

                <ul class="nav navbar-nav navbar-right">
                    <li>
                        <a href="#"><i class="fa fa-envelope" aria-hidden="false"></i></a>
                    </li>
                </ul>

                <ul class="nav navbar-nav navbar-right">
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                            <span class="fa fa-user"></span> 
                            <strong>{{ucfirst(Auth::user()->first_name)}}</strong>
                        </a>
                        <ul class="dropdown-menu">
                            <li><a href="{{route('scubaya::user::logout')}}">Logout</a></li>
                            <li><a href="{{route('scubaya::user::userprofile_edit',[Auth::id()])}}">Edit Profile</a></li>
                        </ul>
                    </li>
                </ul>
            </div><!-- /.navbar-collapse -->
        </div><!-- /.container-fluid -->
    </nav>
</section>