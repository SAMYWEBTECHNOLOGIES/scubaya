<section id="head-section">
    <div id="navigation" class="navbar navbar-default navbar-fixed-top">
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
                {{--<ul class="nav navbar-nav">--}}
                    {{--<li><a href="#" class="smoothScroll">About Us</a></li>--}}
                    {{--<li><a href="#" class="smoothScroll">FAQ</a></li>--}}
                    {{--<li><a href="#" class="smoothScroll">Contact Us</a></li>--}}
                {{--</ul>--}}

                <ul class="nav navbar-nav navbar-right header-social-links" >
                    {{--@if (Auth::guest())--}}
                        {{-- dropdown for sign in --}}
                        {{--<li class="dropdown">--}}
                            {{--<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Sign in <span class="caret"></span></a>--}}
                            {{--<ul class="dropdown-menu">--}}
                                {{--<li><a href="{{ route('scubaya::merchant::index') }}">Merchant</a></li>--}}
                                {{--<li><a href="#">Diver</a></li>--}}
                            {{--</ul>--}}
                        {{--</li>--}}

                        {{--<li><a href="{{ route('scubaya::register::index') }}">Sign up</a></li>--}}
                    {{--@else--}}
                        {{--<li><a href="/home">{{ Auth::user()->name }}</a></li>--}}
                    {{--@endif--}}
                    <li><a href="https://twitter.com/scubayacom" target="_blank"><i class="fa fa-twitter fa-2x" aria-hidden="true"></i></a></li>
                    <li><a href="https://www.facebook.com/scubayacom/" target="_blank"><i class="fa fa-facebook fa-2x" aria-hidden="true"></i></a></li>
                    <li><a href="https://www.instagram.com/scubayacom/" target="_blank"><i class="fa fa-instagram fa-2x" aria-hidden="true"></i></a></li>
                </ul>
            </div><!--/.nav-collapse -->
        </div>
    </div>
</section>