@extends('merchant.layouts.app')
@section('title', 'Login')
@section('content')
    @include('merchant.layouts.mainheader')
    <?php use Illuminate\Support\Facades\Session;
    ?>
    <section id="login-section" class="container">
        <div id="navigation">
            <div class="container">
                <div class="login-box">
                   {{--  show messages related to verification links --}}
                    @if(session('verification_message'))
                    <div class="alert alert-danger verification_link_error">
                        {{ session('verification_message')}}
                    </div>
                    @endif

                    {{-- show error messages related to input fields --}}
                    @if (count($errors) > 0)
                        <div class="alert alert-danger">
                            <strong>Whoops!</strong> {{ trans('adminlte_lang::message.someproblems') }}<br><br>
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{!! $error !!}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    {{-- show errors when attempt to login --}}
                    @if(session('status'))
                        <div class="alert alert-danger login_error text-center">
                            {!! session('status') !!}
                        </div>
                    @endif

                    <div class="login-box-body">
                        <div class="login-logo">
                            <img src="{{asset('assets/images/logo/Scubaya-text-logo-original-color.png')}}" width="150px" alt="Welcome to Scubaya" class="image">
                        </div>

                        <form id="merchant-login-form" name="merchant-login-form" method="post" action="{{ route('scubaya::merchant::login') }}">
                            {{ csrf_field() }}
                            <div class="form-group">
                                 <input type="email" class="form-control" placeholder="Email" id="merchant_email" name="merchant_email">
                            </div>

                            <div class="form-group">
                                 <input type="password" class="form-control" placeholder="Password" id="merchant_password" name="merchant_password">
                            </div>

                            <div class="row form-group">
                                <div class="col-md-6 forget_password">
                                    <a href="{{ route('scubaya::merchant::password_reset') }}">Forgot Password ?</a><br>
                                </div>
                                <div class="col-md-6">
                                    <button name="login" id="login" class="btn btn-block btn-primary btn-lg">SIGN IN</button>
                                </div>
                            </div>
                            <p>Don't have an account? <a href="{{ route('scubaya::register::create_account') }}" class="coral">Create new account</a></p>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
