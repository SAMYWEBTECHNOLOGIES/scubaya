@extends('admin.layouts.app')
@section('title','Manage admins')
@section('breadcrumb')
    <li><a href="#">Admin Profile</a></li>
@endsection
@section('content')
    <section class="container screen-fit">
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title">Admin Profile</h3>
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
            @if(Session::has('success'))
                <div class="alert alert-success">
                    <a href="#" class="close" data-dismiss="alert">&times;</a>
                    <p>{{ Session::get('success') }}</p>
                </div>
            @endif

            <form role="form" id ="admin_configuration_form" method="post" action="{{route('scubaya::admin::admin_profile')}}" enctype="multipart/form-data">
                {{ csrf_field() }}
                <div class="box-body">
                    <div class="col-md-4 col-md-offset-4">
                        <div class="form-group">
                            <label for="name" data-toggle="tooltip">First Name</label>
                            <input type="text" class="form-control" id="first_name" name="first_name" placeholder="Enter First Name" value="{{$adminDetail->first_name}}">
                        </div>
                        <div class="form-group">
                            <label for="name" data-toggle="tooltip"> Last Name</label>
                            <input type="text" class="form-control" id="last_name" name="last_name" placeholder="Enter Last Name" value="{{ $adminDetail->last_name}}">
                        </div>
                        <div class="form-group">
                            <label for="email" data-toggle="tooltip">Email</label>
                            <input type="email" class="form-control" placeholder="Email" id="email" name="email" value="{{$adminDetail->email}}">
                        </div>
                        <div class="form-group">
                            <label for="title" data-toggle="tooltip">Title</label>
                            <input type="text" class="form-control" id="title" name="title" placeholder="Enter Title" value ="{{$adminTitle->title}}">
                        </div>
                        <div class="form-group">
                            <label for="password" data-toggle="tooltip">Password</label>
                            <input type="password" class="form-control" id="password" name="password" placeholder="Enter Password">
                        </div>
                        <div class="form-group">
                            <label for="password_confirmation" data-toggle="tooltip">Confirm Password</label>
                            <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" placeholder="Enter Confirm Password">
                        </div>
                    </div>
                </div>

                <div class="box-footer">
                    <a href="{{ route('scubaya::admin::dashboard') }}"><button type="button" class="btn btn-default">Cancel</button></a>
                    <button type="submit" class="btn btn-info pull-right">Save</button>
                </div>
            </form>
        </div>
    </section>

    <script type="text/javascript">
        $( "#admin_configuration_form").validate({
            rules: {
                first_name:{
                    required: true
                },
                last_name:{
                    required: true
                },
                email:{
                    required: true
                },
                password_confirmation: {
                    equalTo: "#password"
                }
            },
            messages:{
                password_confirmation:{
                    equalTo:"Password didn't match,enter again"
                }
            }
        });
    </script>
@endsection