@extends('user.layouts.app')
@section('title','Login')
@section('content')
<body id="user-login-section" style="">
    <section id="login-section" class="container">
        <div id="navigation">
            <div class="container">
                <div class="navbar-header">
                    <img style="width:10%;margin:10px" src="{{ asset('assets/images/logo/scubaya_original_white.png') }}" alt="Scubaya.com">
                </div>

                <div class="login-box">

                    <?php use Jenssegers\Agent\Agent;
                        $agent = new Agent();
                    ?>

                    <div class="login-box-body">
                        @if(Session::has('error'))
                            <div class="alert alert-danger">
                                <p>{{ Session::get('error') }}</p>
                            </div>
                        @endif

                        @if(Session::has('success'))
                            <div class="alert alert-success">
                                <p>{{ Session::get('success') }}</p>
                            </div>
                        @endif
                        <h1 class="login-box-msg text-center">Scubaya.com</h1>
                        <h4 class="login-box-msg text-center">User Login</h4>
                        <form  method="post" action="{{route('scubaya::user::login')}}">
                            {{csrf_field()}}
                            <div class="form-group">
                                <input type="email" class="form-control" placeholder="Email" id="email" name="email" required>
                            </div>
                            <div class="form-group">
                                <input type="password" class="form-control" placeholder="Password" name="password" id="password" required>
                            </div>
                            <div class="form-group">
                                <button name="login" id="login" class="btn btn-block btn-primary btn-lg">LOG IN</button>
                            </div>
                        </form>

                        <div class="row">
                            <div class="col-md-12 text-center">
                                <a href="{{ route('scubaya::user::password_reset') }}">Forgot Password ?</a> <br>
                                Don't have an account? <a target="_blank" href="{{ route('scubaya::home', ['sign_up' => true]) }}">Create new one</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</body>
@endsection