<div style="background: rgb(51, 122, 183);">
    <header class="smaller">
        <div class="white">
            <h3 id="logo">
                Account Details
            </h3>
        </div>
    </header>
</div>
<div class="container screen-fit">
    <form method="post" id="account_details_form" action="{{ route('scubaya::admin::merchants::account_details', [$account_details->merchant_key, $account_details->id]) }}" enctype="multipart/form-data">
        {{csrf_field()}}
        <div class="row">
            <div class="col-md-6">
                <div class="row">
                    <label for="merchant_status" class="col-md-4 control-label">Merchant Status</label>
                    @php
                        $account_status  =   [
                            MERCHANT_STATUS_PENDING         =>  'label label-warning',
                            MERCHANT_STATUS_APPROVED        =>  'label label-success',
                            MERCHANT_STATUS_REJECTED        =>  'label label-danger',
                            MERCHANT_STATUS_IN_PROCESS      =>  'label label-info',
                        ];

                        $disableApprovedOption =   \App\Scubaya\model\MerchantDocumentsMapper::getDocumentsStatus($account_details->id);
                    @endphp
                    {{--<div class="col-md-4">
                        <div class="form-group">
                            <span class="{{$account_status[$account_details->account_status]}}">{{ $account_details->account_status}}</span>
                        </div>
                    </div>--}}
                    <div class="col-md-8">
                        @if($account_details->status == 'rejected')
                            <p class="red">REJECTED</p>
                        @else
                            <div class="form-group">
                                <select name="status" class="form-control" title="Select Status">
                                    <option value="pending" @if($account_details->status == 'pending') selected @endif>Pending</option>
                                    <option value="approved" @if($account_details->status == 'approved') selected @endif @if($disableApprovedOption) disabled @endif>Approved</option>
                                    <option value="rejected" @if($account_details->status == 'rejected') selected @endif>Rejected</option>
                                    <option value="disabled" @if($account_details->status == 'disabled') selected @endif>Disabled</option>
                                </select>
                            </div>
                        @endif
                    </div>
                </div>

                <div class="row">
                    <label for="merchant_screening" class="col-md-4 control-label">Merchant Screening</label>
                    <div class="col-md-8">
                        <div class="form-group">
                            <select class="form-control selectpicker show-tick status" name="screening" title="Select Screening">
                                <option value="{{MERCHANT_SCREENING_COMPLETED}}" @if($account_details->screening == MERCHANT_SCREENING_COMPLETED) selected @endif>{{ucfirst(MERCHANT_SCREENING_COMPLETED )  }}</option>
                                <option value="{{MERCHANT_SCREENING_PENDING}}" @if($account_details->screening == MERCHANT_SCREENING_PENDING) selected @endif>{{ucfirst(MERCHANT_SCREENING_PENDING ) }}</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <label for="contact_module" class="col-md-4 control-label">Show Contact Module</label>
                    <div class="col-md-8">
                        <div class="form-group">
                             <input type="radio" name="show_contact_module" value="1" @if($account_details->contact_module == 1) checked @endif> Yes
                             <input type="radio" name="show_contact_module" value="0" @if($account_details->contact_module == 0) checked @endif> No
                        </div>
                    </div>
                </div>

                <div class="row">
                    <label for="risk_status" class="col-md-4 control-label">Merchant Risk Status</label>
                    <div class="col-md-8">
                        <div class="form-group">
                            <span class="label label-success">Low</span>
                        </div>
                    </div>
                </div>

                @if($disableApprovedOption)
                    <div class="row">
                        <div class="col-md-12"><p class="red"><strong>*Note:</strong> A merchant account cannot be approved until all the documents do not get approved.</p></div>
                    </div>
                @endif

                <hr>

                <div class="form-group row">
                    <label for="email" class="col-md-4 control-label">Email</label>
                    <div class="col-md-8">
                        <input type="text" id="email" name="email"  class="form-control" value = "{{$account_details->email}}">
                    </div>
                </div>

                <div class="form-group row">
                    <label for="company_name" class="col-md-4 control-label">Company Name</label>
                    <div class="col-md-8">
                        <input type="text" id="company_name" name="company_name"  class="form-control" value = "{{$account_details->company_name}}">
                    </div>
                </div>

                <div class="form-group row">
                    <label for="vat_number" class="col-md-4 control-label">VAT number</label>
                    <div class="col-md-8">
                        <input type="text" id="vat_number" name="vat_number"  class="form-control" value = "{{$account_details->vat_number}}">
                    </div>
                </div>

                <div class="form-group row">
                    <label for="chamber_of_commerce" class="col-md-4 control-label">Chamber of Commerce</label>
                    <div class="col-md-8">
                        <input type="text" id="chamber_of_commerce" name="chamber_of_commerce"  class="form-control" value = "{{$account_details->chamber_of_commerce}}">
                    </div>
                </div>

                <div class="form-group row">
                    <label for="street" class="col-md-4 control-label">Street</label>
                    <div class="col-md-8">
                        <input type="text" id="street" name="street" class="form-control" value = "{{$account_details->street}}">
                    </div>
                </div>

                <div class="form-group row">
                    <label for="town" class="col-md-4 control-label">Town</label>
                    <div class="col-md-8">
                        <input type="text" id="town" name="town" class="form-control" value = "{{$account_details->town}}">
                    </div>
                </div>

                <div class="form-group row">
                    <label for="region" class="col-md-4 control-label">Region</label>
                    <div class="col-md-8">
                        <input type="text" id="region" name="region" class="form-control" value = "{{$account_details->region}}">
                    </div>
                </div>

                <div class="form-group row">
                    <label for="country" class="col-md-4 control-label">Country</label>
                    <div class="col-md-8">
                        <input type="text" id="country" name="country" class="form-control" value = "{{$account_details->country}}">
                    </div>
                </div>

                <div class="form-group row">
                    <label for="postcode" class="col-md-4 control-label">Postcode</label>
                    <div class="col-md-8">
                        <input type="text" id="postcode" name="postcode" class="form-control" value = "{{$account_details->postcode}}">
                    </div>
                </div>

                <div class="form-group row">
                    <label for="telephone" class="col-md-4 control-label">Telephone</label>
                    <div class="col-md-8">
                        <input type="text" id="telephone" name="telephone" class="form-control" value = "{{$account_details->telephone}}">
                    </div>
                </div>

            </div>
            <div class="col-md-6">
                <div class="form-group row">
                    <div class="col-md-12 ">
                        <div id="location" style="width: 100%; height: 400px"></div>
                    </div>
                </div>

                <div class="form-group row">
                    <label for="longitude" class="col-md-4 control-label">Longitude</label>
                    <div class="col-md-8">
                        <input type="text" id="longitude" name="longitude" class="form-control" value = "{{$account_details->longitude}}">
                    </div>
                </div>
                <div class="form-group row">
                    <label for="latitude" class="col-md-4 control-label">Latitude</label>
                    <div class="col-md-8">
                        <input type="text" id="latitude" name="latitude" class="form-control" value = "{{$account_details->latitude}}">
                    </div>
                </div>
            </div>
        </div>
        <div class="box-footer">
            <a href="{{ route('scubaya::admin::dashboard') }}"><button type="button" class="btn btn-default">Cancel</button></a>
            <button type="submit" class="btn btn-info pull-right" id="submit" data-toggle="modal" data-target="#verification-form-modal">Save</button>
        </div>
    </form>
</div>
@php
    $clientGeoInfo =   geoip($_SERVER['REMOTE_ADDR']);
@endphp
<link href="{{asset('plugins/leaflet/leaflet.css')}}" rel="stylesheet">
<script src="{{asset('plugins/leaflet/leaflet.js')}}"></script>
<script type="text/javascript">

    var markers = {
        "lat": "{{ !empty(old('latitude'))  ? old('latitude')  : !empty($account_details->latitude)? $account_details->latitude : $clientGeoInfo['lat'] }}",
        "lng": "{{ !empty(old('longitude')) ? old('longitude') : !empty($account_details->longitude)? $account_details->longitude : $clientGeoInfo['lon'] }}"
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
            $('#latitude').val(marker.getLatLng().lat);
            $('#longitude').val(marker.getLatLng().lng);
        });
    };
</script>