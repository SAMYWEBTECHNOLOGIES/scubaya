@extends('admin.layouts.app')
@section('title','Edit Gear')
@section('breadcrumb')
    <li><a href="{{route('scubaya::admin::dashboard')}}">Manage</a></li>
    <li><a href="{{route('scubaya::admin::manage::gear::index')}}">Gears</a></li>
    <li class="active"><span>Edit Infrastructure</span></li>
@endsection

@section('content')
    <section class="container screen-fit">
        <div class="box box-primary container">
            <div class="box-header with-border">
                <h3 class="box-title">Edit Gear</h3>
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

            <form role="form" method="post" action="{{route('scubaya::admin::manage::gear::update', [ @$gear->id ])}}" enctype="multipart/form-data">
                {{ csrf_field() }}
                <div class="box-body">
                    <div class="col-md-4 col-md-offset-4">
                        <div class="form-group">
                            <label for="name" data-toggle="tooltip">Name</label>
                            <input type="text" class="form-control" id="name" name="name" placeholder="Enter Name" value="{{ @$gear->name }}">
                        </div>

                        <div class="form-group">
                            <label for="name" data-toggle="tooltip">Category</label>
                            <select name="category" class="form-control">
                                <option value="child" @if(@$gear->category == 'child') selected @endif>Children</option>
                                <option value="adult" @if(@$gear->category == 'adult') selected @endif>Adult</option>
                                <option value="other" @if(@$gear->category == 'other') selected @endif>Other</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="icon" data-toggle="tooltip" title="Upload Icon"><i class="fa fa-upload" aria-hidden="true"></i>   Upload Icon</label>
                            <input type="file" class="form-control" id="icon" name="icon">
                        </div>

                        <div class="form-group">
                            <img src="{{ asset('assets/images/scubaya/gears/'.$gear->id.'-'.$gear->icon) }}" width="100" height="100" alt="{{ $gear->icon }}">
                        </div>
                    </div>
                </div>

                <div class="box-footer">
                    <a href="{{ route('scubaya::admin::manage::gear::index') }}"><button type="button" class="btn btn-default">Cancel</button></a>
                    <button type="submit" class="btn btn-info pull-right">Update</button>
                </div>
            </form>
        </div>
    </section>
@stop