@extends('admin.layouts.app')
@section('title','Edit Facility')
@section('breadcrumb')
    <li><a href="{{route('scubaya::admin::dashboard')}}">Manage</a></li>
    <li><a href="{{route('scubaya::admin::manage::center_facility::index')}}">Facilities</a></li>
    <li class="active"><span>Edit Facility</span></li>
@endsection

@section('content')
    <section class="container screen-fit">
        <div class="box box-primary container">
            <div class="box-header with-border">
                <h3 class="box-title">Edit Facility</h3>
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

            <form role="form" method="post" action="{{route('scubaya::admin::manage::center_facility::update', [ @$facility->id ])}}" enctype="multipart/form-data">
                {{ csrf_field() }}
                <div class="box-body">
                    <div class="col-md-4 col-md-offset-4">
                        <div class="form-group">
                            <label for="name" data-toggle="tooltip">Name</label>
                            <input type="text" class="form-control" id="name" name="name" placeholder="Enter Name" value="{{ @$facility->name }}">
                        </div>

                        <div class="form-group">
                            <label for="icon" data-toggle="tooltip" title="Upload Icon"><i class="fa fa-upload" aria-hidden="true"></i>   Upload Icon</label>
                            <input type="file" class="form-control" id="icon" name="icon">
                        </div>

                        <div class="form-group">
                            <img src="{{ asset('assets/images/scubaya/dive_center_facility/'.$facility->id.'-'.$facility->icon) }}" width="100" height="100" alt="{{ $facility->icon }}">
                        </div>
                    </div>
                </div>

                <div class="box-footer">
                    <a href="{{ route('scubaya::admin::manage::center_facility::index') }}"><button type="button" class="btn btn-default">Cancel</button></a>
                    <button type="submit" class="btn btn-info pull-right">Update</button>
                </div>
            </form>
        </div>
    </section>
@stop