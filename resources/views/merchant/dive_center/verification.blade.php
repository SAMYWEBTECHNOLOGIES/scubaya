@extends('merchant.layouts.app')
@section('content')
    <section id="head-section">
        <div id="merchant_navigation" class="navbar navbar-default navbar-fixed-top">
        {{--<a class="navbar-brand" href="#">--}}
            {{--<img src="{{asset('assets/images/logo/Scubaya-text-logo-original-color.png')}}" width="110px" alt="Welcome to Scubaya" class="image">--}}
        {{--</a>--}}
        </div>
    </section>
    <section id="login-section" class="container">
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    <a href="#" class="close" data-dismiss="alert">&times;</a>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        <div id="navigation">
            <div class="container">
                <div class="login-box">
                    <div class="login-box-body">
                        <div class="login-logo">
                            <img src="{{asset('assets/images/logo/Scubaya-text-logo-original-color.png')}}" width="150px" alt="Welcome to Scubaya" class="image">
                        </div>

                        <form  method="post" action="{{ route('scubaya::merchant::verify_instructor',[$request->id,$request->confirmation_code]) }}">
                            {{ csrf_field() }}
                            <div class="form-group">
                                <input type="password" class="form-control" placeholder="password" name="password">
                            </div>

                            <div class="form-group">
                                <input type="password" class="form-control" placeholder="confirm password" name="password_confirmation">
                            </div>

                            <div class="row form-group">
                                <div class="col-md-6">
                                    <button name="set_password" id="login" class="btn btn-block btn-primary btn-lg">Set Password</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection