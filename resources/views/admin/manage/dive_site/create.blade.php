@extends('admin.layouts.app')
@section('title','Add Dive Site')
@section('breadcrumb')
    <li><a href="{{route('scubaya::admin::dashboard')}}">Manage</a></li>
    <li><a href="{{route('scubaya::admin::manage::dive_sites::index')}}">Dive Sites</a></li>
    <li class="active"><span>Add Dive Site</span></li>
@endsection

@php
    use Jenssegers\Agent\Agent as Agent;
    $Agent = new Agent();
@endphp

@section('content')
    <section class="container screen-fit">
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title">Add Dive Site</h3>
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

            <form role="form" method="post" action="{{route('scubaya::admin::manage::dive_sites::create')}}" enctype="multipart/form-data">
                {{ csrf_field() }}
                <div class="box-body">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="active" class="control-label" data-toggle="tooltip">Active</label><br>
                                        <div class="btn-group"  data-toggle="buttons">
                                            <label class="btn btn-default btn-on btn-sm @if(old('active') === '1') active @elseif(is_null(old('active'))) active @endif">
                                                <input type="radio" value="1" name="active" @if(old('active') === '1') checked @elseif(is_null(old('active'))) checked @endif>YES</label>

                                            <label class="btn btn-default btn-off btn-sm @if(old('active') === '0') active @endif">
                                                <input type="radio" value="0" name="active" @if(old('active') === '0') checked @endif>NO</label>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="active" class="control-label" data-toggle="tooltip">Need A Boat</label><br>
                                        <div class="btn-group"  data-toggle="buttons">
                                            <label class="btn btn-default btn-on btn-sm @if(old('need_a_boat') === '1') active @elseif(is_null(old('need_a_boat'))) active @endif">
                                                <input type="radio" value="1" name="need_a_boat" @if(old('need_a_boat') === '1') checked @elseif(is_null(old('need_a_boat'))) checked @endif>YES</label>

                                            <label class="btn btn-default btn-off btn-sm @if(old('need_a_boat') === '0') active @endif">
                                                <input type="radio" value="0" name="need_a_boat" @if(old('need_a_boat') === '0') checked @endif>NO</label>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label>Dive Site Name</label>
                                <input class="form-control" name="name" type="text" placeholder="Dive Site Name" value="{{ old('name') }}">
                            </div>

                            <div class="form-group">
                                <label>Depth</label><br>
                                Maximum: <select class="form-control" name="max_depth">
                                    <option value="" selected disabled="">-- Select Max Depth --</option>
                                    @for($i = 1; $i <= 100; $i++)
                                    <option value="{{$i}}" @if(old('max_depth') == $i) selected @endif>{{$i}}m</option>
                                    @endfor
                                </select>

                                <br>

                                Average: <select class="form-control" name="avg_depth">
                                    <option value="" selected disabled="">-- Select Avg Depth --</option>
                                    @for($i = 1; $i <= 100; $i++)
                                        <option value="{{$i}}">{{$i}}m</option>
                                    @endfor
                                </select>
                            </div>

                            <div class="form-group">
                                <label>Diver Level</label>
                                <select class="form-control" name="diver_level">
                                    <option value="">-- Select Diver Level --</option>
                                    <option value="beginner">Beginner</option>
                                    <option value="advanced">Advanced</option>
                                    <option value="professional">Professional</option>
                                </select>
                            </div>

                            <div class="form-group">
                                <label>Current</label>
                                <select class="form-control" name="current">
                                    <option value="">-- Select --</option>
                                    <option value="low">Low</option>
                                    <option value="moderate">Moderate</option>
                                    <option value="strong">Strong</option>
                                </select>
                            </div>

                            <div class="form-group">
                                <label>Visibility</label><br>
                                Maximum: <select class="form-control" name="max_visibility">
                                    <option value="">-- Select Max Visibility --</option>
                                    <option value="1">1m</option>
                                    <option value="2">2m</option>
                                    <option value="3">3m</option>
                                    <option value="4">4m</option>
                                    <option value="5">5m</option>
                                    <option value="6">6m</option>
                                    <option value="7">7m</option>
                                    <option value="8">8m</option>
                                    <option value="9">9m</option>
                                    <option value="10">10m</option>
                                    <option value="15">15m</option>
                                    <option value="20">20m</option>
                                    <option value="30">30m</option>
                                    <option value="50">50m</option>
                                    <option value="more than 50">more than 50m</option>
                                </select>

                                <br>

                                Average: <select class="form-control" name="avg_visibility">
                                    <option value="">-- Select Avg Visibility --</option>
                                    <option value="1">1m</option>
                                    <option value="2">2m</option>
                                    <option value="3">3m</option>
                                    <option value="4">4m</option>
                                    <option value="5">5m</option>
                                    <option value="6">6m</option>
                                    <option value="7">7m</option>
                                    <option value="8">8m</option>
                                    <option value="9">9m</option>
                                    <option value="10">10m</option>
                                    <option value="15">15m</option>
                                    <option value="20">20m</option>
                                    <option value="30">30m</option>
                                    <option value="50">50m</option>
                                    <option value="more than 50">more than 50m</option>
                                </select>
                            </div>

                            <div class="form-group">
                                <label>Type</label>
                                <select class="form-control selectpicker" name="type[]" multiple>
                                    <option value="shore">Shore Dive</option>
                                    <option value="boat">Boat Dive</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-7  col-md-offset-1">
                             <div class="row margin-bottom-10">
                                  <div class="col-md-6">
                                      <label><i class="fa fa-upload" aria-hidden="true"></i> Upload Image </label>
                                      <input type="file" class="form-control margin-bottom-10" id="image" name="image" onchange="readURL(this)">
                                  </div>
                             </div>

                            <div id="dive_site_map" style="width: auto;height: 300px" class="margin-bottom-10"></div>

                            <div class="row">
                                <div class="col-md-6">
                                    <label>Latitude</label>
                                    <input name="latitude" id="latitude" class="form-control" type="text">
                                </div>

                                <div class="col-md-6">
                                    <label>Longitude</label>
                                    <input name="longitude" id="longitude" class="form-control" type="text">
                                </div>

                                <input name="country" id="divesite_country" class="form-control" type="hidden">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="box-footer">
                    <a href="{{ route('scubaya::admin::manage::dive_sites::index') }}"><button type="button" class="btn btn-default">Cancel</button></a>
                    <button type="submit" class="btn btn-info pull-right">Save</button>
                </div>
            </form>
        </div>
    </section>

    @php
        $clientGeoInfo =   geoip($_SERVER['REMOTE_ADDR']);
    @endphp

    <link href="{{asset('plugins/leaflet/leaflet.css')}}" rel="stylesheet">
    <script src="{{asset('plugins/leaflet/leaflet.js')}}"></script>

    <script type="text/javascript">
    $(document).ready(function () {
        var markers = {
            "lat": "{{ !empty(old('latitude'))  ? old('latitude')  : $clientGeoInfo['lat'] }}",
            "lng": "{{ !empty(old('longitude')) ? old('longitude') : $clientGeoInfo['lon'] }}"
        };

        var curLocation = [markers.lat, markers.lng];

        var map     = L.map('dive_site_map').setView(curLocation, 4);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; <a href="https://www.scubaya.com">Scubaya.com</a>'
        }).addTo(map);

        var marker = new L.marker(curLocation, {
            draggable: 'true'
        });

        map.addLayer(marker);

        marker.on('dragend', function (e) {
            fetch('https://nominatim.openstreetmap.org/reverse?format=json&lon=' + marker.getLatLng().lng + '&lat=' + marker.getLatLng().lat).then(function(response) {
                return response.json();
            }).then(function(json) {
                if(typeof json.display_name !== 'undefined') {

                    $('#divesite_country').val(json.address.country);

                    var popLocation= new L.LatLng(marker.getLatLng().lat, marker.getLatLng().lng);
                    L.popup()
                        .setLatLng(popLocation)
                        .setContent('<p>'+json.display_name+'</p>')
                        .openOn(map);
                }
            });

            $('#latitude').val(marker.getLatLng().lat);
            $('#longitude').val(marker.getLatLng().lng);
        });
    });

    function readURL(input) {
        if (input.files && input.files[0] && input.files.length == 1 ) {
            var reader = new FileReader();
            reader.onload = function (e) {
                jQuery(input).after('<img  src="'+e.target.result+'" width="30%" height="30%">');
            };
            reader.readAsDataURL(input.files[0]);
        }
    }
    </script>
@stop