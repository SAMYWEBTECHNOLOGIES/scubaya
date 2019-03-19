<div class="ui mini modal scu-signin">
    <i class="close icon"></i>
    <div class="header text-center">
        <img alt="Scubaya - your dive buddy" src="{{ asset('assets/images/logo/Scubaya-text-logo-original-color.png')}}" width="110"/>
    </div>
    <div class="content">

        <div class="login-box-body">
            <form class="ui form" method="post" action="{{route('scubaya::user::login',['redirect'=> urlencode(\Illuminate\Support\Facades\Request::fullUrl())])}}">
                @if(Session::has('error'))
                    <div class="ui negative message">
                        <i class="close icon"></i>
                        <div class="header">
                            {{ Session::get('error') }}
                        </div>
                    </div>
                @endif

                @if(isset($_GET['error']))
                    <div class="ui negative message">
                        <i class="close icon"></i>
                        <div class="header">
                           {{$_GET['error']}}
                        </div>
                    </div>
                @endif

                @if(Session::has('success'))
                    <div class="ui success message">
                        <i class="close icon"></i>
                        <div class="header">
                            {{Session::get('success')}}
                        </div>
                    </div>
                @endif
                {{ csrf_field() }}
                <div class="field">
                    <input type="email" class="form-control" placeholder="Email" id="email1" name="email" required>
                </div>

                <div class="field">
                    <input type="password" class="form-control" placeholder="Password" id="password1" name="password" required>
                </div>

                <div class="ui two column grid">
                    <div class="column forget_password">
                        <a href="{{ route('scubaya::user::password_reset') }}">Forgot Password ?</a><br>
                    </div>
                    <div class="column text-right">
                        <button name="login" id="login" class="ui blue button">SIGN IN</button>
                    </div>
                </div>

                <p class="text-center">Don't have an account? <a href="#" class="scu-signup-btn">Create new account</a></p>
            </form>
        </div>
    </div>
</div>
