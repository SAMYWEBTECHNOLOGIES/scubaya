{{-- leaflet css --}}
<link rel="stylesheet" type="text/css" href="{{asset('plugins/leaflet/leaflet.css')}}" />
<link rel="stylesheet" type="text/css" href="{{asset('plugins/leaflet-markercluster/MarkerCluster.css')}}" />
<link rel="stylesheet" type="text/css" href="{{asset('plugins/leaflet-markercluster/MarkerCluster.Default.css')}}" />

{{--leaflet and marker script--}}
<script type='text/javascript' src="{{asset('plugins/leaflet/leaflet.js')}}"></script>
<script type='text/javascript' src="{{asset('plugins/leaflet-markercluster/leaflet.markercluster-src.js')}}"></script>

<script type="text/javascript">
//    $.noConflict();
    $(document).ready(function () {
        $('.datepicker').datepicker();

        $('.fa-minus').click(function () {
//            alert('work man');
            $(this).closest('tr').remove();
        });



        $('.addBtn').on('click', function () {
            addTableRow();
        });

        var i = 1;
        function addTableRow()
        {
            $("#tableAddRow").append('' +
            '<tr id="addRow">' +
            '<td>' +
            '<select class="form-control"><option value="1">Affliation 1</option><option value="2">Affliation 2</option></select >' +
            '</td>' +
            '<td><select class="form-control"><option value="1">level 1</option><option value="2">level 2</option></select>' +
            '</td>' +
            '<td><select class="form-control"><option value="1">speciality 1</option><option value="2">speciality 2</option></select>' +
            '</td>' +
            '<td><span onclick="delTableRow()" class="fa fa-minus addBtnRemove" id="addBtnRemove_0"></span></td>' +
            '</tr>');
            i++;
        }
    });
    //delete the table row in affiliations
    function delTableRow(){
        $('#addRow').remove();
    }
    //leaflet map
    var map = L.map( 'map', {
        center: [20.0, 5.0],
        minZoom: 2,
        zoom: 2,
        maxZoom:4
    });
    var markerClusters = L.markerClusterGroup();

    var markers = [
        {
            "name": "Canada",
            "lat": 56.130366,
            "lng": -106.346771
        },
        {
            "name": "Anguilla",
            "lat": 18.220554,
            "lng": -63.068615
        },
        {
            "name": "Japan",
            "lat": 36.204824,
            "lng": 138.252924
        }
    ];
    //diaplay markers on map
    for ( var i=0; i < markers.length; ++i )
    {   var datepickerJS    =   'https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.7.1/js/bootstrap-datepicker.js';
        var datepickerCSS   =   'https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.7.1/css/bootstrap-datepicker.css';
                var m    =    L.marker( [markers[i].lat, markers[i].lng],{riseOnHover:true} )
                .bindPopup('<div style="overflow-y: scroll;height:300px;" id="get_input" ><table  class="table table-bordered table-hover" id="tableAddRowDiver"><thead><tr><th>Country-{Region}</th><th>Diver-Symbol</th></tr></thead><tbody ><tr id="tr_0"><td>"No of Dives"</td><td><input name="no_of_dives" class="form-control" type="text"></td></tr><tr id="tr_0"><td>"Dive period"</td><td><input id="dive_period" class="form-control datepicker" data-date-format="dd/mm/yyyy" name="dive_period" id="dive_period"  placeholder="Dive Period"></td></tr></tbody></table><div class="modal-footer"><div class="btn-group btn-group-justified" role="group" aria-label="group button"><div class="btn-group" role="group"></div><div class="btn-group btn-delete hidden" role="group"></div><div class="btn-group" role="group"><button type="button" onclick=confirmSelection('+ markers[i].lat+','+ markers[i].lng+') id="saveImage" class="btn btn-default btn-hover-green" data-action="save" role="button">Select</button></div></div></div><button type="button" onclick="addDive(this)" class="fa fa-plus" data-unicode="2b"></button><script src="'+datepickerJS+'" type="text/javascript"><'+'/script><link href="'+ datepickerCSS +'" rel="stylesheet"><script>'+$('.datepicker').datepicker()+'<'+'/script><script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.js" type="text/javascript"><'+'script>')
                .addTo( map );

        markerClusters.addLayer(m);
    }
    // making clusters
    map.addLayer( markerClusters );


    L.tileLayer( 'http://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; <a href="https://www.scubaya.com">Scubaya.com</a>',
        subdomains: ['a','b','c']
    }).addTo( map );


    function confirmSelection(lat,lng){
        // get the input values
        var inputValues = $('#get_input :input').map(function() {
            var type = $(this).prop("type");
            if (type != "button" || type != "submit") {
                return $(this).val();
            }
        });


        //clean the blank values from array
        var finalValues= cleanArray(inputValues);

        //break the values into array of two
        var i,j,temparray,chunk = 2 ;
        var newArray    =   new Array();
        for (i=0,j=finalValues.length; i<j; i+=chunk) {
            temparray = finalValues.slice(i,i+chunk);
            newArray.push(temparray);
        }
        // get the final html with user user inputs
        var final   =   finalHtml(newArray);

        //icon change of flag after user inputs his/her values
//            var myIcon = L.icon({
//                iconUrl: 'images/pin24.png',
//                iconRetinaUrl:'images/pin48.png',
//                iconSize: [29, 24],
//                iconAnchor: [9, 21],
//                popupAnchor: [0, -14]
//            });
//
//
//                L.marker( [lat, lng], {icon: myIcon} )
//                        .bindPopup( '<a href="' + markers[i].url + '" target="_blank">' + markers[i].name + '</a>' )
//                        .addTo( map );

        //to close popup after they hit select
        map.closePopup();
        //and set the new popup for the selection
        L.marker([lat, lng])
                .bindPopup( '<div style="overflow-y: scroll;height:300px;" id="get_input" ><table  class="table table-bordered table-hover" id="tableAddRowDiver">'+final+'</table><div class="modal-footer"><div class="btn-group btn-group-justified" role="group" aria-label="group button"><div class="btn-group" role="group"></div><div class="btn-group btn-delete hidden" role="group"></div><div class="btn-group" role="group"><button type="button" onclick="confirmSelection('+ lat+','+ lng+')" id="saveImage" class="btn btn-default btn-hover-green" data-action="save" role="button">Select</button></div></div></div><button type="button" onclick="addDive(this)" class="fa fa-plus" data-unicode="2b"></button>' )
                .addTo(map);
    }


    function addDive(){
        $('#tableAddRowDiver').append('<thead id="thead"><tr><th>Country-{Region}</th><th>Diver-Symbol<button type="button" onclick="delDive()" class="fa fa-minus" data-unicode="2b"></button></th></tr></thead><tbody id="tbody"><tr id="tr_0"><td>"No of Dives"</td><td><input class="form-control" type="text" ></td></tr><tr id="tr_0"><td>"Dive period"</td><td><input class="form-control datepicker" data-date-format="dd/mm/yyyy" name="dive_period" id="dive_period" placeholder="Dive-period"></td></tr></tbody>');
    }
    function delDive(){
        $('#thead').remove();
        $('#tbody').remove();
    }

    // function to clean blank values
    function cleanArray(actual) {
        var newArray = new Array();
        for (var i = 0; i < actual.length; i++) {
            if (actual[i]) {
                newArray.push(actual[i]);
            }
        }
        return newArray;
    }

    function finalHtml (newArray){
        var final   =   new Array();
        var counter =   0;

        // binding the table elements according to the diver selection no.
        if (!newArray.length) {var html      =   '<thead><tr><th>Country-{Region}</th><th>Diver-Symbol</th></tr></thead><tbody ><tr id="tr_0"><td>"No of Dives"</td><td><input name="no_of_dives" class="form-control" type="text" ></td></tr><tr id="tr_0"><td>"Dive period"</td><td><input id="dive_period" class="form-control datepicker" data-date-format="dd/mm/yyyy" name="dive_period" id="dive_period"  placeholder="Dive Period"></td></tr></tbody>';
            final.push(html);
        }

        else{
            newArray.forEach(function(value){
                if(counter>0){
                    var html        =   '<thead id="thead"><tr><th>Country-{Region}</th><th>Diver-Symbol<button type="button" onclick="delDive()" class="fa fa-minus" data-unicode="2b"></button></th></tr></thead><tbody id="tbody" ><tr id="tr_0"><td>"No of Dives"</td><td><input name="no_of_dives" class="form-control" type="text" value="'+value[0]+'"></td></tr><tr id="tr_0"><td>"Dive period"</td><td><input value="'+value[1]+'" id="dive_period" class="form-control datepicker" data-date-format="dd/mm/yyyy" name="dive_period" id="dive_period"  placeholder="Dive Period"></td></tr></tbody>';
                }else var html      =   '<thead><tr><th>Country-{Region}</th><th>Diver-Symbol</th></tr></thead><tbody ><tr id="tr_0"><td>"No of Dives"</td><td><input name="no_of_dives" class="form-control" type="text" value="'+value[0]+'"></td></tr><tr id="tr_0"><td>"Dive period"</td><td><input value="'+value[1]+'" id="dive_period" class="form-control datepicker" data-date-format="dd/mm/yyyy" name="dive_period" id="dive_period"  placeholder="Dive Period"></td></tr></tbody>';
                final.push(html);
                counter++;
            });
        }
        return final;
    }
</script>