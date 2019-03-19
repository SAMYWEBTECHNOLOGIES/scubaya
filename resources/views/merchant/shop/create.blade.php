@extends('merchant.layouts.app')
@section('title', 'Shop General Information')
@section('breadcrumb')
    <li><a href="#">Shop</a></li>
    <li><a href="{{route('scubaya::merchant::shop::shops',[Auth::id()])}}">Manage Shop</a></li>
    <li class="active"><span>Add Shop</span></li>
@endsection

@section('content')
    @include('merchant.layouts.mainheader')

    <section id="create_shop_information_section" class="padding-20">
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title">Shop General Information</h3>
            </div>
            <!-- /.box-header -->
            <!-- form start -->
            @if ($errors->shopError->any())
                <div class="row margin-top-10">
                    <div class="col-md-4 col-md-offset-4 alert alert-danger">
                        <ul>
                            @foreach ($errors->shopError->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            @endif

            <div class="box-body">
                <form name="shop_information_form" method="post" action="{{route('scubaya::merchant::shop::create_shop', [Auth::id()])}}" enctype="multipart/form-data">
                    {{csrf_field()}}
                    <?php $shop =   session()->get('shop'); ?>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="name">Name</label>
                                <input type="text" class="form-control"  placeholder="Enter Name" name="name" value="{{ old('name') ? old('name'): @$shop->name}}">
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
                                <input type="text" class="form-control" id="address"  placeholder="Enter Address" name="address" value="{{ old('address') ? old('address'): @$shop->address}}">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="city">City</label>
                                <input type="text" class="form-control" id="locality" placeholder="Enter City" name="city" value="{{ old('city') ? old('city'): @$shop->city}}">
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="state">State</label>
                                <input type="text" class="form-control" id="administrative_area_level_1" placeholder="Enter State" name="state" value="{{ old('state') ? old('state'): @$shop->state}}">
                            </div>
                        </div>

                        <div class="col-md-4">
                            <?php
                            if(!empty($shop->country)) {
                                $country    =   json_decode($shop->country);
                            }
                            ?>
                            <div class="form-group">
                                <label for="country">Country</label>
                                <input type="text" class="form-control" id="country" placeholder="Enter Country" name="country" value="{{ old('country') ? old('country'): @$shop->name}}">
                                <input type="hidden" class="form-control" id="country_code" placeholder="Enter Country" name="country_code" value="">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="zip_code">Zip Code</label>
                                <input type="text" class="form-control" id="postal_code" placeholder="Enter Zipcode" name="zip_code" value="{{ old('zip_code') ? old('zip_code'): @$shop->zip_code }}">
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="longitude">Longitude</label>
                                <input type="text" class="form-control" id="longitude"  name="longitude" value="{{ old('longitude') ? old('longitude'): @$shop->longitude }}">
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="latitude">Latitude</label>
                                <input type="text" class="form-control" id="latitude"  name="latitude" value="{{ old('latitude') ? old('latitude'): @$shop->latitude }}">
                            </div>
                        </div>
                    </div>

                    <div class="box-footer">
                        <a href="{{ route('scubaya::merchant::shop::shops', [Auth::id()]) }}"><button type="button" class="btn btn-default">Cancel</button></a>
                        <button type="submit" class="btn btn-info pull-right" id="submit" data-toggle="modal" data-target="#verification-form-modal{{@$shop->id}}">Save</button>
                    </div>
                </form>

                {{-- include verification model --}}
                {{--@if(session()->get('show_popup') == 'true' || $errors->verificationError->any())
                    @include('merchant.layouts.website_verification.verification_modal', ['route1' => 'scubaya::merchant::shop::verification',
                     'route2' => 'scubaya::merchant::shop::shops', 'website' => session()->get('shop')])
                @endif--}}
            </div>
        </div>
    </section>

    {{--<script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAm_-PodAPns0u0-bvF3qHHV3G_sLe0gdI&libraries=places"></script>--}}
    <script type="text/javascript">
        @if(session()->get('show_popup') == 'true' || $errors->verificationError->any())
        jQuery('#submit').attr('type', 'button');
        var modelId =   jQuery('#submit').attr('data-target');
        jQuery(modelId).modal('show');
        @endif

        jQuery(document).ready(function($){
            //initialize();

            $('.datetimepicker3').datetimepicker({
                format: 'LT'
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
                console.log('address-type:-> '+addressType);

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