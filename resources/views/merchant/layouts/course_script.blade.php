<link href="{{asset('plugins/leaflet/leaflet.css')}}" rel="stylesheet">
<script src="{{asset('plugins/leaflet/leaflet.js')}}"></script>
<script type="text/javascript">

    $('#course_description').summernote({
        height: 300,
    });

    $('#cancellation_detail').summernote({
        height: 300,
    });

    function showExcludedIncluded(data){
        var html    =   '<div class="col-md-3 product_included_excluded'+data.id+'">'+
                '<input type="radio" checked name="product_in_course['+data.id+'][IE]" value="1" id="incl'+data.id+'" > Included &nbsp;'+
                '<input type="radio" name="product_in_course['+data.id+'][IE]" value="0" id="excl'+data.id+'"> Excluded'+
                '</div>';

        if(jQuery('#'+data.id).val() == 1){
            if(jQuery('.product_included_excluded'+data.id).length == 0){
                var parent  =   jQuery('#'+data.id).parent().parent().parent().parent();
                parent.append(html);
            }
        }else{
            jQuery('.product_included_excluded'+data.id).remove();

            if(jQuery('.product_price'+data.id).length == 1){
                jQuery('.product_price'+data.id).remove();
            }
        }
    }

    jQuery('.datepicker').datepicker({
        format: 'mm-dd-yyyy'
    });

    var markers = {
        "lat": "{{ !empty(old('latitude'))  ? old('latitude')  : !empty($location['lat'])? $location['lat'] : $clientGeoInfo['lat'] }}",
        "lng": "{{ !empty(old('longitude')) ? old('longitude') : !empty($location['long'])? $location['long'] : $clientGeoInfo['lon'] }}"

    };

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
            var lat                 = marker.getLatLng().lat;
            var lng                 = marker.getLatLng().lng;

            fetch('https://nominatim.openstreetmap.org/reverse?format=json&lon=' + lng + '&lat=' + lat).then(function(response) {
                return response.json();
            }).then(function(json) {
                if(typeof json.display_name !== 'undefined') {
                    var popLocation= new L.LatLng(lat,lng);
                    addressCourse = json.display_name ;
                    $('#address').val(addressCourse);
                }
                $('#latitude').val(marker.getLatLng().lat);
                $('#longitude').val(marker.getLatLng().lng);

            });
        });

        var boatOptions, instructorOption;
        var centers     =   [];
        var token       =   '{{ csrf_token() }}';
        var boats       =   JSON.parse('{!! json_encode($boats) !!}');
        var instructors =   JSON.parse('{!! json_encode($instructors) !!}');
        var url         =   '{{ route('scubaya::merchant::shop::boat_instructor', ['--KEY--']) }}';
        url             =   url.replace('--KEY--', <?php echo $key; ?>);

        $.each($(".dive-centers option:selected"), function(){
            centers.push($(this).val());
        });

        if(centers.length > 0) {
            $.ajax({
                url: url,
                method: 'post',
                data: {centers: JSON.stringify(centers), _token: token},
                success: function (response) {
                    // for making boats option
                    $.each(response.boats, function (key, value) {
                        if($.inArray((value.id).toString(), boats) >= 0) {
                            boatOptions += '<option selected value="' + value.id + '">' + value.name + '</option>';
                        } else {
                            boatOptions += '<option value="' + value.id + '">' + value.name + '</option>';
                        }
                    });

                    if ($('#boats').find('option').length > 0) {
                        $('#boats').find('option').remove();
                        $('#boats').selectpicker('refresh');
                    }

                    $('#boats').append(boatOptions).selectpicker('refresh');

                    // for making instructors option
                    $.each(response.instructors, function (key, value) {
                        if($.inArray((value.id).toString(), instructors) >= 0) {
                            instructorOption += '<option selected value="' + value.id + '">' + value.first_name + ' ' + value.last_name + '</option>';
                        } else {
                            instructorOption += '<option value="' + value.id + '">' + value.first_name + ' ' + value.last_name + '</option>';
                        }
                    });

                    if ($('#instructors').find('option').length > 0) {
                        $('#instructors').find('option').remove();
                        $('#instructors').selectpicker('refresh');
                    }

                    $('#instructors').append(instructorOption).selectpicker('refresh');

                },
                error: function (message) {
                    console.log(message);
                }
            });
        }
    }

    $('.dive-centers').change(function() {
        var token = '{{ csrf_token() }}';
        var boatOptions, instructorOption;

        var url = '{{ route('scubaya::merchant::shop::boat_instructor', ['--KEY--']) }}';
        url = url.replace('--KEY--', <?php echo $key; ?>);
        console.log(url);

        $.ajax({
            url: url,
            method: 'post',
            data: {centers: JSON.stringify($(this).val()), _token: token},
            success: function (response) {
                // for making boats option
                $.each(response.boats, function (key, value) {
                    boatOptions += '<option value="' + value.id + '">' + value.name + '</option>';
                });

                if ($('#boats').find('option').length > 0) {
                    $('#boats').find('option').remove();
                    $('#boats').selectpicker('refresh');
                }

                $('#boats').append(boatOptions).selectpicker('refresh');

                // for making instructors option
                $.each(response.instructors, function (key, value) {
                    /*$.each(value, function () {*/
                    instructorOption += '<option value="' + value.id + '">' + value.first_name + ' ' + value.last_name + '</option>';
                    /*});*/
                });

                if ($('#instructors').find('option').length > 0) {
                    $('#instructors').find('option').remove();
                    $('#instructors').selectpicker('refresh');
                }

                $('#instructors').append(instructorOption).selectpicker('refresh');

            },
            error: function (message) {
                console.log(message);
            }
        });
    });
</script>