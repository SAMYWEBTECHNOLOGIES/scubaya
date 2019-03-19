@extends('admin.layouts.app')
@section('title','Add Boat Types')
@section('breadcrumb')
    <li><a href="{{route('scubaya::admin::dashboard')}}">Manage</a></li>
    <li><a href="{{route('scubaya::admin::manage::boat_types')}}">Boat Types</a></li>
    <li class="active"><span>Add Boat Types</span></li>
@endsection
@php
use Jenssegers\Agent\Agent as Agent;
$Agent = new Agent();
@endphp

@section('content')
    <section class="container screen-fit">
        <div class="box box-primary container">
            <div class="box-header with-border">
                <h3 class="box-title">Add Boat Types</h3>
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

            <form role="form" method="post" action="{{route('scubaya::admin::manage::add_boat_type')}}" enctype="multipart/form-data">
                {{ csrf_field() }}
                <div class="box-body">

                        <div class="col-md-4 col-md-offset-4">
                            <div class="form-group">
                                <label for="active" class="control-label" data-toggle="tooltip">Active</label><br>
                                <div class="btn-group" id="status" data-toggle="buttons">
                                    <label class="btn btn-default btn-on btn-sm active">
                                        <input type="radio" value="1" name="active" checked>YES</label>

                                    <label class="btn btn-default btn-off btn-sm @if(old('active') === '0') active @endif">
                                        <input type="radio" value="0" name="active" @if(old('active') === '0') checked @endif>NO</label>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="name" data-toggle="tooltip">Name</label>
                                <input type="text" class="form-control" id="name" name="name">
                            </div>

                            <div class="form-group">
                                <label for="image" data-toggle="tooltip" title="Upload main image"><i class="fa fa-upload" aria-hidden="true"></i>   Upload main image</label>
                                <input type="file" class="form-control" id="image" name="image" onchange="readURL(this);">
                            </div>
                        </div>
                    </div>

                    <div class="box-footer">
                        <a href="{{ route('scubaya::admin::manage::boat_types') }}"><button type="button" class="btn btn-default">Cancel</button></a>
                        <button type="submit" class="btn btn-info pull-right">Save</button>
                    </div>
            </form>
        </div>
    </section>

    <script type="text/javascript">
        function readURL(input) {
            if (input.files && input.files[0] && input.files.length == 1 ) {
                var reader = new FileReader();
                reader.onload = function (e) {
                    jQuery(input).after('<img  src="'+e.target.result+'" width="30%" height="30%">');

                };
                reader.readAsDataURL(input.files[0]);
            }else{
                var i =1;
                for(i;i<input.files.length+1;i++){
                    var reader = new FileReader();
                    reader.onload = function (e) {
                        jQuery(input).after('<img src="'+e.target.result+'" width="30%" height="30%">');
                    };
                    reader.readAsDataURL(input.files[i-1]);
                }
            }
        }
    </script>

@stop