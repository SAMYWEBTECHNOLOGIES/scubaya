@extends('admin.layouts.app')
@section('title','Add Merchant')
@section('breadcrumb')
    <li><a href="{{route('scubaya::admin::dashboard')}}">Merchants</a></li>
    <li class="active"><span>Add Merchant</span></li>
@endsection
@section('content')
    <section id="merchant_sign_up" class="margin-top-60">
        <div class="scubaya-box">
            <div class="scubaya-box-body">
                <h2 class="scubaya-box-msg text-center">Create Merchant</h2>
                <form class="sign_up_form" id="merchant_sign_up_form"  name="sign_up_form" method="post" action="{{route('scubaya::admin::add_merchant')}}">
                {{csrf_field()}}

                <!-- for showing errors-->
                    @if ($errors->add_merchant->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->add_merchant->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    @if (session()->has('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif
                    <div class="form-group">
                        <label for="company_name">Company Name</label>
                        <input type="text" class="form-control" placeholder="" id="company_name" name="company_name">
                    </div>
                    <div class="form-group">
                        <label for="address">Address</label>
                        <input type="text" class="form-control" placeholder="" id="address" name="address">
                    </div>

                    <div class="form-group">
                        <label for="country">City</label>
                        <input type="text" class="form-control" placeholder="" id="city" name="city">
                    </div>

                    <div class="form-group">
                        <label for="merchant_email">Email</label>
                        <input type="email" class="form-control" placeholder="" id="merchant_email" name="merchant_email">
                    </div>

                    {{--<div class="form-group">
                        <label for="merchant_password">Password</label>
                        <input type="password" class="form-control" placeholder="" id="merchant_password" name="merchant_password">
                    </div>--}}

                    <div class="row">
                        <div class="col-md-4 pull-left">
                            <div class="form-group">
                                <input type="submit" class="form-control btn btn-primary" id="save" name="save" value="Save">
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </section>
@endsection
