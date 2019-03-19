{{--
* Created by PhpStorm.
* User: siddharth
* Date: 29/1/18
* Time: 12:43 PM
--}}
@extends('admin.layouts.app')
@section('title','Edit Boat Types')
@section('breadcrumb')
    <li><a href="{{route('scubaya::admin::dashboard')}}">Manage</a></li>
    <li><a href="{{route('scubaya::admin::manage::boat_types')}}">Boat Types</a></li>
    <li class="active"><span>{{$data->name}}</span></li>
@endsection
@section('content')
    <section class="container screen-fit">
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title">Edit Boat Types</h3>
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

            <form role="form" method="post" action="{{route('scubaya::admin::manage::edit_boat_type',[$id])}}" enctype="multipart/form-data">
                {{ csrf_field() }}
                <div class="box-body">
                      <div class="col-md-4 col-md-offset-4">
                            <div class="form-group">
                                <label for="active" class="control-label" data-toggle="tooltip">Active</label><br>
                                <div class="btn-group" id="status" data-toggle="buttons">
                                    <label class="btn btn-default btn-on btn-sm @if($data->active) active @endif">
                                        <input type="radio" value="1" name="active" @if($data->active) checked @endif>YES</label>

                                    <label class="btn btn-default btn-off btn-sm @if(!$data->active) active @endif">
                                        <input type="radio" value="0" name="active" @if(!$data->active) checked @endif>NO</label>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="name" data-toggle="tooltip">Name</label>
                                <input type="text" class="form-control" id="name" name="name" value="{{$data->name}}">
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

@stop
