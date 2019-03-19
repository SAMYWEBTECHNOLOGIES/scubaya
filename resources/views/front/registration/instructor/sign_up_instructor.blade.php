@extends('front.layouts.app')
@section('content')
    @include('front.layouts.mainheader')
    @include('front.layouts.registration_script')
    <section id="instructor_sign_up" class="margin-top-60">
        <div class="scubaya-box">
            <div class="scubaya-box-body">
                <h2 class="scubaya-box-msg text-center">Create account</h2>
                <form class="sign_up_form" id="instructor_sign_up_form"  name="sign_up_form" method="post" action="{{ route('scubaya::register::create_instructor_account') }}">
                    {{csrf_field()}}

                    <!-- for showing errors-->
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    @if (session('status'))
                        <div class="alert alert-success">
                            {{ session('status') }}
                        </div>
                    @endif

                    <div class="form-group">
                        <label for="first_name">First Name</label>
                        <input type="text" class="form-control" placeholder="" id="first_name" name="first_name" value="{{old('first_name')}}">
                    </div>

                    <div class="form-group">
                        <label for="last_name">Last Name</label>
                        <input type="text" class="form-control" placeholder="" id="last_name" name="last_name" value="{{old('last_name')}}">
                    </div>

                    <div class="form-group">
                        <label for="merchant_email">Email</label>
                        <input type="email" class="form-control" placeholder="" id="instructor_email" name="instructor_email" value="{{old('instructor_email')}}">
                    </div>

                    <div class="form-group">
                        <label for="merchant_password">Password</label>
                        <input type="password" class="form-control" placeholder="" id="merchant_password" name="merchant_password">
                    </div>
                    <p class="text-center">Already have an account ? <a href="{{route('scubaya::merchant::login')}}">Login</a></p>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="col-md-4 pull-right">
                                <div class="form-group">
                                    <a href="{{ route('scubaya::index') }}"><input type="button" class="form-control btn btn-default" id="cancel" name="cancel" value="Cancel"></a>
                                </div>
                            </div>
                            <div class="col-md-4 pull-right">
                                <div class="form-group">
                                    <input type="submit" class="form-control btn btn-primary" id="save" name="save" value="Save">
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </section>
@endsection