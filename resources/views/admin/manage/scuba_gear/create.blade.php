@extends('admin.layouts.app')
@section('title','Add Gear')
@section('breadcrumb')
    <li><a href="{{route('scubaya::admin::dashboard')}}">Manage</a></li>
    <li><a href="{{route('scubaya::admin::manage::infrastructure::index')}}">Gears</a></li>
    <li class="active"><span>Add Gear</span></li>
@endsection

@section('content')
    <section class="container screen-fit">
        <div class="box box-primary container">
            <div class="box-header with-border">
                <h3 class="box-title">Add Gear</h3>
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

            <form role="form" method="post" action="{{route('scubaya::admin::manage::gear::create')}}" enctype="multipart/form-data">
                {{ csrf_field() }}
                <div class="box-body">
                    <div class="col-md-4 col-md-offset-4">
                        <div class="form-group">
                            <label for="name" data-toggle="tooltip">Name</label>
                            <input type="text" class="form-control" id="name" name="name" placeholder="Enter Name" value="{{ old('name') }}">
                        </div>

                        <div class="form-group">
                            <label for="name" data-toggle="tooltip">Category</label>
                            <select name="category" class="form-control">
                                <option value="child" @if(old('category') == 'child') selected @endif>Children</option>
                                <option value="adult" @if(old('category') == 'adult') selected @endif>Adult</option>
                                <option value="other" @if(old('category') == 'other') selected @endif>Other</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="icon" data-toggle="tooltip" title="Upload Icon"><i class="fa fa-upload" aria-hidden="true"></i>   Upload Icon</label>
                            <input type="file" class="form-control" id="icon" name="icon">
                        </div>
                    </div>
                </div>

                <div class="box-footer">
                    <a href="{{ route('scubaya::admin::manage::gear::index') }}"><button type="button" class="btn btn-default">Cancel</button></a>
                    <button type="submit" class="btn btn-info pull-right">Save</button>
                </div>
            </form>
        </div>
    </section>
@stop