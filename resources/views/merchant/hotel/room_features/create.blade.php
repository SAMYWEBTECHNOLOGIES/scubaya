@extends('merchant.layouts.app')
@section('title', 'New Feature')
@section('breadcrumb')
    <li><a href="#">Hotel</a></li>
    <li class="active"><a href="{{route('scubaya::merchant::room_features',[Auth::id()])}}">Room Features</a></li>
    <li class="active"><span>Create Room Feature</span></li>
@endsection

@section('content')
    @include('merchant.layouts.mainheader')

    <section id="create_room_feature_section" class="padding-20">
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title">New Feature</h3>
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

            <form role="form" enctype="multipart/form-data" method="post" action="{{ route('scubaya::merchant::save_room_feature', [Auth::id()]) }}">
                {{ csrf_field() }}
                <div class="box-body">
                    <div class="col-md-4 col-md-offset-4">
                        <div class="form-group">
                            <label for="feature_description" class="control-label">Feature Description*</label>
                            <input type="text" class="form-control" id="feature_description"  placeholder="Enter Description" name="feature_description" value="{{old('feature_description')}}">
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
                    <button type="submit" class="btn btn-info pull-right">Create</button>
                </div>
                <!-- /.box-footer -->
            </form>
        </div>
    </section>
@endsection
