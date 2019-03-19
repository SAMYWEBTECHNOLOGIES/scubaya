@php
    $clientGeoInfo =   geoip($_SERVER['REMOTE_ADDR']);
@endphp
{{--select for country js--}}
<link rel="stylesheet" href="{{asset('assets/country-selector/build/css/countrySelect.css')}}">
<script src="{{asset('assets/country-selector/build/js/countrySelect.js')}}"></script>

<link href="{{asset('plugins/leaflet/leaflet.css')}}" rel="stylesheet">
<script src="{{asset('plugins/leaflet/leaflet.js')}}"></script>

<script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAm_-PodAPns0u0-bvF3qHHV3G_sLe0gdI&libraries=places"></script>
<script type="text/javascript">
    function readURL(input) {
        if (input.files && input.files[0] && input.files.length == 1 ) {
            var reader = new FileReader();
            reader.onload = function (e) {
                jQuery(input).after('<img  src="'+e.target.result+'" width="30%" height="30%">');

            };
            reader.readAsDataURL(input.files[0]);
        }else{
            var i =1;
            for(i;i<input.files.length+1;i++){
                var reader = new FileReader();
                reader.onload = function (e) {
                    jQuery(input).after('<img src="'+e.target.result+'" width="30%" height="30%">');
                };
                reader.readAsDataURL(input.files[i-1]);
            }
        }
    }

    function addLocation(location)
    {
        var locationInfo    =   jQuery(location).data('place');

        jQuery('.location').append('<div class="row margin-bottom-10">' +
        '<div class="col-md-8">' +
        '<div class="col-md-6">'+locationInfo.address+'' +
        '</div>' +
        '<div class="col-md-6 text-right">' +
        '<span class="location-publish-label">Show in Front End</span>' +
        '<div class="btn-group" id="status" data-toggle="buttons">' +
        '<label class="btn btn-default btn-on btn-sm active"><input type="radio" value="1" name="decompression['+locationInfo.address+'][show]" checked>YES</label>' +
        '<label class="btn btn-default btn-off btn-sm"><input type="radio" value="0" name="decompression['+locationInfo.address+'][show]">NO</label>' +
        '</div>' +
        '</div>' +
        '</div>' +
        '<div class="col-md-4">' +
        '<input type="hidden" name="decompression['+locationInfo.address+'][lat]" class="form-control" value="'+locationInfo.lat+'">' +
        '<input type="hidden" name="decompression['+locationInfo.address+'][long]" class="form-control" value="'+locationInfo.long+'">' +
        '</div>'+
        '</div>');
    }

    var markers = {
        "lat": "{{ !empty(old('latitude'))  ? old('latitude')  : $clientGeoInfo['lat'] }}",
        "lng": "{{ !empty(old('longitude')) ? old('longitude') : $clientGeoInfo['lon'] }}"
    };

    window.onload = function () {
        var curLocation = [markers.lat, markers.lng];

        var map1 = L.map('location').setView(curLocation, 4);
        var map2 = L.map('destination-location-map').setView(curLocation, 4);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; <a href="https://www.scubaya.com">Scubaya.com</a>'
        }).addTo(map1);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; <a href="https://www.scubaya.com">Scubaya.com</a>'
        }).addTo(map2);

        var marker1 = new L.marker(curLocation, {
            draggable: 'true'
        });

        var marker2 = new L.marker(curLocation, {
            draggable: 'true'
        });

        map1.addLayer(marker1);
        map2.addLayer(marker2);

        marker1.on('dragend', function (e) {
            var lat = marker1.getLatLng().lat;
            var lng = marker1.getLatLng().lng;

            fetch('https://nominatim.openstreetmap.org/reverse?format=json&lon=' + lng + '&lat=' + lat).then(function(response) {
                return response.json();
            }).then(function(json) {
                if(typeof json.display_name !== 'undefined') {
                    var popLocation= new L.LatLng(lat,lng);
                    L.popup()
                        .setLatLng(popLocation)
                        .setContent('<a data-place=\''+'{"address":"'+json.display_name+'","lat":"'+lat+'","long":"'+lng+'"'+'}'+'\' onclick=addLocation(this)>'+json.display_name+'</a>')
                        .openOn(map1);
                }

            });
        });

        marker2.on('dragend', function (e) {
            fetch('https://nominatim.openstreetmap.org/reverse?format=json&lon=' + marker2.getLatLng().lng + '&lat=' + marker2.getLatLng().lat).then(function(response) {
                return response.json();
            }).then(function(json) {
                if(typeof json.display_name !== 'undefined') {
                    $('#destination-location').val(json.display_name);
                } else {
                    $('#destination-location').val(' ');
                }
            });

            $('#destination-latitude').val(marker2.getLatLng().lat);
            $('#destination-longitude').val(marker2.getLatLng().lng);
        });
    };

</script>