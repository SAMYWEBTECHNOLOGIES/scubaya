@extends('merchant.layouts.app')
@section('title', 'Edit Feature')
@section('breadcrumb')
    <li><a href="#">Hotel</a></li>
    <li class="active"><a href="{{route('scubaya::merchant::room_features',[Auth::id()])}}">Room Features</a></li>
    <li class="active"><span>{{ $features['feature_description'] }}</span></li>
@endsection

@section('content')
    @include('merchant.layouts.mainheader')

    <section id="edit_room_feature_section" class="padding-20">
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title">Edit Feature</h3>
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

            <form role="form" method="post" enctype="multipart/form-data" action="{{ route('scubaya::merchant::update_room_feature', [Auth::id(), $features['id']]) }}">
                {{ csrf_field() }}
                <div class="box-body">
                    <div class="col-md-4 col-md-offset-4">
                        <div class="form-group">
                            <label for="feature_description" class="control-label">Feature Description*</label>
                            <input type="text" class="form-control" id="feature_description" placeholder="Enter Description" name="feature_description" value="{{ $features['feature_description'] }}">
                        </div>

                        <div class="form-group">
                            <label for="feature_icon" class="control-label">Icon</label>
                            <input type="file" class="form-control" id="feature_icon"  name="feature_icon">
                        </div>
                    </div>
                </div>

                <!-- /.box-body -->
                <div class="box-footer">
                    <a href="{{ route('scubaya::merchant::room_features', [Auth::id()]) }}"><button type="button" class="btn btn-default">Cancel</button></a>
                    <button type="submit" class="btn btn-info pull-right">Update</button>
                </div>
                <!-- /.box-footer -->
            </form>
        </div>
    </section>
@endsection
