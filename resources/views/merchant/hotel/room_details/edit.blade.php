@extends('merchant.layouts.app')
@section('title', 'Edit Room')
@section('breadcrumb')
    <li><a href="#">Hotel</a></li>
    <li class="active"><a href="{{route('scubaya::merchant::hotels',[Auth::id()])}}">Manage Hotels</a></li>
    <li class="active"><a href="{{route('scubaya::merchant::all_rooms',[Auth::id(),$room['hotel_id']])}}">All Rooms</a></li>
    <li class="active"><span>{{ $room['name'] }}</span></li>
@endsection

@section('content')
    @include('merchant.layouts.mainheader')

    <section id="edit_room_section" class="padding-20">
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title">@lang('merchant_hotel.edit_room')</h3>
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

            <form role="form" method="post" action="{{ route('scubaya::merchant::update_room', [Auth::id(), $room['hotel_id'], $room['id']]) }}" enctype="multipart/form-data">
                {{ csrf_field() }}
                <div class="box-body">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="room_type" class="control-label">@lang('merchant_hotel.type')*</label>
                                <select id="room_type" name="room_type" class="form-control select2" tabindex="-1">
                                    <option value="" disabled selected>-- Select Room Type --</option>
                                    @if(count($roomTypes) > 0)
                                        @foreach($roomTypes as $type)
                                            <option value="{{ $type->room_type }}" @if($room['type'] == $type->room_type) selected @endif>{{ $type->room_type }}</option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="room_name">@lang('merchant_hotel.name')</label>
                                <input type="text" class="form-control" id="room_name" placeholder="Enter Name" name="room_name" value="{{ $room['name'] }}">
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="room_number">@lang('merchant_hotel.number')</label>
                                <input type="text" class="form-control" id="room_number" placeholder="Enter Number" name="room_number" value="{{ $room['number'] }}">
                                <input type="hidden" class="form-control" id="room_number" placeholder="Enter Number" name="old_room_number" value="{{ $room['number'] }}">
                            </div>

                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="floor">@lang('merchant_hotel.floor')</label>
                                <input type="text" class="form-control" id="floor" placeholder="Enter Floor" name="floor" value="{{ $room['floor'] }}">
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="max_people" class="control-label">@lang('merchant_hotel.max_people')*</label>
                                <select id="max_people" name="max_people" class="form-control">
                                    <?php for($i = 1; $i <= config('scubaya.max_people_in_room'); $i++){ ?>
                                    <option value="{{ $i }}" @if($room['max_people'] == $i) selected @endif>{{ $i }}</option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="=image">@lang('merchant_hotel.room_image')</label>
                                <input type="file" class="form-control" name="image">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="room_features" class="control-label">@lang('merchant_hotel.features')</label></br>
                                <?php $roomFeatures =   json_decode($room['features']); ?>

                                @if(count($features) > 0)
                                    @foreach($features as $feature)
                                        <input type="checkbox" @if(!empty($roomFeatures) && in_array($feature->feature_description, $roomFeatures)) checked @endif value="{{$feature->feature_description}}" name="features[]"> {{$feature->feature_description}} </br>
                                    @endforeach
                                @else
                                    <p>@lang('merchant_hotel.not_available')</p>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="row margin-bottom-10">
                        <div class="col-md-4">
                            <h4 class="blue">Add Description</h4>
                        </div>
                    </div>

                    <div class="row margin-bottom-10">
                        <div class="col-md-12">
                            <div class="form-group">
                                <textarea class="form-control"  placeholder="Enter Room Description" id="room_description" name="room_description">{!! $room['description']  !!}</textarea>
                            </div>
                        </div>
                    </div>
                <!-- /.box-body -->

                <div class="box-footer">
                    <a href="{{ route('scubaya::merchant::all_rooms', [Auth::id(), $room['hotel_id']]) }}"><button type="button" class="btn btn-default">@lang('merchant_hotel.cancel')</button></a>
                    <button type="submit" class="btn btn-info pull-right">@lang('merchant_hotel.update')</button>
                </div>
                <!-- /.box-footer -->
                </div>
            </form>
        </div>
    </section>

    <script type="text/javascript">
        jQuery(document).ready(function () {
            $('#room_description').summernote({
                height: 300,
            });
        });
    </script>
@endsection
