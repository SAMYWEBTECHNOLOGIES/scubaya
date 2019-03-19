@extends('merchant.layouts.app')
@section('title', 'New Room Type')
@section('breadcrumb')
    <li><a href="#">Hotel</a></li>
    <li class="active"><a href="{{route('scubaya::merchant::room_types',[Auth::id()])}}">Room Types</a></li>
    <li class="active"><span>Create Room Types</span></li>
@endsection

@section('content')
    @include('merchant.layouts.mainheader')

    <section id="create_room_type_section" class="padding-20">
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title">New Room Type</h3>
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

            <form role="form" enctype="multipart/form-data" method="post" action="{{ route('scubaya::merchant::save_room_type', [Auth::id()]) }}">
                {{ csrf_field() }}
                <div class="box-body">
                    <div class="col-md-4 col-md-offset-4">
                        <div class="form-group">
                            <label for="room_type" class="control-label">Room Type*</label>
                            <input type="text" class="form-control" id="room_type"  placeholder="Enter Room Type" name="room_type" value="{{ old('room_type') }}">
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
                    <button type="submit" class="btn btn-info pull-right">Create</button>
                </div>
                <!-- /.box-footer -->
            </form>
        </div>
    </section>
@endsection
