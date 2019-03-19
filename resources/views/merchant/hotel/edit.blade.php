@extends('merchant.layouts.app')
@section('title', 'Edit Hotel General Information')
@section('breadcrumb')
    <li><a href="#">Hotel</a></li>
    <li class="active"><a href="{{route('scubaya::merchant::hotels',[Auth::id()])}}">Manage Hotel</a></li>
    <li class="active"><span>{{$hotelInfo->name}}</span></li>
@endsection


@section('content')
    @include('merchant.layouts.mainheader')

    <section id="edit_hotel_information_section" class="padding-20">
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title">Edit Hotel General Information</h3>
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

            <div class="box-body">
                <form name="hotel_information_form" method="post" action="{{ route('scubaya::merchant::edit_hotel', [Auth::id(), $hotelInfo->id]) }}" enctype="multipart/form-data">
                    {{csrf_field()}}
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="hotel_name">Name</label>
                                <input type="text" class="form-control" id="hotel_name" placeholder="Enter Name" name="hotel_name" value="{{$hotelInfo->name}}">
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="image">Upload Image</label>
                                <input type="file" class="form-control" name="image">
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="images_for_gallery" data-toggle="tooltip" title="Upload images of the room gallery">@lang('merchant_hotel.upload_images_gallery')</label>
                                <input type="file" class="form-control" id="images_for_gallery" name="images_for_gallery[]" multiple="true">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="hotel_address">Address</label>
                                <input type="text" class="form-control" id="hotel_address" onfocus="geolocate()" placeholder="Enter Address" name="hotel_address" value="{{$hotelInfo->address}}">
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="hotel_city">City</label>
                                <input type="text" class="form-control" id="locality" placeholder="Enter City" name="hotel_city" value="{{$hotelInfo->city}}">
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="hotel_state">State</label>
                                <input type="text" class="form-control" id="administrative_area_level_1" placeholder="Enter State" name="hotel_state" value="{{$hotelInfo->state}}">
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="hotel_country">Country</label>
                                <input type="text" class="form-control" id="country" placeholder="Enter Country" name="hotel_country" value="{{$hotelInfo->country}}">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="hotel_zip_code">Zip Code</label>
                                <input type="text" class="form-control" id="postal_code" placeholder="Enter Zipcode" name="hotel_zip_code" value="{{$hotelInfo->zipcode}}">
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="hotel_longitude">Longitude</label>
                                <input type="text" class="form-control" id="hotel_longitude" readonly name="hotel_longitude" value="{{$hotelInfo->longitude}}">
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="hotel_latitude">Latitude</label>
                                <input type="text" class="form-control" id="hotel_latitude" readonly name="hotel_latitude" value="{{$hotelInfo->latitude}}">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <h4>Hotel Description</h4>
                        </div>
                    </div>

                    <div class="row margin-bottom-10">
                        <div class="col-md-12">
                            <div class="form-group">
                                <textarea class="form-control" placeholder="Enter Hotel Description" id="hotel_description" name="hotel_description">{{ $hotelInfo->hotel_desc }}</textarea>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <h4>Hotel Policies</h4>
                        </div>
                    </div>

                    @if(count($hotelInfo->hotel_policies) > 0)
                        <?php $HotelPolicies    =   (array)json_decode($hotelInfo->hotel_policies); ?>
                    @endif

                    <div class="row">
                        @if(count(@$HotelPolicies) > 0)
                            @foreach(@$HotelPolicies as $key => $value)
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <div class="form-group">
                                            <label for="{{str_replace(' ','_', $key)}}">{{ ucwords(str_replace('_',' ', $key)) }}</label>
                                            <input type="text" class="form-control @if($key == 'check_in' || $key == 'check_out') datetimepicker3 @endif" id="{{str_replace(' ','_', $key)}}" name="hotel_policies[{{str_replace(' ','_', $key)}}]" value="{{$value}}">
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <div class="col-md-4">
                                <p>Please contact your administrator to add policy fields.</p>
                            </div>
                        @endif
                    </div>

                    <div class="box-footer">
                        <a href="{{ route('scubaya::merchant::hotels', [Auth::id()]) }}"><button type="button" class="btn btn-default">Cancel</button></a>
                        <button type="submit" class="btn btn-info pull-right">Update</button>
                    </div>
                </form>
            </div>
        </div>
    </section>
    <script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAm_-PodAPns0u0-bvF3qHHV3G_sLe0gdI&libraries=places"></script>
    <script type="text/javascript">
        jQuery(document).ready(function($){
            initialize();

            $('.datetimepicker3').datetimepicker({
                format: 'LT'
            });

            $('#hotel_description').summernote({
                height: 300,
            });
        });

        /* google address auto complete api */
        var placeSearch, autocomplete;
        var componentForm = {
            locality: 'long_name',
            administrative_area_level_1: 'long_name',
            country: 'long_name',
            postal_code: 'short_name'
        };

        function initialize() {
            // Create the autocomplete object, restricting the search
            // to geographical location types.
            autocomplete = new google.maps.places.Autocomplete(
                    (document.getElementById('hotel_address')),
                    { types: ['geocode'] });
            // When the user selects an address from the dropdown,
            // populate the address fields in the form.
            google.maps.event.addListener(autocomplete, 'place_changed', function() {
                fillInAddress();
            });
        }

        function fillInAddress() {
            // Get the place details from the autocomplete object.
            var place = autocomplete.getPlace();
            var lat = place.geometry.location.lat();
            var lng = place.geometry.location.lng();

            for (var component in componentForm) {
                document.getElementById(component).value = '';
                document.getElementById(component).disabled = false;
            }

            // Get each component of the address from the place details
            // and fill the corresponding field on the form.
            for (var i = 0; i < place.address_components.length; i++) {
                var addressType = place.address_components[i].types[0];
                //console.log('address-type:-> '+addressType);
                if (componentForm[addressType]) {
                    var val = place.address_components[i][componentForm[addressType]];
                    document.getElementById(addressType).value = val;
                }

                jQuery('#hotel_longitude').val(lat);
                jQuery('#hotel_latitude').val(lng);
            }
        }

        function geolocate() {
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(function(position) {
                    var geolocation = new google.maps.LatLng(
                            position.coords.latitude, position.coords.longitude);
                    var circle = new google.maps.Circle({
                        center: geolocation,
                        radius: position.coords.accuracy
                    });
                    autocomplete.setBounds(circle.getBounds());
                    autocomplete_textarea.setBounds(circle.getBounds());
                });
            }
        }
    </script>
@endsection