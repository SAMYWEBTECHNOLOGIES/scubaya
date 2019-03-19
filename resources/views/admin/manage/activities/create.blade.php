@extends('admin.layouts.app')
@section('title','Add Activity')
@section('breadcrumb')
    <li><a href="{{route('scubaya::admin::dashboard')}}">Manage</a></li>
    <li><a href="{{route('scubaya::admin::manage::activities::index')}}">Activities</a></li>
    <li class="active"><span>Add Activity</span></li>
@endsection

@php
    use Jenssegers\Agent\Agent as Agent;
    $Agent = new Agent();
@endphp

@section('content')
    <section class="container screen-fit">
        <div class="box box-primary container">
            <div class="box-header with-border">
                <h3 class="box-title">Add Activity</h3>
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

            <form role="form" method="post" action="{{route('scubaya::admin::manage::activities::create')}}" enctype="multipart/form-data">
                {{ csrf_field() }}
                <div class="box-body">
                    <div class="col-md-4 col-md-offset-4">
                        <div class="form-group">
                            <label for="name" data-toggle="tooltip">Name</label>
                            <input type="text" class="form-control" id="name" name="name" placeholder="Enter Name" value="{{ old('name') }}">
                        </div>

                        <div class="form-group">
                            <label for="icon" data-toggle="tooltip" title="Upload Icon"><i class="fa fa-upload" aria-hidden="true"></i>   Upload Icon</label>
                            <input type="file" class="form-control" id="icon" name="icon">
                        </div>

                        <div class="form-group">
                            <label for="active" class="control-label" data-toggle="tooltip">Non Diving</label><br>
                            <div class="btn-group"  data-toggle="buttons">
                                <label class="btn btn-default btn-on btn-sm @if(old('non_diving') === '1') active @elseif(is_null(old('non_diving'))) active @endif">
                                <input type="radio" value="1" name="non_diving" @if(old('non_diving') === '1') checked @elseif(is_null(old('non_diving'))) checked @endif>YES</label>

                                <label class="btn btn-default btn-off btn-sm @if(old('non_diving') === '0') active @endif">
                                <input type="radio" value="0" name="non_diving" @if(old('non_diving') === '0') checked @endif>NO</label>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="box-footer">
                    <a href="{{ route('scubaya::admin::manage::activities::index') }}"><button type="button" class="btn btn-default">Cancel</button></a>
                    <button type="submit" class="btn btn-info pull-right">Save</button>
                </div>
            </form>
        </div>
    </section>
@stop