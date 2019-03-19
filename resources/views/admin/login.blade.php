@extends('admin.layouts.app')
@section('title','Login')
@section('content')
    <body id="admin-login-section" style="margin: -30px">
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
                                <a href="#" class="close" data-dismiss="alert">&times;</a>
                                <p>{{ Session::get('error') }}</p>
                            </div>
                        @endif
                        <h1 class="login-box-msg text-center">Scubaya.com</h1>
                        <h4 class="login-box-msg text-center">Employees are only able to connect</h4>
                        <form id="admin_login"  method="post" action="{{route('scubaya::admin::login')}}">
                            <input type="hidden" name="_token" value="{{csrf_token()}}">
                            <div class="form-group">
                                {{--admin email--}}
                                <input type="email" class="form-control" placeholder="Email" id="email" name="email">
                            </div>
                            <div class="form-group">
                                {{--admin password--}}
                                <input type="password" class="form-control" placeholder="Password" name="password" id="password">
                            </div>
                            <div class="form-group">
                                <button name="login" id="login" class="btn btn-block btn-primary btn-lg">SIGN IN</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
    </body>

    <script type="text/javascript">
        /*jquery validation*/
        jQuery("#admin_login").validate({
            rules:{
                email       :   {required:true,email:true},
                password    :   "required"
            },
            messages:{
                email       :   "Please enter a valid Email address",
                password    :   "Please enter the corresponding password"
            }
        });
    </script>
@endsection
