@extends('merchant.layouts.app')
@section('title', 'Edit Room Type')
@section('breadcrumb')
    <li><a href="#">Hotel</a></li>
    <li class="active"><a href="{{route('scubaya::merchant::room_types',[Auth::id()])}}">Room Types</a></li>
    <li class="active"><span>{{ $roomType['room_type'] }}</span></li>
@endsection

@section('content')
    @include('merchant.layouts.mainheader')

    <section id="edit_room_type_section" class="padding-20">
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title">Edit Room Type</h3>
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

            <form role="form" enctype="multipart/form-data" method="post" action="{{ route('scubaya::merchant::update_room_type', [Auth::id(), $roomType['id']]) }}">
                {{ csrf_field() }}
                <div class="box-body">
                    <div class="col-md-4 col-md-offset-4">
                        <div class="form-group">
                            <label for="room_type" class="control-label">Room Type*</label>
                            <input type="text" class="form-control" id="room_type"  placeholder="Enter Room Type" name="room_type" value="{{ $roomType['room_type'] }}">
                        </div>

                        <div class="form-group">
                            <label for="room_type_icon" class="control-label">Icon</label>
                            <input type="file" class="form-control" id="room_type_icon"  name="room_type_icon">
                        </div>
                    </div>
                </div>

                <!-- /.box-body -->
                <div class="box-footer">
                    <a href="{{ route('scubaya::merchant::room_types', [Auth::id()]) }}"><button type="button" class="btn btn-default">Cancel</button></a>
                    <button type="submit" class="btn btn-info pull-right">Update</button>
                </div>
                <!-- /.box-footer -->
            </form>
        </div>
    </section>
@endsection
