@extends('merchant.layouts.app')
@section('title', 'Edit Dive Center')
@section('breadcrumb')
    <li><a href="#">Dive Center</a></li>
    <li><a href="{{route('scubaya::merchant::dive_center::dive_centers',[Auth::id()])}}">Manage Dive Centers</a></li>
    <li class="active"><span>{{ $diveCenter->name }}</span></li>
@endsection

@section('content')
    @include('merchant.layouts.mainheader')

    <section id="edit_dive_center" class="padding-20">
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title">Edit Dive Center</h3>
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
                <form method="post" action="{{ route('scubaya::merchant::dive_center::edit_dive_center', [Auth::id(), @$diveCenter->id]) }}" enctype="multipart/form-data">
                    {{csrf_field()}}
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="name">Name</label>
                                <input type="text" class="form-control"  placeholder="Enter Name" name="name" value="{{ @$diveCenter->name }}">
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="address">Address</label>
                                <input type="text" class="form-control" id="address" onfocus="geolocate()" placeholder="Enter Address" name="address" value="{{@$diveCenter->address }}">
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="city">City</label>
                                <input type="text" class="form-control" id="locality" placeholder="Enter City" name="city" value="{{ @$diveCenter->city }}">
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="zip_code">Zip Code</label>
                                <input type="text" class="form-control" id="postal_code" placeholder="Enter Zipcode" name="zip_code" value="{{ @$diveCenter->zipcode }}">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="state">State</label>
                                <input type="text" class="form-control" id="administrative_area_level_1" placeholder="Enter State" name="state" value="{{ @$diveCenter->state }}">
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="country">Country</label>
                                <input type="text" class="form-control" id="country" placeholder="Enter Country" name="country" value="{{ @$diveCenter->country }}">
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="longitude">Longitude</label>
                                <input type="text" class="form-control" id="longitude" readonly name="longitude" value="{{ @$diveCenter->longitude }}">
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="latitude">Latitude</label>
                                <input type="text" class="form-control" id="latitude" readonly name="latitude" value="{{ @$diveCenter->latitude }}">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="facebook_url">Facebook Url</label>
                                <input type="text" class="form-control" id="facebook_url" placeholder="Enter Name" name="facebook_url" value="{{ @$diveCenter->facebook_url }}">
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="twitter_url">Twitter Url</label>
                                <input type="text" class="form-control" id="twitter_url" placeholder="Enter Name" name="twitter_url" value="{{ @$diveCenter->twitter_url }}">
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="instagram_url">Instagram Url</label>
                                <input type="text" class="form-control" id="instagram_url" placeholder="Enter Instagram Url" name="instagram_url" value="{{ @$diveCenter->instagram_url }}">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="image">Upload Image</label>
                                <input type="file" class="form-control" id="image" name="image" onchange="readURL(this)">
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="gallery">Upload Gallery</label>
                                <input type="file" class="form-control" id="gallery"  name="gallery[]" multiple="true" onchange="readURL(this)">
                            </div>
                        </div>
                    </div>

                    <hr>

                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Activities</label> <br>
                                <button type="button" class="btn" data-toggle="modal" data-target="#activity">Add Activity</button>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Non Diving Activities</label> <br>
                                <button type="button" class="btn" data-toggle="modal" data-target="#nonDivingActivity">Add Non Diving Acitvity</button>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Dive Center Facilities</label> <br>
                                <button type="button" class="btn" data-toggle="modal" data-target="#facility">Add Facility</button>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Specialities</label> <br>
                                <button type="button" class="btn" data-toggle="modal" data-target="#speciality">Add Speciality</button>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Members Of: </label> <br>
                                <button type="button" class="btn" data-toggle="modal" data-target="#memberAffiliation">Add member</button>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Affiliate of:</label> <br>
                                <button type="button" class="btn" data-toggle="modal" data-target="#affiliation">Add Affiliate</button>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Languages spoken</label><br>
                                <button type="button" class="btn" data-toggle="modal" data-target="#language">Add language</button>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Infrastructure</label><br>
                                <button type="button" class="btn" data-toggle="modal" data-target="#infrastructure">Add Infrastructure</button>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Payment Methods </label> <br>
                                <button type="button" class="btn" data-toggle="modal" data-target="#paymentMethod">Add Payment Method</button>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Required Documents And Information</label><br>
                                <textarea cols="8" rows="3" class="form-control" placeholder="Example: certification card, number of dives etc." name="documents">{{ @$diveCenter->required_documents }}</textarea>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Cancellation Policy</label><br>
                                <textarea cols="8" rows="3" class="form-control" placeholder="Cancellation Policy" name="cancellation_policy">{{ @$diveCenter->cancellation_policy }}</textarea>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Distance From Sea</label>
                                <select class="form-control" name="distance_from_sea">
                                    <option selected disabled>-- select distance --</option>
                                    <option value="less than 50mtr" @if(isset($diveCenter->distance_from_sea) && $diveCenter->distance_from_sea == 'less than 50mtr') selected @endif>less than 50mtr</option>
                                    <option value="50mtr" @if(isset($diveCenter->distance_from_sea) && $diveCenter->distance_from_sea == '50mtr') selected @endif>50mtr</option>
                                    <option value="100mtr" @if(isset($diveCenter->distance_from_sea) && $diveCenter->distance_from_sea == '100mtr') selected @endif>100mtr</option>
                                    <option value="150mtr" @if(isset($diveCenter->distance_from_sea) && $diveCenter->distance_from_sea == '150mtr') selected @endif>150mtr</option>
                                    <option value="200mtr" @if(isset($diveCenter->distance_from_sea) && $diveCenter->distance_from_sea == '200mtr') selected @endif>200mtr</option>
                                    <option value="300mtr" @if(isset($diveCenter->distance_from_sea) && $diveCenter->distance_from_sea == '300mtr') selected @endif>300mtr</option>
                                    <option value="500mtr" @if(isset($diveCenter->distance_from_sea) && $diveCenter->distance_from_sea == '500mtr') selected @endif>500mtr</option>
                                    <option value="1km" @if(isset($diveCenter->distance_from_sea) && $diveCenter->distance_from_sea == '1km') selected @endif>1km</option>
                                    <option value="more than 1km" @if(isset($diveCenter->distance_from_sea) && $diveCenter->distance_from_sea == 'more than 1km') selected @endif>more than 1km</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    @php
                        $groupInfo  =   json_decode($diveCenter->groups);

                        $status     =   $groupInfo->status;
                    @endphp
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Are Group Accepted</label><br>
                                <div class="btn-group" id="status" data-toggle="buttons">
                                    <label class="btn btn-default btn-on btn-sm @if($status === '1') active @endif">
                                        <input type="radio" value="1" name="group[status]" @if($status === '1') checked @endif>ON</label>

                                    <label class="btn btn-default btn-off btn-sm @if($status === '0') active @endif">
                                        <input type="radio" value="0" name="group[status]" @if($status === '0') checked @endif>OFF</label>
                                </div>
                                <textarea cols="8" rows="3" class="form-control margin-top-10" name="group[explanation]" placeholder="Explanation why yes or no">{{ @$groupInfo->explanation }}</textarea>
                            </div>
                        </div>
                    </div>

                    <hr>

                    <h4>Opening Days</h4>

                    @php
                        $openingDays    =   (array)json_decode($diveCenter->opening_days);

                        $days           =   array_key_exists('day', $openingDays) ? $openingDays['day'] : [];
                        $time           =   array_key_exists('time', $openingDays)? (array)$openingDays['time'] : [];
                    @endphp
                    <div class="row margin-top-10">
                        <div class="col-md-5">
                            <div class="row">
                                <div class="col-md-4">
                                    <label>Day</label><br>
                                    <input type="checkbox" value="all" name="opening_days[day][]" @if(count($days) && in_array('all', $days)) checked @endif> 7 Days
                                </div>

                                <div class="col-md-4">
                                    <label>From</label>
                                    <input type="text" class="form-control datetimepicker" name="opening_days[time][all][from]" value="{{ @$time['all']->from }}">
                                </div>

                                <div class="col-md-4">
                                    <label>To</label>
                                    <input type="text" class="form-control datetimepicker" name="opening_days[time][all][to]" value="{{ @$time['all']->to }}">
                                </div>
                            </div>

                            <div class="row margin-top-10">
                                <div class="col-md-4">
                                    <label>Day</label><br>
                                    <input type="checkbox" value="su" name="opening_days[day][]" @if(count($days) && in_array('su', $days)) checked @endif> Sunday
                                </div>

                                <div class="col-md-4">
                                    <label>From</label>
                                    <input type="text" class="form-control datetimepicker" name="opening_days[time][su][from]" value="{{ @$time['su']->from }}">
                                </div>

                                <div class="col-md-4">
                                    <label>To</label>
                                    <input type="text" class="form-control datetimepicker" name="opening_days[time][su][to]" value="{{ @$time['su']->to }}">
                                </div>
                            </div>

                            <div class="row margin-top-10">
                                <div class="col-md-4">
                                    <label>Day</label><br>
                                    <input type="checkbox" value="mo" name="opening_days[day][]" @if(count($days) && in_array('mo', $days)) checked @endif> Monday
                                </div>

                                <div class="col-md-4">
                                    <label>From</label>
                                    <input type="text" class="form-control datetimepicker" name="opening_days[time][mo][from]" value="{{ @$time['mo']->from }}">
                                </div>

                                <div class="col-md-4">
                                    <label>To</label>
                                    <input type="text" class="form-control datetimepicker" name="opening_days[time][mo][to]" value="{{ @$time['mo']->to }}">
                                </div>
                            </div>

                            <div class="row margin-top-10">
                                <div class="col-md-4">
                                    <label>Day</label><br>
                                    <input type="checkbox" value="tu" name="opening_days[day][]" @if(count($days) && in_array('tu', $days)) checked @endif> Tuesday
                                </div>

                                <div class="col-md-4">
                                    <label>From</label>
                                    <input type="text" class="form-control datetimepicker" name="opening_days[time][tu][from]" value="{{ @$time['tu']->from }}">
                                </div>

                                <div class="col-md-4">
                                    <label>To</label>
                                    <input type="text" class="form-control datetimepicker" name="opening_days[time][tu][to]" value="{{ @$time['tu']->to }}">
                                </div>
                            </div>
                        </div>

                        <div class="col-md-5 col-md-offset-2">
                            <div class="row">
                                <div class="col-md-4">
                                    <label>Day</label><br>
                                    <input type="checkbox" value="we" name="opening_days[day][]" @if(count($days) && in_array('we', $days)) checked @endif> Wednesday
                                </div>

                                <div class="col-md-4">
                                    <label>From</label>
                                    <input type="text" class="form-control datetimepicker" name="opening_days[time][we][from]" value="{{ @$time['we']->from }}">
                                </div>

                                <div class="col-md-4">
                                    <label>To</label>
                                    <input type="text" class="form-control datetimepicker" name="opening_days[time][we][to]" value="{{ @$time['we']->to }}">
                                </div>
                            </div>

                            <div class="row margin-top-10">
                                <div class="col-md-4">
                                    <label>Day</label><br>
                                    <input type="checkbox" value="th" name="opening_days[day][]" @if(count($days) && in_array('th', $days)) checked @endif> Thursday
                                </div>

                                <div class="col-md-4">
                                    <label>From</label>
                                    <input type="text" class="form-control datetimepicker" name="opening_days[time][th][from]" value="{{ @$time['th']->from }}">
                                </div>

                                <div class="col-md-4">
                                    <label>To</label>
                                    <input type="text" class="form-control datetimepicker" name="opening_days[time][th][to]" value="{{ @$time['th']->to }}">
                                </div>
                            </div>

                            <div class="row margin-top-10">
                                <div class="col-md-4">
                                    <label>Day</label><br>
                                    <input type="checkbox" value="fr" name="opening_days[day][]" @if(count($days) && in_array('fr', $days)) checked @endif> Friday
                                </div>

                                <div class="col-md-4">
                                    <label>From</label>
                                    <input type="text" class="form-control datetimepicker" name="opening_days[time][fr][from]" value="{{ @$time['fr']->from }}">
                                </div>

                                <div class="col-md-4">
                                    <label>To</label>
                                    <input type="text" class="form-control datetimepicker" name="opening_days[time][fr][to]" value="{{ @$time['fr']->to }}">
                                </div>
                            </div>

                            <div class="row margin-top-10">
                                <div class="col-md-4">
                                    <label>Day</label><br>
                                    <input type="checkbox" value="sa" name="opening_days[day][]" @if(count($days) && in_array('sa', $days)) checked @endif> Saturday
                                </div>

                                <div class="col-md-4">
                                    <label>From</label>
                                    <input type="text" class="form-control datetimepicker" name="opening_days[time][sa][from]" value="{{ @$time['sa']->from }}">
                                </div>

                                <div class="col-md-4">
                                    <label>To</label>
                                    <input type="text" class="form-control datetimepicker" name="opening_days[time][sa][to]" value="{{ @$time['sa']->to }}">
                                </div>
                            </div>
                        </div>
                    </div>

                    <hr>

                    <h4>Information Section</h4>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Add Short Description</label>
                                <textarea cols="8" rows="5" class="form-control" placeholder="Short description" name="dc_short_description">{{ @$diveCenter->short_description }}</textarea>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Add Long Description</label>
                                <textarea cols="8" rows="5" class="form-control" placeholder="Long description" name="dc_long_description">{{ @$diveCenter->long_description }}</textarea>
                            </div>
                        </div>
                    </div>

                    <hr>

                    <div class="row">
                        <div class="col-md-4">
                            <h4>Read before you go</h4>
                        </div>
                        <div class="col-md-3">
                            <button type="button" class="btn btn-success pull-right margin-top-10 add_new_read_td_section" name="add_new">Add New</button>
                        </div>
                    </div>

                    @php
                        $readtd     =   (array)json_decode($diveCenter->read_before_you_go);
                        $readCount  =   0;
                    @endphp

                    @if(count($readtd))
                        <div class="read-title-desc-section margin-top-10">
                        @foreach($readtd as $key => $read)

                            <div class="row margin-top-10">
                                <div class="col-md-3">
                                    <input name="read[{{ $key }}][title]" class="form-control" placeholder="Title" value="{{ $read->title }}">
                                </div>

                                <div class="col-md-4">
                                    <textarea cols="8" rows="5" class="form-control" placeholder="Description" name="read[{{ $key }}][description]">{{ $read->description }}</textarea>
                                </div>

                                <div class="col-md-5"></div>
                            </div>

                        @php $readCount = $key; @endphp
                        @endforeach
                        </div>
                    @endif

                    <hr>

                    <h4>Gear Info</h4>

                    @php
                        $childGears =   array();
                        $adultGears =   array();
                        $otherGears =   array();

                        $savedGears =   (array)json_decode($diveCenter->gears);

                        if(count($savedGears)) {
                            $childGears =   $savedGears['child'];
                            $adultGears =   $savedGears['adult'];
                            $otherGears =   $savedGears['other'];
                        }

                    @endphp

                    @if(count($gears))
                        <div class="row">
                            <div class="col-md-2">
                                <label>Scuba Gear</label>
                            </div>

                            <div class="col-md-4">
                                <label>Children Gear :</label><br>
                                @if(array_key_exists('child', $gears))
                                    @foreach($gears['child'] as $childGear)
                                        <input type="checkbox"  value="{{ $childGear }}" name="gear[child][]" @if(count($childGears) && in_array($childGear, $childGears)) checked @endif> {{ $childGear }} &nbsp;
                                    @endforeach
                                @endif

                                <br>

                                <label class="margin-top-10">Adult Gear :</label><br>
                                @if(array_key_exists('adult', $gears))
                                    @foreach($gears['adult'] as $adultGear)
                                        <input type="checkbox"  value="{{ $adultGear }}" name="gear[adult][]" @if(count($adultGears) && in_array($adultGear, $adultGears)) checked @endif> {{ $adultGear }} &nbsp;
                                    @endforeach
                                @endif

                                <br>
                                <label class="margin-top-10">Other Gear :</label><br>
                                @if(array_key_exists('other', $gears))
                                    @foreach($gears['other'] as $otherGear)
                                        <input type="checkbox"  value="{{ $otherGear }}" name="gear[other][]" @if(count($otherGears) && in_array($otherGear, $otherGears)) checked @endif> {{ $otherGear }} &nbsp;
                                    @endforeach
                                @endif
                            </div>
                        </div>
                    @else
                        <p>Please contact administrator to add gears.</p>
                    @endif

                    <div class="row margin-top-10">
                        <div class="col-md-2">
                            <label>Filling Station</label>
                        </div>

                        @php
                            $fillingStation =   isset($diveCenter->filling_station) ? json_decode($diveCenter->filling_station) : [];
                        @endphp

                        <div class="col-md-4">
                            <select name="filling_station[]" data-size="10" data-selected-text-format="count > 4" multiple class="selectpicker form-control filling-station">
                                <option value="22" @if(count($fillingStation) && in_array('22', $fillingStation)) selected @endif>22 litres: Available in steel, 200 and 232bar,[44]</option>
                                <option value="20" @if(count($fillingStation) && in_array('20', $fillingStation)) selected @endif>20 litres: Available in steel, 200 and 232bar,[44]</option>
                                <option value="18" @if(count($fillingStation) && in_array('18', $fillingStation)) selected @endif>18 litres: Available in steel, 200 and 232 bar,[44] used as single or twins for back gas.</option>
                                <option value="16" @if(count($fillingStation) && in_array('16', $fillingStation)) selected @endif>16 litres: Available in steel, 200 and 232bar,[44] used as single or twins for back gas.</option>
                                <option value="15" @if(count($fillingStation) && in_array('15', $fillingStation)) selected @endif>15 litres: Available in steel, 200 and 232 bar,[44] used as single or twins for back gas</option>
                                <option value="12.2" @if(count($fillingStation) && in_array('12.2', $fillingStation)) selected @endif>12.2 litres: Available in steel 232, 300 bar[45] and aluminium 232 bar, used as single or twins for back gas</option>
                                <option value="12" @if(count($fillingStation) && in_array('12', $fillingStation)) selected @endif>12 litres: Available in steel 200, 232, 300 bar,[45] and aluminium 232 bar, used as single or twins for back gas</option>
                                <option value="11" @if(count($fillingStation) && in_array('11', $fillingStation)) selected @endif>11 litres: Available in aluminium, 200, 232 bar used as single, twins for back gas or sidemount</option>
                                <option value="10.2" @if(count($fillingStation) && in_array('10.2', $fillingStation)) selected @endif>10.2 litres: Available in aluminium, 232 bar, used as single or twins for back gas</option>
                                <option value="10" @if(count($fillingStation) && in_array('10', $fillingStation)) selected @endif>10 litres: Available in steel, 200, 232 and 300 bar,[46] used as single or twins for back gas, and for bailout</option>
                                <option value="9.4" @if(count($fillingStation) && in_array('9.4', $fillingStation)) selected @endif>9.4 litres: Available in aluminium, 232 bar, used for back gas or as slings</option>
                                <option value="8" @if(count($fillingStation) && in_array('8', $fillingStation)) selected @endif>8 litres: Available in steel, 200 bar, used for Semi-closed rebreathers</option>
                                <option value="7" @if(count($fillingStation) && in_array('7', $fillingStation)) selected @endif>7 litres: Available in steel, 200, 232 and 300 bar,[47] and aluminium 232 bar, back gas as singles and twins, and as bailout cylinders. A popular size for SCBA</option>
                                <option value="6" @if(count($fillingStation) && in_array('6', $fillingStation)) selected @endif>6 litres: Available in steel, 200, 232, 300 bar,[47] used for back gas as singles and twins, and as bailout cylinders. Also a popular size for SCBA</option>
                                <option value="5.5" @if(count($fillingStation) && in_array('5.5', $fillingStation)) selected @endif>5.5 litres: Available in steel, 200 and 232 bar,[48]</option>
                                <option value="5" @if(count($fillingStation) && in_array('5', $fillingStation)) selected @endif>5 litres: Available in steel, 200 bar,[48] used for rebreathers</option>
                                <option value="4" @if(count($fillingStation) && in_array('4', $fillingStation)) selected @endif>4 litres: Available in steel, 200 bar,[48] used for rebreathers and pony cylinders</option>
                                <option value="3" @if(count($fillingStation) && in_array('3', $fillingStation)) selected @endif>3 litres: Available in steel, 200 bar,[48] used for rebreathers and pony cylinders</option>
                                <option value="2" @if(count($fillingStation) && in_array('2', $fillingStation)) selected @endif>2 litres: Available in steel, 200 bar,[48] used for rebreathers, pony cylinders, and suit inflation</option>
                                <option value="1.5" @if(count($fillingStation) && in_array('1.5', $fillingStation)) selected @endif>1.5 litres: Available in steel, 200 and 232 bar,[48] used for suit inflation</option>
                                <option value="0.5" @if(count($fillingStation) && in_array('0.5', $fillingStation)) selected @endif>0.5 litres: Available in steel and aluminium, 200 bar, used for buoyancy compensator and surface marker buoy inflation</option>
                            </select>
                        </div>
                    </div>

                    <div class="row margin-top-10">
                        <div class="col-md-2">
                            <label>Nitrox</label>
                        </div>

                        <div class="col-md-4">
                            <div class="btn-group" id="nitrox" data-toggle="buttons">
                                <label class="btn btn-default btn-on btn-sm @if(@$diveCenter->nitrox === 1) active @endif">
                                    <input type="radio" value="1" name="nitrox" @if(@$diveCenter->nitrox === 1) checked @endif>ON</label>

                                <label class="btn btn-default btn-off btn-sm @if(@$diveCenter->nitrox === 0) active @endif">
                                    <input type="radio" value="0" name="nitrox" @if(@$diveCenter->nitrox === 0) checked @endif>OFF</label>
                            </div>
                        </div>
                    </div>

                    <hr>

                    <h4>Dives offered by dive center</h4>

                    <div class="row">
                        <div class="col-md-2">
                            <label>Discovery Dives</label>
                        </div>

                        @php
                            $discoveryDives =   json_decode($diveCenter->discovery_dives);
                        @endphp
                        <div class="col-md-4">
                            <select name="discovery_dives[]" data-selected-text-format="count > 2" class="form-control selectpicker" multiple>
                                <option value="kid introductory dive" @if(isset($discoveryDives) && in_array('kid introductory dive', $discoveryDives)) selected @endif>Kid introductory dive (8-14 years old)</option>
                                <option value="kid group introductory dive" @if(isset($discoveryDives) && in_array('kid group introductory dive', $discoveryDives)) selected @endif>Kid group introductory dive</option>
                                <option value="adult introductory dive" @if(isset($discoveryDives) && in_array('adult introductory dive', $discoveryDives)) selected @endif>Adult introductory dive</option>
                                <option value="adult group introductory dive" @if(isset($discoveryDives) && in_array('adult group introductory dive', $discoveryDives)) selected @endif>Adult group introductory dive</option>
                            </select>
                        </div>
                    </div>

                    <div class="row margin-top-10">
                        <div class="col-md-2">
                            <label>Fun Dives</label>
                        </div>

                        @php
                            $funDives =   json_decode($diveCenter->fun_dives);
                        @endphp
                        <div class="col-md-4">
                            <select name="fun_dives[]" data-selected-text-format="count > 2" class="form-control selectpicker" multiple>
                                <option value="fun dive" @if(isset($funDives) && in_array('fun dive', $funDives)) selected @endif>Fun dive</option>
                                <option value="adult night dive" @if(isset($funDives) && in_array('adult night dive', $funDives)) selected @endif>Adult night dive</option>
                                <option value="kid night dive" @if(isset($funDives) && in_array('kid night dive', $funDives)) selected @endif>Kid night dive</option>
                                <option value="adult nitrox dive" @if(isset($funDives) && in_array('adult nitrox dive', $funDives)) selected @endif>Adult nitrox dive</option>
                            </select>
                        </div>
                    </div>

                    <div class="row margin-top-10">
                        <div class="col-md-2">
                            <label>Other Dives</label>
                        </div>

                        @php
                            $otherDives =   json_decode($diveCenter->other_dives);
                        @endphp
                        <div class="col-md-4">
                            <select name="other_dives[]" data-selected-text-format="count > 2" class="form-control selectpicker" multiple>
                                <option value="photography dive" @if(isset($otherDives) && in_array('photography dive', $otherDives)) selected @endif>Photography dive</option>
                                <option value="naturalist" @if(isset($otherDives) && in_array('naturalist', $otherDives)) selected @endif>Naturalist dive</option>
                                <option value="archeology" @if(isset($otherDives) && in_array('archeology', $otherDives)) selected @endif>Archeology</option>
                                <option value="passenger" @if(isset($otherDives) && in_array('passenger', $otherDives)) selected @endif>Passenger</option>
                            </select>
                        </div>
                    </div>

                    <hr>

                    <h4>Season Info</h4>

                    @php
                        $seasonInfo =   (array)json_decode($diveCenter->season);

                        $label      =   [
                            1   =>  'One',
                            2   =>  'Two',
                            3   =>  'Three',
                            4   =>  'Four'
                        ];

                        $seasonCount    =   1;
                    @endphp

                    <div class="row">
                        <div class="col-md-4">
                            <div class="box-with-shadow season-info-section">
                                <h4>Opening Season</h4>

                                <div class="form-group">
                                    <input type="checkbox" value="1" id="whole_year" name="whole_year" @if(count($seasonInfo) && $seasonInfo['whole_year'] == '1') checked @endif> The Whole Year
                                </div>

                                @if(count($seasonInfo) && $seasonInfo['whole_year'] == null)
                                    @php $info  =  $seasonInfo['info']; @endphp
                                    @foreach($info as $value)
                                        @foreach($value as $k   =>  $v)
                                        <div class="box-with-shadow season-box form-group">
                                            <div class="form-group">
                                                <div class="form-group">Season {{ $label[$seasonCount] }}</div>
                                                <label for="from">From</label>
                                                {{--<input type="text" id="from" class="form-control datepicker" data-date-format="yyyy/mm/dd" name="from"><span class="input-group-addon"><span class="glyphicon-calendar glyphicon"></span></span>--}}
                                                <div class="input-group date">
                                                    <input type="text" class="form-control datepicker" required data-date-format="yyyy/mm/dd" id="from" name="from[{{ $seasonCount }}]" value="{{ $v[0] }}"/>
                                                    <span class="input-group-addon"><span class="glyphicon-calendar glyphicon"></span></span>
                                                </div>
                                                <div class="form-group"></div>
                                                <label for="from">Till</label>

                                                <div class="input-group date">
                                                    <input type="text" id="from" class="form-control datepicker" required data-date-format="yyyy/mm/dd" name="till[{{ $seasonCount }}]" value="{{ $v[1] }}"/>
                                                    <span class="input-group-addon"><span class="glyphicon-calendar glyphicon"></span></span>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="season_feasibility">Season feasibility</label>
                                                <div class="half-width-input">
                                                    <select class="selectpicker form-control " name="season_feasibility[{{ $seasonCount }}]"  id="season_feasibility">
                                                        <option value="yes" @if($k == 'yes') selected @endif>YES</option>
                                                        <option value="no" @if($k == 'no') selected @endif>NO</option>
                                                        <option value="ideal" @if($k == 'ideal') selected @endif>IDEAL</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        @php $seasonCount++; @endphp
                                        @endforeach
                                    @endforeach
                                @endif

                                <button type="button" id="add-season-button" class="btn btn-primary add-season">Add Season</button>
                                <button type="button" id="remove-season-button" class="btn remove-season">Remove</button>
                            </div>
                        </div>
                    </div>

                    {{-- language model --}}
                    @php
                        $languageSpoken =   (array)json_decode($diveCenter->language_spoken);
                    @endphp

                    <div class="add_language">
                        <div class="modal fade" id="language" role="dialog">
                            <div class="modal-dialog modal-lg">
                                <!-- Modal content-->
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                                        <h4 class="modal-title">Select Language</h4>
                                    </div>
                                    <div class="modal-body">
                                        <div class="form-group">
                                            <div class="">
                                                <select id="language" data-size="5" data-selected-text-format="count > 4" data-live-search="true" multiple class="selectpicker form-control show-tick" name="language[]" title="language">
                                                    @foreach($languages_spoken as $languages)
                                                        <option data-content="<span class='flag-icon flag-icon-{{$languages->country_code}}'>&nbsp &nbsp &nbsp{{$languages->name}}({{$languages->iso_639_1}})</span>"
                                                                title="{{$languages->name}}"
                                                                data-tokens="{{$languages->name}}"
                                                                value="{{$languages->name}}"
                                                                @if(count($languageSpoken) && in_array($languages->name, $languageSpoken)) selected @endif>
                                                                {{$languages->name}}({{$languages->iso_639_1}})
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- activity model --}}
                    @php
                        $Activities =   (array)json_decode($diveCenter->activities);
                    @endphp

                    <div class="add_activity">
                        <div class="modal fade" id="activity" role="dialog">
                            <div class="modal-dialog modal-lg">
                                <!-- Modal content-->
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                                        <h4 class="modal-title">Select Activity</h4>
                                    </div>
                                    <div class="modal-body">
                                        @if(count($activities))
                                            <div class="row">
                                                @foreach($activities as $activity)
                                                    <div class="col-md-3 col-xs-3 text-center">
                                                        <img width="50" src="{{ asset('assets/images/scubaya/activities/'.$activity->id.'-'.$activity->icon) }}" alt="{{ $activity->name }}">
                                                        <p>{{ ucwords($activity->name) }}</p>
                                                        <input type="checkbox" name="activity[]" value="{{ $activity->id }}" @if(count($Activities) && in_array($activity->id, $Activities)) checked @endif>
                                                    </div>
                                                @endforeach
                                            </div>
                                        @else
                                            <h5 class="text-center">There is no activity to add.</h5>
                                        @endif
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- non diving activity model --}}
                    @php
                        $NonDivingActivities =   (array)json_decode($diveCenter->non_diving_activities);
                    @endphp
                    <div class="add_non_diving_activity">
                        <div class="modal fade" id="nonDivingActivity" role="dialog">
                            <div class="modal-dialog modal-lg">
                                <!-- Modal content-->
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                                        <h4 class="modal-title">Select Non Diving Activity</h4>
                                    </div>
                                    <div class="modal-body">
                                        @if(count($nonDivingActivities))
                                            <div class="row">
                                                @foreach($nonDivingActivities as $activity)
                                                    <div class="col-md-3 col-xs-3 text-center">
                                                        <img width="50" src="{{ asset('assets/images/scubaya/activities/'.$activity->id.'-'.$activity->icon) }}" alt="{{ $activity->name }}">
                                                        <p>{{ ucwords($activity->name) }}</p>
                                                        <input type="checkbox" name="non_diving_activity[]" value="{{ $activity->id }}" @if(count($NonDivingActivities) && in_array($activity->id, $NonDivingActivities)) checked @endif>
                                                    </div>
                                                @endforeach
                                            </div>
                                        @else
                                            <h5 class="text-center">There is no activity to add.</h5>
                                        @endif
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- facility model --}}
                    @php
                        $Facilities =   (array)json_decode($diveCenter->facilities);
                    @endphp
                    <div class="add_facility">
                        <div class="modal fade" id="facility" role="dialog">
                            <div class="modal-dialog modal-lg">
                                <!-- Modal content-->
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                                        <h4 class="modal-title">Select Facility</h4>
                                    </div>
                                    <div class="modal-body">
                                        @if(count($facilities))
                                            <div class="row">
                                                @foreach($facilities as $facility)
                                                    <div class="col-md-3 col-xs-3 text-center">
                                                        <img width="50" src="{{ asset('assets/images/scubaya/dive_center_facility/'.$facility->id.'-'.$facility->icon) }}" alt="{{ $facility->name }}">
                                                        <p>{{ ucwords($facility->name) }}</p>
                                                        <input type="checkbox" name="facility[]" value="{{ $facility->id }}" @if(count($Facilities) && in_array($facility->id, $Facilities)) checked @endif>
                                                    </div>
                                                @endforeach
                                            </div>
                                        @else
                                            <h5 class="text-center">There is no facility to add.</h5>
                                        @endif
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- speciality model --}}
                    @php
                        $Specialities =   (array)json_decode($diveCenter->specialities);
                    @endphp
                    <div class="add_speciality">
                        <div class="modal fade" id="speciality" role="dialog">
                            <div class="modal-dialog modal-lg">
                                <!-- Modal content-->
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                                        <h4 class="modal-title">Select Speciality</h4>
                                    </div>
                                    <div class="modal-body">
                                        @if(count($specialities))
                                            <div class="row">
                                                @foreach($specialities as $speciality)
                                                    <div class="col-md-3 col-xs-3 text-center">
                                                        <img width="50" src="{{ asset('assets/images/scubaya/speciality/'.$speciality->id.'-'.$speciality->icon) }}" alt="{{ $speciality->name }}">
                                                        <p>{{ ucwords($speciality->name) }}</p>
                                                        <input type="checkbox" name="speciality[]" value="{{ $speciality->id }}" @if(count($Specialities) && in_array($speciality->id, $Specialities)) checked @endif>
                                                    </div>
                                                @endforeach
                                            </div>
                                        @else
                                            <h5 class="text-center">There is no speciality to add.</h5>
                                        @endif
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- infrastructure model --}}
                    @php
                        $Infrastructure =   (array)json_decode($diveCenter->infrastructure);
                    @endphp
                    <div class="add_infrastructure">
                        <div class="modal fade" id="infrastructure" role="dialog">
                            <div class="modal-dialog modal-lg">
                                <!-- Modal content-->
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                                        <h4 class="modal-title">Select Infrastructure</h4>
                                    </div>
                                    <div class="modal-body">
                                        @if(count($infrastructure))
                                            <div class="row">
                                                @foreach($infrastructure as $structure)
                                                    <div class="col-md-3 col-xs-4 text-center">
                                                        <img width="50" src="{{ asset('assets/images/scubaya/infrastructure/'.$structure->id.'-'.$structure->icon) }}" alt="{{ $structure->name }}">
                                                        <p>{{ ucwords($structure->name) }}</p>
                                                        <input type="checkbox" name="infrastructure[]" value="{{ $structure->id }}" @if(count($Infrastructure) && in_array($structure->id, $Infrastructure)) checked @endif>
                                                    </div>
                                                @endforeach
                                            </div>
                                        @else
                                            <h5 class="text-center">There is no infrastructure to add.</h5>
                                        @endif
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- affiliation model --}}
                    @php
                        $Affiliations =   (array)json_decode($diveCenter->affiliations);
                    @endphp
                    <div class="add_affiliation">
                        <div class="modal fade" id="affiliation" role="dialog">
                            <div class="modal-dialog modal-lg">
                                <!-- Modal content-->
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                                        <h4 class="modal-title">Select Affiliation</h4>
                                    </div>
                                    <div class="modal-body">
                                        @if(count($affiliations))
                                            <div class="row">
                                                @foreach($affiliations as $affiliation)
                                                    <div class="col-md-2 col-xs-3 text-center">
                                                        <img width="50" src="{{ asset('assets/images/scubaya/affiliations/'.$affiliation->id.'-'.$affiliation->image) }}" alt="{{ $affiliation->name }}">
                                                        <p>{{ ucwords($affiliation->name) }}</p>
                                                        <input type="checkbox" name="affiliations[]" value="{{ $affiliation->id }}" @if(count($Affiliations) && in_array($affiliation->id, $Affiliations)) checked @endif>
                                                    </div>
                                                @endforeach
                                            </div>
                                        @else
                                            <h5 class="text-center">There is no affiliation to add.</h5>
                                        @endif
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- member affiliation model --}}
                    @php
                        $MA                 =   array();
                        $MemberAffiliations =   (array)json_decode($diveCenter->member_affiliations);

                        if(count($MemberAffiliations)) {
                            foreach($MemberAffiliations as $key => $value){
                                $MA[$key]   =  $value;
                            }
                        } //dd($MA);
                    @endphp
                    <div class="add_member_affiliation">
                        <div class="modal fade" id="memberAffiliation" role="dialog">
                            <div class="modal-dialog modal-lg">
                                <!-- Modal content-->
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                                        <h4 class="modal-title">Select Member Affiliation</h4>
                                    </div>
                                    <div class="modal-body">
                                        @if(count($affiliations))
                                            <div class="row">
                                                @php $count =   1; @endphp
                                                @foreach($affiliations as $affiliation)
                                                    <div class="col-md-3 col-xs-6 text-center">
                                                        <img width="50" src="{{ asset('assets/images/scubaya/affiliations/'.$affiliation->id.'-'.$affiliation->image) }}" alt="{{ $affiliation->name }}">
                                                        <p>{{ ucwords($affiliation->name) }}</p>
                                                        <label>Certification Number</label>
                                                        <input type="text" name="member_affiliations[{{$count}}][cno]" class="form-control" value="@if(array_key_exists($count, $MA)) {{ @$MA[$count]->cno }} @endif">
                                                        <input type="checkbox" name="member_affiliations[{{$count}}][aid]" value="{{ $affiliation->id or 0 }}" @if(count($MA) && array_key_exists($count, $MA) && isset($MA[$count]->aid) && $MA[$count]->aid == $affiliation->id) checked @endif>
                                                    </div>
                                                    @php $count++; @endphp
                                                @endforeach
                                            </div>
                                        @else
                                            <h5 class="text-center">There is no member affiliation to add.</h5>
                                        @endif
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- payment method model --}}
                    @php
                        $PaymentMethods =   (array)json_decode($diveCenter->payment_methods);
                    @endphp
                    <div class="add_payment_method">
                        <div class="modal fade" id="paymentMethod" role="dialog">
                            <div class="modal-dialog modal-lg">
                                <!-- Modal content-->
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                                        <h4 class="modal-title">Select Payment Method</h4>
                                    </div>
                                    <div class="modal-body">
                                        @if(count($paymentMethods))
                                            <div class="row">
                                                @foreach($paymentMethods as $paymentMethod)
                                                    <div class="col-md-3 col-xs-3 text-center">
                                                        <img width="50" src="{{ asset('assets/images/scubaya/payment_methods/'.$paymentMethod->id.'-'.$paymentMethod->icon) }}" alt="{{ $paymentMethod->name }}">
                                                        <p>{{ ucwords($paymentMethod->name) }}</p>
                                                        <input type="checkbox" name="payment_method[]" value="{{ $paymentMethod->id }}" @if(count($PaymentMethods) && in_array($paymentMethod->id, $PaymentMethods)) checked @endif>
                                                    </div>
                                                @endforeach
                                            </div>
                                        @else
                                            <h5 class="text-center">There is no payment method to add.</h5>
                                        @endif
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="box-footer">
                        <a href="{{ route('scubaya::merchant::dive_center::dive_centers', [Auth::id()]) }}"><button type="button" class="btn btn-default">Cancel</button></a>
                        <button type="submit" class="btn btn-info pull-right" id="submit" data-toggle="modal" data-target="#verification-form-modal{{@$diveCenter->id}}">Update</button>
                    </div>
                </form>
            </div>
            {{-- include verification model --}}
            @if(session()->get('show_popup') == 'true' || $errors->verificationError->any())
                @include('merchant.layouts.website_verification.verification_modal', ['route1' => 'scubaya::merchant::dive_center::verification',
                'route2' => 'scubaya::merchant::dive_center::dive_centers','website' => session()->get('diveCenter')])
            @endif
        </div>
    </section>

    <script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAm_-PodAPns0u0-bvF3qHHV3G_sLe0gdI&libraries=places"></script>
    <script type="text/javascript">
        @if(session()->get('show_popup') == 'true' || $errors->verificationError->any())
        jQuery('#submit').attr('type', 'button');
        var modelId =   jQuery('#submit').attr('data-target');
        jQuery(modelId).modal('show');
        @endif

        jQuery(document).ready(function(scubaya) {
            initialize();
            // CKEDITOR.replace( 'dive_center_description' );
            // CKEDITOR.replace( 'information_description' );
            // CKEDITOR.replace( 'read_before_you_go');

            $('.datetimepicker').datetimepicker({
                format: 'LT'
            });

            $('.datepicker').datepicker();

            $('#whole_year').click(function(){

                if($('#add-season-button').hasClass('btn-danger')){
                    $('#add-season-button').removeClass('btn-danger');
                    $('#add-season-button').addClass('btn-primary');
                    $('#add-season-button').html('Add season');
                }

                if($(this).prop('checked')){
                    $('.add-season').prop('disabled','disabled');
                    $('.season-box').remove();
                    season = 1;
                } else {
                    $('.add-season').prop('disabled',false);

                    $('<div class="box-with-shadow season-box form-group">' +
                        '<div class="form-group">Season One</div>' +
                        '<div class="form-group">' +
                        '<label for="from">From</label>' +
                        '<div class="input-group date">' +
                        '<input type="text" class="form-control datepicker" required data-date-format="yyyy/mm/dd" id="from" name="from[1]" />' +
                        '<span class="input-group-addon"><span class="glyphicon-calendar glyphicon"></span></span>' +
                        '</div>' +
                        '<div class="form-group"></div>' +
                        '<label for="from">Till</label>' +
                        '<div class="input-group date">' +
                        '<input type="text" id="from" class="form-control datepicker" required data-date-format="yyyy/mm/dd" name="till[1]"/>' +
                        '<span class="input-group-addon"><span class="glyphicon-calendar glyphicon"></span></span>' +
                        '</div>' +
                        '</div>' +
                        '<div class="form-group">' +
                        '<label for="season_feasibility">Season feasibility</label>' +
                        '<div class="half-width-input">' +
                        '<select class="form-control " name="season_feasibility[1]"  id="season_feasibility">' +
                        '<option value="yes">YES</option>' +
                        '<option value="no">NO</option>' +
                        '<option value="ideal">IDEAL</option>' +
                        '</select>' +
                        '</div>' +
                        '</div>' +
                        '</div>'
                    ).insertAfter($('.season-info-section').find('div.form-group'));

                    $('.datepicker').datepicker();

                    season = 2;
                }
            });

            $('#remove-season-button').on('click',function(){
                if($('.add-season').prev().hasClass('season-box')) {
                    if($('.add-season').hasClass('btn-danger')) {
                        $('.add-season').removeClass('btn-danger');
                        $('.add-season').addClass('btn-primary');
                        $('.add-season').html('Add Season');
                    }
                    $('.add-season').prev().remove();
                    season--;
                } else {
                    $(this).prop('disabled',true);
                }
            });

            let season  =   '{{ $seasonCount }}';
            season      =   parseInt(season);

            let convert =   {
                _1:'one',
                _2:'Two',
                _3:'Three',
                _4:'Four'
            };

            $('#click-me-link').click(function(){
                $('#dive_center_facilities').trigger('click');
            });

            scubaya(".add-season").on('click',function() {
                if(season < 5) {
                    if($('#remove-season-button').prop('disabled')) {
                        $('#remove-season-button').prop('disabled',false);
                    }

                    scubaya(this).before('<div class="box-with-shadow season-box form-group">' +
                        '<div class="form-group">' +
                        '<div class="form-group">Season '+convert['_'+season]+'</div>' +
                        '<label for="from">From</label>' +
                        '<div class="input-group date">' +
                        '<input type="text" id="from" class="form-control datepicker " required data-date-format="yyyy/mm/dd" name="from['+season+']">' +
                        '<span class="input-group-addon"><span class="glyphicon-calendar glyphicon"></span></span>' +
                        '</div>' +
                        '<div class="form-group"></div>' +
                        '<label for="from">Till</label>' +
                        '<div class="input-group date">' +
                        '<input type="text" id="from" class="form-control datepicker " required data-date-format="yyyy/mm/dd" name="till['+season+']">' +
                        '<span class="input-group-addon"><span class="glyphicon-calendar glyphicon"></span></span>' +
                        '</div>' +
                        '</div>' +
                        '<div class="form-group">' +
                        '<label for="season_feasibility">Season feasibility</label>' +
                        '<div class="half-width-input">' +
                        '<select class="selectpicker form-control" name="season_feasibility['+season+']"  id="season_feasibility">' +
                        '<option value="yes">YES</option>' +
                        '<option value="no">NO</option>' +
                        '<option value="ideal">IDEAL</option>' +
                        '</select>' +
                        '</div>' +
                        '</div>' +
                        '</div>');
                    season++;
                    scubaya('.selectpicker').selectpicker('refresh');
                    scubaya('.datepicker').datepicker();
                } else if(season >= 5) {
                    $('#add-season-button').removeClass('btn-primary');
                    $('#add-season-button').addClass('btn-danger');
                    $('#add-season-button').html("Can't add more");
                }
            });
        });

        var readtd =   '{{$readCount}}';
        readtd     =    parseInt(readtd) + 1;

        $('.add_new_read_td_section').click(function () {
            var html    =   '<div class="row margin-top-10">' +
                '<div class="col-md-3">' +
                '<input name="read['+readtd+'][title]" class="form-control" placeholder="Title">' +
                '</div>' +
                '<div class="col-md-4">' +
                '<textarea cols="8" rows="5" class="form-control" placeholder="Description" name="read['+readtd+'][description]"></textarea>' +
                '</div>' +
                '<div class="col-md-5">' +
                '<button name="remove-read-title-desc-section" type="button" class="btn btn-danger remove-read-title-desc-section">Remove</button>'+
                '</div>' +
                '</div>';

            $('.read-title-desc-section').append(html);

            readtd++;
        });

        $(document).on('click', '.remove-read-title-desc-section', function () {
            $(this).parent('div').parent('div').remove();
        });

        /* google address auto complete api */
        var placeSearch, autocomplete;
        var componentForm = {
            locality: 'long_name',
            administrative_area_level_1: 'long_name',
            country : 'long_name',
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

        function readURL(input) {
            $('#gallery').nextAll('img').remove();
            if (input.files && input.files[0] && input.files.length == 1 ) {
                var reader      = new FileReader();
                reader.onload   = function (e) {
                    jQuery(input).after('<img src="'+e.target.result+'" width="30%" height="30%" style="margin: 10px 10px 10px 10px">');
                };
                reader.readAsDataURL(input.files[0]);
            }else{
                var i =1;
                for(i;i<input.files.length+1;i++){
                    var reader = new FileReader();
                    reader.onload = function (e) {
                        jQuery(input).after('<img src="'+e.target.result+'" width="30%" height="30%" style="margin: 10px 5px 10px 5px">');
                    };
                    reader.readAsDataURL(input.files[i-1]);
                }
            }
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