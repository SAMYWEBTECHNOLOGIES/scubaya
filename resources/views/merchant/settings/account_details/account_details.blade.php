@extends('merchant.layouts.app')
@section('title', 'Manage Dive Center')
@section('breadcrumb')
    <li><a href="#">Settings</a></li>
    <li class="active"><span>Account Details</span></li>
@endsection

@section('content')
    @include('merchant.layouts.mainheader')

    <section class="padding-20">
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title">Account Details :</h3>
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
            @if (session('success'))
                <div class="alert alert-success">
                    <a href="#" class="close" data-dismiss="alert">&times;</a>
                    <p>{{ session('success') }}</p>
                </div>
            @endif
            <div class="box-body">
                <form method="post" id="account_details_form" action="{{ route('scubaya::merchant::settings::account_details', [Auth::id()]) }}" enctype="multipart/form-data">
                    {{csrf_field()}}

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group row">
                                <label for="company_name" class="col-md-3 control-label">Company Name</label>
                                <div class="col-md-9">
                                    <input type="text" id="company_name" name="company_name"  class="form-control" value ="{{$accountDetail->company_name}}">
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="vat_number" class="col-md-3 control-label">VAT number</label>
                                <div class="col-md-9">
                                    <input type="text" id="vat_number" name="vat_number"  class="form-control" value ="{{$accountDetail->vat_number}}">
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="chamber_of_commerce" class="col-md-3 control-label">Chamber of Commerce</label>
                                <div class="col-md-9">
                                    <input type="text" id="chamber_of_commerce" name="chamber_of_commerce"  class="form-control" value ="{{$accountDetail->chamber_of_commerce}}">
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="street" class="col-md-3 control-label">Street</label>
                                <div class="col-md-9">
                                    <input type="text" id="street" name="street" class="form-control" value ="{{$accountDetail->street}}">
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="town" class="col-md-3 control-label">Town</label>
                                <div class="col-md-9">
                                    <input type="text" id="town" name="town" class="form-control" value ="{{$accountDetail->town}}">
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="region" class="col-md-3 control-label">Region</label>
                                <div class="col-md-9">
                                    <input type="text" id="region" name="region" class="form-control" value ="{{$accountDetail->region}}">
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="country" class="col-md-3 control-label">Country</label>
                                <div class="col-md-9">
                                    <input type="text" id="country" name="country" class="form-control" value ="{{$accountDetail->country}}">
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="postcode" class="col-md-3 control-label">Postcode</label>
                                <div class="col-md-9">
                                    <input type="text" id="postcode" name="postcode" class="form-control" value ="{{$accountDetail->postcode}}">
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="telephone" class="col-md-3 control-label">Telephone</label>
                                <div class="col-md-9">
                                    <input type="text" id="telephone" name="telephone" class="form-control" value ="{{$accountDetail->telephone}}">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-horizontal">
                                <div class="form-group">
                                    <div class="col-md-12 ">
                                        <div id="location" style="width: 100%; height: 392px"></div>
                                    </div>
                                </div>
                                <div class ="row">
                                    <div class="col-md-6">
                                        <div class="form-group row">
                                            <label for="longitude" class="col-md-3 control-label">Longitude</label>
                                            <div class="col-md-8">
                                                <input type="text" id="longitude" name="longitude" class="form-control" value ="{{$accountDetail->longitude}}">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group row">
                                            <label for="latitude" class="col-md-3 control-label">Latitude</label>
                                            <div class="col-md-8">
                                                <input type="text" id="latitude" name="latitude" class="form-control" value ="{{$accountDetail->latitude}}">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="box-footer">
                        <a href="{{ route('scubaya::merchant::dashboard', [Auth::id()]) }}"><button type="button" class="btn btn-default">Cancel</button></a>
                        <button type="submit" class="btn btn-info pull-right" id="submit" data-toggle="modal" data-target="#verification-form-modal">Save</button>
                    </div>
                </form>
            </div>

        </div>
    </section>
    @php
        $clientGeoInfo =   geoip($_SERVER['REMOTE_ADDR']);
    @endphp
    <link href="{{asset('plugins/leaflet/leaflet.css')}}" rel="stylesheet">
    <script src="{{asset('plugins/leaflet/leaflet.js')}}"></script>
    <script type="text/javascript">

        var markers = {
            "lat": "{{ !empty(old('latitude'))  ? old('latitude')  : !empty($accountDetail->latitude)? $accountDetail->latitude : $clientGeoInfo['lat'] }}",
            "lng": "{{ !empty(old('longitude')) ? old('longitude') : !empty($accountDetail->longitude)? $accountDetail->longitude : $clientGeoInfo['lon'] }}"
        };
        $( "#account_details_form" ).validate({
            rules: {
                first_name:{
                    required: true
                },
                last_name:{
                    required: true
                },
                /*password: "required",
                password_confirmation: {
                    equalTo: "#password"
                }*/
            },
            messages:{
                password_confirmation:{
                    equalTo:"Password didnt match, enter again"
                }
            }
        });

        window.onload = function () {

            var curLocation = [markers.lat, markers.lng];

            var map = L.map('location').setView(curLocation, 4);

            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; <a href="https://www.scubaya.com">Scubaya.com</a>'
            }).addTo(map);

            var marker = new L.marker(curLocation, {
                draggable: 'true'
            });

            map.addLayer(marker);

            marker.on('dragend', function (e) {
                $('#latitude').val(marker.getLatLng().lat);
                $('#longitude').val(marker.getLatLng().lng);
            });
        };
    </script>

@endsection