@extends('merchant.layouts.app')
@section('title', 'Edit Shop General Information')
@section('breadcrumb')
    <li><a href="#">Shop</a></li>
    <li><a href="{{route('scubaya::merchant::shop::shops',[Auth::id()])}}">Manage Shop</a></li>
    <li class="active"><span>{{ $shop->name }}</span></li>
@endsection

@section('content')
    @include('merchant.layouts.mainheader')

    <section id="create_shop_information_section" class="padding-20">
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title">Edit Shop General Information</h3>
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
                <form name="shop_information_form" method="post" action="{{route('scubaya::merchant::shop::edit_shop', [Auth::id(), $shop->id])}}" enctype="multipart/form-data">
                    {{csrf_field()}}
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="name">Name</label>
                                <input type="text" class="form-control"  placeholder="Enter Name" name="name" value="{{ $shop->name }}">
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="image">Upload Image</label>
                                <input type="file" class="form-control"  name="image">
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="address">Address</label>
                                <input type="text" class="form-control" id="address" onfocus="geolocate()" placeholder="Enter Address" name="address" value="{{ $shop->address }}">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="city">City</label>
                                <input type="text" class="form-control" id="locality" placeholder="Enter City" name="city" value="{{ $shop->city }}">
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="state">State</label>
                                <input type="text" class="form-control" id="administrative_area_level_1" placeholder="Enter State" name="state" value="{{ $shop->state }}">
                            </div>
                        </div>

                        <?php
                        if(!empty($shop->country)) {
                            $country    =   json_decode($shop->country);
                        }
                        ?>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="country">Country</label>
                                <input type="text" class="form-control" id="country" placeholder="Enter Country" name="country" value="{{ $country->name }}">
                                <input type="hidden" class="form-control" id="country_code" placeholder="Enter Country" name="country_code" value="{{ $country->iso_code2 }}">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="zip_code">Zip Code</label>
                                <input type="text" class="form-control" id="postal_code" placeholder="Enter Zipcode" name="zip_code" value="{{ $shop->zipcode }}">
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="longitude">Longitude</label>
                                <input type="text" class="form-control" id="longitude" readonly name="longitude" value="{{ $shop->longitude }}">
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="latitude">Latitude</label>
                                <input type="text" class="form-control" id="latitude" readonly name="latitude" value="{{ $shop->latitude }}">
                            </div>
                        </div>
                    </div>

                    <div class="box-footer">
                        <a href="{{ route('scubaya::merchant::shop::shops', [Auth::id()]) }}"><button type="button" class="btn btn-default">Cancel</button></a>
                        <button type="submit" class="btn btn-info pull-right">Update</button>
                    </div>

                </form>
            </div>
        </div>
    </section>
    <script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAm_-PodAPns0u0-bvF3qHHV3G_sLe0gdI&libraries=places"></script>
    <script type="text/javascript">
        jQuery(document).ready(function(){
            initialize();
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
                    (document.getElementById('address')),
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

                if (addressType == "country") {
                    document.getElementById("country_code").value = place.address_components[i].short_name;
                }

                if (componentForm[addressType]) {
                    var val = place.address_components[i][componentForm[addressType]];
                    document.getElementById(addressType).value = val;
                }

                jQuery('#longitude').val(lat);
                jQuery('#latitude').val(lng);
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