@extends('merchant.layouts.app')
@section('title', 'Manage Dive Center')
@section('breadcrumb')
    <li><a href="#">Settings</a></li>
    <li class="active"><span>Account Configuration</span></li>
@endsection

@section('content')
    @include('merchant.layouts.mainheader')

    <section class="padding-20">
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title">Account Configuration : <strong class="blue"><span>{{$accountDetail->email}}</span></strong> </h3>
            </div>
            <!-- /.box-header -->
            <!-- form start -->
            @if ($errors->any())
                <div class="row margin-top-10">
                    <div class="col-md-4 col-md-offset-4 alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            @endif
            @if (session('success'))
                <div class="alert alert-success">
                    <a href="#" class="close" data-dismiss="alert">&times;</a>
                    <p>{{ session('success') }}</p>
                </div>
            @endif
            <div class="box-body">
                <form method="post" id="account_configuration_form" action="{{ route('scubaya::merchant::settings::account_configuration', [Auth::id()]) }}" enctype="multipart/form-data">
                    {{csrf_field()}}

                    <div class="row">
                        <div class="col-md-4 col-md-offset-4">

                            <div class="form-group">
                                <label for="first_name">First Name : -</label>
                                <input name="first_name" id="first_name" type="text" value="{{$accountDetail->first_name}}" class="form-control">
                            </div>
                            <div class="form-group">
                                <label for="last_name">Last Name : -</label>
                                <input name="last_name" id="last_name" type="text" value="{{$accountDetail->last_name}}" class="form-control">
                            </div>
                            <div class="form-group">
                                <label for="email">Email : -</label>
                                <input name="email" id="email" type="text" value="{{$accountDetail->email}}" class="form-control">
                            </div>
                            <div class="form-group">
                                <label for="password" data-toggle="tooltip">Change Password</label>
                                <input name="password" id="password" type="password" class="form-control">
                            </div>
                            <div class="form-group">
                                <label for="password_confirmation" data-toggle="tooltip">Confirm Password</label>
                                <input name="password_confirmation" id="password_confirmation" type="password" class="form-control">
                            </div>
                        </div>
                    </div>

                    <div class="box-footer">
                        <a href="{{ route('scubaya::merchant::dashboard', [Auth::id()]) }}"><button type="button" class="btn btn-default">Cancel</button></a>
                        <button type="submit" class="btn btn-info pull-right" id="submit" data-toggle="modal" data-target="#verification-form-modal">Save</button>
                    </div>
                </form>
            </div>

        </div>
    </section>
    <script type="text/javascript">
        $( "#account_configuration_form" ).validate({
            rules: {
                first_name:{
                    required: true
                },
                last_name:{
                    required: true
                },
                email:{
                    required: true
                }
                /*password: "required",
                password_confirmation: {
                    equalTo: "#password"
                }*/
            },
            messages:{
                password_confirmation:{
                    equalTo:"Password didn't match, enter again"
                }
            }
        });
    </script>

@endsection