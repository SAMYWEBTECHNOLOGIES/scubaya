@extends('merchant.layouts.app')
@section('title', 'Add Location')
@section('breadcrumb')
    <li><a href="#">Dive Center</a></li>
    <li><a href="{{route('scubaya::merchant::dive_center::locations',[Auth::id()])}}">Add Location</a></li>
    <li class="active"><span>Add Location</span></li>
@endsection

@section('content')
    @include('merchant.layouts.mainheader')
    <section id="create_location_section" class="padding-20">
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title">New Locations</h3>
            </div>
            <!-- /.box-header -->
            <!-- form start -->
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        <a href="#" class="close" data-dismiss="alert">&times;</a>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form role="form" enctype="multipart/form-data" method="post" action="{{ route('scubaya::merchant::dive_center::add_location', [Auth::id()]) }}">
                {{ csrf_field() }}
                <div class="box-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="active" class="control-label" data-toggle="tooltip">Active</label></br>
                                <div class="btn-group" data-toggle="buttons">
                                    <label class="btn btn-default btn-on btn-sm @if(old('active') === '1') active @elseif(is_null(old('active'))) active @endif">
                                        <input type="radio" value="1" name="active" @if(old('active') === '1') checked @elseif(is_null(old('active'))) checked @endif>Yes</label>

                                    <label class="btn btn-default btn-off btn-sm @if(old('active') === '0') active @endif">
                                        <input type="radio" value="0" name="active" @if(old('active') === '0') checked @endif>No</label>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="name" class="control-label" data-toggle="tooltip" title="Name Of Location">Name*</label>
                                <input type="text" id="name" name="name" class="form-control" value="{{ old('name') }}" placeholder="Enter name">
                            </div>

                            <div class="form-group">
                                <label for="longitude" class="control-label" data-toggle="tooltip">Longitude*</label>
                                <input type="text" id="longitude" name="longitude" class="form-control" value="{{ old('longitude') }}" placeholder="Longitude">
                            </div>

                            <div class="form-group">
                                <label for="latitude" class="control-label" data-toggle="tooltip">Latitude*</label>
                                <input type="text" id="latitude" name="latitude" class="form-control" value="{{ old('latitude') }}" placeholder="latitude">
                            </div>

                            <div class="form-group">
                                <label for="type" class="control-label" data-toggle="tooltip">Type*</label>
                                <select id="type" name="type" class="form-control selectpicker show-tick">
                                        <option value="short_dive" >Short Dive</option>
                                        <option value="long_dive" >Long Dive</option>
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="need_a_boat" class="control-label" data-toggle="tooltip" title="Specify Boat Is Active Or Not">Need a Boat</label></br>
                                <div class="btn-group" data-toggle="buttons">
                                    <label class="btn btn-default btn-on btn-sm @if(old('need_a_boat') === '1') active @elseif(is_null(old('need_a_boat'))) active @endif">
                                        <input type="radio" value="1" name="need_a_boat" @if(old('need_a_boat') === '1') checked @elseif(is_null(old('need_a_boat'))) checked @endif>Yes</label>

                                    <label class="btn btn-default btn-off btn-sm @if(old('need_a_boat') === '0') active @endif">
                                        <input type="radio" value="0" name="need_a_boat" @if(old('need_a_boat') === '0') checked @endif>No</label>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="level" class="control-label" data-toggle="tooltip">For which diver level applicable</label>
                                <select id="level" name="level" class="form-control selectpicker show-tick">
                                    <option value="beginner" >Beginner</option>
                                    <option value="intermediate" >Intermediate</option>
                                    <option value="advanced" >Advanced</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="image" class="control-label" data-toggle="tooltip"><i class="fa fa-upload" aria-hidden="true"></i> Upload Image*</label>
                                <input type="file" name="image" class="form-control" onchange="readURL(this);">
                            </div>

                            <div class="form-group">
                                <div class=" ">
                                    <div class="form-group">

                                        <div id="location" style="width: 100%; height: 500px"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="box-footer">
                    <a href="{{ route('scubaya::merchant::dive_center::locations', [Auth::id()]) }}"><button type="button" class="btn btn-default">Cancel</button></a>
                    <button type="submit" class="btn btn-info pull-right">Create</button>
                </div>
            </form>
        </div>
    </section>

    <script type="text/javascript">
        function readURL(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();
                reader.onload = function (e) {
                    jQuery(input).after('<img  src="'+e.target.result+'" width="30%" height="30%">');
                };

                reader.readAsDataURL(input.files[0]);
            }
        }
    </script>

    <script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAm_-PodAPns0u0-bvF3qHHV3G_sLe0gdI"></script>
    <script type="text/javascript">
        @php
            $clientGeoInfo =   geoip($_SERVER['REMOTE_ADDR']);
        @endphp

        var markers = {
            "lat": "{{ !empty(old('latitude'))  ? old('latitude')  : $clientGeoInfo['lat'] }}",
            "lng": "{{ !empty(old('longitude')) ? old('longitude') : $clientGeoInfo['lon'] }}"
        };

        window.onload = function () {
            var mapOptions = {
                center      : new google.maps.LatLng(markers.lat, markers.lng),
                zoom        : 8,
                scrollwheel : false,
                mapTypeId   : google.maps.MapTypeId.ROADMAP
            };

            var geocoder    = geocoder = new google.maps.Geocoder();
            var map         = new google.maps.Map(document.getElementById("location"), mapOptions);
            var myLatlng    = new google.maps.LatLng(markers.lat, markers.lng);

            var marker      = new google.maps.Marker({
                position    : myLatlng,
                map         : map,
                draggable   : true
            });

            google.maps.event.addListener(marker, "dragend", function () {
                console.log(marker);
                var lat, lng, address;
                geocoder.geocode({ 'latLng': marker.getPosition() }, function (results, status) {
                    if (status == google.maps.GeocoderStatus.OK) {

                        lat = marker.getPosition().lat();
                        lng = marker.getPosition().lng();
                        address = results[0].formatted_address;

                        jQuery('#address').val(address);
                        jQuery('#latitude').val(lat);
                        jQuery('#longitude').val(lng);
                    }
                });
            });
        };

    </script>

@endsection