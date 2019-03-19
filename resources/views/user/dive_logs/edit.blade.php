@extends('user.layouts.app')
@section('title','Edit Log Dive')
@section('contentheader')
    <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> User</a></li>
        <li><a href="#"><i class="fa fa-dashboard"></i> Dive Logs</a></li>
        <li class="active">Edit Log Dive</li>
    </ol>
@endsection
@section('content')
    @php
        $labels =   [
            '1' =>  'One',
            '2' =>  'Two',
            '3' =>  'Three',
            '4' =>  'Four',
        ];
    @endphp

    <section class="content new-dive-logs">
        <div class="row margin-20">
            <div class="col-md-12">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title">Edit Log Dive</h3>
                    </div>
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
                    <form method="post" action="{{route('scubaya::user::dive_logs::update',[Auth::id(), $diveLog->id])}}">
                        {{csrf_field()}}
                        <div class="box-body">
                            <div class="row">
                                <div class="col-md-5">
                                    <div class="form-group">
                                        <label for="log_name">Log Name</label>
                                        <input type="text" class="form-control" id="log_name" name="log_name" value="{{ $diveLog->log_name }}">
                                    </div>
                                    <div class="form-group">
                                        <label for="dive_number">Dive Number</label>
                                        <input type="text" class="form-control" id="dive_number" name="dive_number" value="{{ $diveLog->dive_number }}">
                                    </div>
                                    <div class="form-group">
                                        <label for="date">Date</label>
                                        <div class="input-group">
                                            <span class="input-group-addon"><i class="glyphicon glyphicon-calendar"></i></span>
                                            <input type="text" class="form-control datepicker" id="date" data-date-format="yyyy/mm/dd" name="log_date" value="{{ $diveLog->log_date}}">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="training_dive" class="control-label" data-toggle="tooltip">Training
                                            Dive</label><br>
                                        <div class="btn-group" id="training_dive" data-toggle="buttons">
                                            <label class="btn btn-default btn-on btn-sm @if($diveLog->training_dive == 1) active @endif">
                                                <input type="radio" value="1" name="training_dive" @if($diveLog->training_dive == 1) checked @endif>YES</label>

                                            <label class="btn btn-default btn-off btn-sm @if($diveLog->training_dive == 0) active @endif ">
                                                <input type="radio" value="0" name="training_dive" @if($diveLog->training_dive == 0) checked @endif>NO</label>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label for="dive_mode">Dive Mode</label>
                                        <select id="dive_mode" class="selectpicker form-control show-tick" name="dive_mode">
                                            <option value="oc recreational" @if($diveLog->dive_mode == 'oc recreational') selected @endif>OC Recreational</option>
                                            <option value="oc technical" @if($diveLog->dive_mode == 'oc technical') selected @endif>OC Technical</option>
                                            <option value="cc/bo" @if($diveLog->dive_mode == 'cc/bo') selected @endif>CC/BO</option>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="dive_center">Dive Center:</label>
                                        <input type="text" class="form-control" id="dive_center" name="dive_center" value="{{ $diveLog->dive_center}}" >
                                    </div>

                                    <div class="form-group">
                                        <div class="box-with-shadow">
                                            <h4>Dive Buddy</h4>
                                            <div class="form-group">
                                                <input type="checkbox" value="1" id="verify_my_dive" name="verify_my_dive" @if($diveLog->verify_my_dive_status) checked @endif> verify my dive
                                            </div>

                                            @php
                                                $count  =   1;
                                                $buddy  =   (array)json_decode($diveLog->buddy);
                                            @endphp

                                            @if($buddy)
                                                @foreach($buddy as $key1 => $value1)
                                                    @foreach($value1 as $key2 => $value2)
                                                    <div class="box-with-shadow half-width-input form-group">
                                                        <div class="form-group">
                                                            <div class="form-group">Buddy {{ $labels[$count] }}</div>
                                                            <label for="buddy_name">Buddy Name</label>
                                                            <div class="input-group date">
                                                                <input type="text" class="form-control" required id="buddy_name"
                                                                       name="buddy_name[1]" value="{{ $value2[0] }}"/>
                                                            </div>
                                                            <div class="form-group"></div>
                                                            <label for="scby_user_id">SCBY user ID</label>

                                                            <div class="input-group date">
                                                                <input type="text" required id="scby_user_id" class="form-control"
                                                                       name="scby_user_id[1]" value="{{ $value2[1] }}" />
                                                            </div>
                                                        </div>
                                                        <div class="form-group">
                                                            <div class="half-width-input">
                                                                <label for="buddy_type">Buddy Type</label>
                                                                <select class="selectpicker form-control" name="buddy_type[1]" id="buddy_type">
                                                                    <option value="instructor" @if($key2 == 'instructor') selected @endif>Instructor</option>
                                                                    <option value="guide" @if($key2 == 'guide') selected @endif>Guide</option>
                                                                    <option value="buddy" @if($key2 == 'buddy') selected @endif>Buddy</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    @php $count++; @endphp
                                                    @endforeach
                                                @endforeach
                                            @endif
                                            <button type="button" id="add-button"    class="btn btn-primary add-buddy">Add Buddy</button>
                                            <button type="button" id="remove-button" class="btn remove-buddy">Remove Buddy</button>
                                        </div>
                                    </div>

                                    @php
                                        $equipments =   (array)json_decode($diveLog->equipments);
                                    @endphp

                                    <div class="form-group">
                                        <label for="template_content" data-toggle="tooltip">Add Equipment</label>
                                        <div class="box-with-shadow">
                                            <div id="selected-equipment"></div>
                                            <button type="button" class="btn btn-primary" data-toggle="modal" data-target=".add-equipments">Add Equipment</button>
                                        </div>
                                    </div>
                                    <div class="modal add-equipments" tabindex="-1" role="dialog">
                                        <div class="modal-dialog modal-sm" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h4 class="modal-title">Add Equipment</h4>
                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="form-group">
                                                        @php
                                                            $menus = array('child','other','adult')
                                                        @endphp
                                                        <select data-actions-box="true" data-selected-text-format="count > 2" class="add_equipment form-control selectpicker show-tick" multiple id="add_equipment" name="equipments[]" data-size="5">
                                                            @foreach($menus as $menu)
                                                                <optgroup label="{{$menu}}">
                                                                    @foreach(\Illuminate\Support\Facades\DB::table('gears')->where('category',$menu)->get() as $submenu)
                                                                        <option @if($equipments && in_array($submenu->name, $equipments)) selected @endif>{{$submenu->name}}</option>
                                                                    @endforeach
                                                                </optgroup>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="template_content" data-toggle="tooltip">Notes</label>
                                        <textarea class="form-control" id="notes"
                                                  placeholder="Enter the html here" rows="25"
                                                  name="notes">{{ $diveLog->notes }}</textarea>
                                    </div>
                                </div>
                                <div class="col-md-7">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group row">
                                                <label for="dive_site" class="col-sm-3 col-form-label"><i class="fa fa-map-marker blue map-marker"></i> Dive Site:</label>
                                                <div class="col-sm-6">
                                                    <input type="text" id="dive_site" name="dive_site" class="form-control" value="{{ $diveLog->dive_site}}"  >
                                                </div>

                                                <div class="col-sm-1">
                                                    <p class="margin-top-10 margin-bottom-0">OR</p>
                                                </div>

                                                <div class="col-sm-2">
                                                    <button type="button" class="btn btn-primary" id="add-dive-site" data-toggle="modal" data-target="#new-dive-site">New</button>

                                                    <div class="modal fade bs-example-modal-md" id="new-dive-site" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel">
                                                        <div class="modal-dialog modal-md" role="document">
                                                            <div class="modal-content">
                                                                <div class="modal-header">
                                                                    <h4 class="modal-title blue">Add Dive Site</h4>
                                                                </div>

                                                                <div class="modal-body">
                                                                    <p data-name="site-title-error" class="text-danger" style="display: none;">Please drag the pin or enter site name!</p>
                                                                    <p data-name="latitude-error" class="text-danger" style="display: none;">Please drag the pin or enter latitude!</p>
                                                                    <p data-name="longitude-error" class="text-danger" style="display: none;">Please drag the pin or enter longitude!</p>
                                                                    <p data-name="site-error" class="text-danger" style="display: none;">This dive site already exists!</p>

                                                                    <div class="row">
                                                                        <div class="col-md-12">
                                                                            <div class="form-group row">
                                                                                <label for="dive_site" class="col-sm-3 col-form-label"><i class="fa fa-map-marker blue map-marker"></i> Dive Site:</label>
                                                                                <div class="col-sm-6">
                                                                                    <input type="text" id="new_dive_site" name="new_dive_site" class="form-control" value="{{ old('dive_site') ? old('dive_site'): @$userDiveLog->dive_site}}"  >
                                                                                </div>
                                                                            </div>

                                                                            <div class="row">
                                                                                <div class="col-md-12">
                                                                                    <div class="drag-marker-position">
                                                                                        <span>Drag the pin to set the exact map position</span>
                                                                                    </div>
                                                                                </div>
                                                                            </div>

                                                                            <div class="row">
                                                                                <div class="col-md-12">
                                                                                    <div class="form-group">
                                                                                        <div id="location_log_dive" style=" height: 300px"></div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>

                                                                            <div class="row">
                                                                                <div class="col-md-6">
                                                                                    <div class="form-group row">
                                                                                        <label for="longitude" class="col-sm-3 col-form-label">Longitude</label>
                                                                                        <div class="col-sm-9">
                                                                                            <input type="text" class="form-control" id="longitude" readonly name="longitude" value="{{ old('longitude') ? old('longitude'): @$diveCenter->longitude }}">
                                                                                        </div>
                                                                                    </div>
                                                                                </div>

                                                                                <div class="col-md-6">
                                                                                    <div class="form-group row">
                                                                                        <label for="latitude" class="col-sm-3 col-form-label">Latitude</label>
                                                                                        <div class="col-sm-9">
                                                                                            <input type="text" class="form-control" id="latitude" readonly name="latitude" value="{{ old('latitude') ? old('latitude'): @$diveCenter->latitude }}">
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>

                                                                <div class="modal-footer">
                                                                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                                                    <button type="button" class="btn btn-primary add-new-dive-site" data-url="{{ route('scubaya::user::dive_logs::add_dive_site', [Auth::id()]) }}">Save</button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="box-with-shadow">
                                        <div class="row">
                                            <div class="col-md-9">
                                                <h4>Diving conditions</h4>
                                            </div>
                                            <div class="col-md-3">
                                                <label for="dob">Collapse all</label>
                                                <button type="button" id="collapse_button"  class="btn btn-toggle" data-toggle="collapse" onclick="changeStatus(this)" data-target="#diving_conditions">
                                                    <div class="handle">
                                                        <input type="hidden" id="collapse_all" name="collapse_all">
                                                    </div>
                                                </button>
                                            </div>
                                        </div>

                                        <div id="diving_conditions" class="collapse in">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <h5>Day dive</h5>
                                                    <div class="btn-group" data-toggle="buttons">
                                                        <button type="button" class="btn btn-default @if($diveLog->day_dive == 'sunny_day') active @endif">
                                                            <i class="wi wi-day-sunny" title="sunny day"></i>
                                                            <input type="radio" name="day_dive" value="sunny_day"
                                                                   autocomplete="off" @if($diveLog->day_dive == 'sunny_day') checked @endif>
                                                        </button>
                                                        <button type="button" class="btn btn-default @if($diveLog->day_dive == 'cloudy_day') active @endif">
                                                            <i class="wi wi-day-cloudy" title="cloudy day"></i>
                                                            <input type="radio" name="day_dive" value="cloudy_day"
                                                                   autocomplete="off" @if($diveLog->day_dive == 'cloudy_day') checked @endif>
                                                        </button>

                                                        <button type="button" class="btn btn-default @if($diveLog->day_dive == 'cloud_day') active @endif">
                                                            <i class="wi wi-cloud" title="cloud day"></i>
                                                            <input type="radio" name="day_dive" value="cloud_day"
                                                                   autocomplete="off" @if($diveLog->day_dive == 'cloud_day') checked @endif>
                                                        </button>

                                                        <button type="button" class="btn btn-default @if($diveLog->day_dive == 'hail_day') active @endif">
                                                            <i class="wi wi-day-hail" title="hail day"></i>
                                                            <input type="radio" name="day_dive" value="hail_day"
                                                                   autocomplete="off" @if($diveLog->day_dive == 'hail_day') checked @endif>
                                                        </button>
                                                    </div>
                                                </div>

                                                <div class="col-md-6">
                                                    <h5>Night dive</h5>
                                                    <div class="btn-group" data-toggle="buttons">
                                                        <button type="button" class="btn btn-default @if($diveLog->night_dive == 'night_clear') active @endif">
                                                            <i class="wi wi-night-clear" title="night clear"></i>
                                                            <input type="radio" name="night_dive" value="night_clear"
                                                                   autocomplete="off" @if($diveLog->day_dive == 'night_clear') checked @endif>
                                                        </button>

                                                        <button type="button" class="btn btn-default @if($diveLog->night_dive == 'night_alt_cloudy') active @endif">
                                                            <i class="wi wi-night-alt-cloudy" title="night cloudy"></i>
                                                            <input type="radio" name="night_dive" value="night_alt_cloudy"
                                                                   autocomplete="off" @if($diveLog->day_dive == 'night_alt_cloudy') checked @endif>
                                                        </button>

                                                        <button type="button" class="btn btn-default @if($diveLog->night_dive == 'night_cloud') active @endif">
                                                            <i class="wi wi-cloud" title="night cloud"></i>
                                                            <input type="radio" name="night_dive" value="night_cloud"
                                                                   autocomplete="off" @if($diveLog->day_dive == 'night_cloud') checked @endif>
                                                        </button>

                                                        <button type="button" class="btn btn-default @if($diveLog->night_dive == 'night_alt_rain') active @endif">
                                                            <i class="wi wi-night-alt-rain" title="night rain"></i>
                                                            <input type="radio" name="night_dive" value="night_alt_rain"
                                                                   autocomplete="off" @if($diveLog->day_dive == 'night_alt_rain') checked @endif>
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row dive-type">
                                                <div class="col-md-6"></div>
                                                <div class="btn-group" data-toggle="buttons">
                                                    <button type="button" class="btn btn-default @if($diveLog->dive_type == 'boat_dive') active @endif">
                                                        <img src="{{asset('assets/images/dive-icons/wi-shore_dive.png')}}" />
                                                        <input type="radio" name="dive_type" value="boat_dive"
                                                               autocomplete="off" @if($diveLog->dive_type == 'boat_dive') checked @endif>
                                                        <span>Boat Dive &nbsp;</span>
                                                    </button>
                                                    <button type="button" class="btn btn-default @if($diveLog->dive_type == 'shore_dive') active @endif">
                                                        <img src="{{asset('assets/images/dive-icons/wi-shore_dive.png')}}" />
                                                        <input type="radio" name="dive_type" value="shore_dive"
                                                               autocomplete="off" @if($diveLog->dive_type == 'shore_dive') checked @endif>
                                                        <span>Shore Dive</span>
                                                    </button>

                                                    <button type="button" class="btn btn-default @if($diveLog->dive_type == 'lake_dive') active @endif">
                                                        <img src="{{asset('assets/images/dive-icons/wi-lake.png')}}" />
                                                        <input type="radio" name="dive_type" value="lake_dive"
                                                               autocomplete="off" @if($diveLog->dive_type == 'lake_dive') checked @endif>
                                                        <span>Lake Dive &nbsp;</span>
                                                    </button>

                                                    <button type="button" class="btn btn-default @if($diveLog->dive_type == 'sea_dive') active @endif">
                                                        <img src="{{asset('assets/images/dive-icons/wi-sea-wave.png')}}" />
                                                        <input type="radio" name="dive_type" value="sea_dive"
                                                               autocomplete="off" @if($diveLog->dive_type == 'sea_dive') checked @endif>
                                                        <span>Sea Dive &nbsp;</span>
                                                    </button>

                                                    <button type="button" class="btn btn-default @if($diveLog->dive_type == 'river_dive') active @endif">
                                                        <img src="{{asset('assets/images/dive-icons/wi-river.png')}}" />
                                                        <input type="radio" name="dive_type" value="river_dive"
                                                               autocomplete="off" @if($diveLog->dive_type == 'river_dive') checked @endif>
                                                        <span>River Dive</span>
                                                    </button>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-6 form-group">
                                                    <label for="temperature">Air temperature</label>
                                                    <div class="input-group">
                                                        <input type="text" class="form-control" id="temperature"
                                                               name="temperature" placeholder="Temperature" value="{{ $diveLog->temperature }}">
                                                        <div class="input-group-addon">°C</div>
                                                    </div>
                                                </div>
                                                <div class="col-md-6 form-group">
                                                    <label for="altitude">Altitude:<b>( In Mtr )</b></label>
                                                    <div class="input-group">
                                                        <input type="text" class="form-control" id="altitude"
                                                               name="altitude" placeholder="Altitude" value="{{ $diveLog->altitude }}">
                                                        <div class="input-group-addon">Mtr</div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="wave-box well-lg row">
                                                <div class="col-md-9">
                                                    <div class="row">
                                                        <div class="row">
                                                            <div class="col-md-6">
                                                                <h5>Waves</h5>
                                                            </div>
                                                            <input type="checkbox" id="waves_availability" name="waves_availability" @if($diveLog->waves == NULL) checked @endif>NA
                                                        </div>
                                                        <div id="circles_slider_for_waves">
                                                            <input type="hidden" name="waves" id="waves">
                                                        </div>
                                                    </div>
                                                    <div class="row margin-top-20">
                                                        <div class="row">
                                                            <div class="col-md-6">
                                                                <h5>Current</h5>
                                                            </div>
                                                            <input type="checkbox" value="1" id="current_availability" name="current_availability" @if($diveLog->current == NULL) checked @endif>NA
                                                        </div>
                                                        <div id="circles_slider_for_current">
                                                            <input type="hidden" name="current" id="current">
                                                        </div>
                                                    </div>

                                                    <div class="row margin-top-20">
                                                        <div class="row">
                                                            <div class="col-md-6">
                                                                <h5>Visibility</h5>
                                                            </div>
                                                            <input type="checkbox" value="1" id="visibility_availability" name="visibility_availability" @if($diveLog->visibility == NULL) checked @endif>NA
                                                        </div>
                                                        <div id="circles_slider_for_visibility">
                                                            <input type="hidden" name="visibility" id="visibility">
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-md-3">
                                                    <div class="row surface-margin-top">
                                                        <label>Surface:</label>
                                                        <div class="input-group">
                                                            <input type="text" class="form-control" name="surface_temperature" id="surface_temperature" value="{{ $diveLog->surface_temperature }}">
                                                            <div class="input-group-addon">C</div>
                                                        </div>
                                                    </div>
                                                    <div class="row bottom-margin-top">
                                                        <label>Bottom:</label>
                                                        <div class="input-group">
                                                            <input type="text" name="bottom_temperature" class="form-control" id="bottom_temperature" value="{{ $diveLog->bottom_temperature }}">
                                                            <div class="input-group-addon">C</div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            @php
                                                $waterTime  =   (array)json_decode($diveLog->water_time);
                                            @endphp

                                            <div class="wave-box well-lg " style="margin-top: 10px">
                                                <div class="row">
                                                    <div class="col-md-4"><label>Enter water</label>
                                                        <div class="input-group bootstrap-timepicker timepicker" id="enter_timepicker">
                                                            <input id="enter_water_time" name="enter_water_time" type="text" class="form-control input-small" value="{{ $waterTime ? $waterTime['enter_water_time'] : '' }}">
                                                            <span class="input-group-addon"><i class="glyphicon glyphicon-time"></i></span>
                                                        </div>
                                                    </div>

                                                    <div class="col-md-4"><label>Exit water</label>
                                                        <div class="input-group bootstrap-timepicker timepicker" id="exit_timepicker">
                                                            <input id="exit_water_time" type="text" name="exit_water_time" class="form-control input-small" value="{{ $waterTime ? $waterTime['exit_water_time'] : '' }}">
                                                            <span class="input-group-addon"><i class="glyphicon glyphicon-time"></i></span>
                                                        </div>
                                                    </div>

                                                    <div class="col-md-1">
                                                        <div class="row" style="margin-top: 30px"><label>=</label></div>
                                                    </div>

                                                    <div class="col-md-3"><label>Total time</label>
                                                        <div class="input-group">
                                                            <input type="text" class="form-control" id="total_time" name="total_time" maxlength="5" value="{{ $diveLog->total_time }}">
                                                            <div class="input-group-addon">Min</div>
                                                        </div>
                                                    </div>
                                                </div>

                                                @php
                                                    $pressure   =   (array)json_decode($diveLog->pressure_in_enter_exit_water_time);
                                                @endphp

                                                <div class="row" style="margin-top: 10px">
                                                    <div class="col-md-4">
                                                        <div class="dropdown">
                                                            <label>pressure</label>
                                                            <select class="form-control" name="enter_pressure">
                                                                @foreach($A_Z_listing_for_pressure as $a_z)
                                                                    <option value="{{$a_z}}" @if($pressure && $pressure['enter_pressure'] == $a_z) selected @endif>{{$a_z}}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="dropdown">
                                                            <label>pressure</label>
                                                            <select class="form-control" name="exit_pressure">
                                                                @foreach($A_Z_listing_for_pressure as $a_z)
                                                                    <option value="{{$a_z}}" @if($pressure && $pressure['exit_pressure'] == $a_z) selected @endif>{{$a_z}}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>

                                                @php
                                                    $pressure   =   (array)json_decode($diveLog->pressure);
                                                @endphp

                                                <div class="row margin-top-60">
                                                    <div class="col-md-4"><label>Start pressure</label>
                                                        <div class="input-group">
                                                            <input type="text" class="form-control" id="start_pressure"
                                                                   name="start_pressure"
                                                                   placeholder="  :  " maxlength="5"
                                                                   value="{{ $pressure ? $pressure['start_pressure'] : '' }}"
                                                            >
                                                            <div class="input-group-addon">Bar</div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4"><label>End pressure</label>
                                                        <div class="input-group">
                                                            <input type="text" class="form-control" id="end_pressure"
                                                                   name="end_pressure"
                                                                   placeholder="  :  " maxlength="5"
                                                                   value="{{ $pressure ? $pressure['end_pressure'] : '' }}"
                                                            >
                                                            <div class="input-group-addon">Bar</div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-1">
                                                        <div class="row" style="margin-top: 30px"><label>=</label></div>
                                                    </div>
                                                    <div class="col-md-3"><label>Used</label>
                                                        <div class="input-group">
                                                            <input type="text" class="form-control" id="used_pressure"
                                                                   name="used_pressure"
                                                                   maxlength="5"
                                                                   value="{{ $pressure ? ($pressure['start_pressure'] - $pressure['end_pressure']) : '' }}"
                                                            >
                                                            <div class="input-group-addon">Bar</div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="row margin-top-60">
                                                    <div class="col-md-3">
                                                        <label>Tank:</label>
                                                        <div class="input-group">
                                                            <input type="text" class="form-control" id="tank_total"
                                                                   name="tank_total"
                                                                   maxlength="5"
                                                                   value="{{ $diveLog->tank_capacity }}"
                                                            >
                                                            <div class="input-group-addon">Ltr</div>
                                                        </div>
                                                    </div>

                                                    <div class="col-md-9">
                                                        <label>Tank Type:</label><br/>
                                                        <div class="btn-group" data-toggle="buttons">
                                                            <label class="btn btn-default @if($diveLog->tank_type == 'aluminium') active @endif">
                                                                <input type="radio" name="tank_type" value="aluminium"
                                                                       autocomplete="off" @if($diveLog->tank_type == 'aluminium') checked @endif>Aluminium
                                                            </label>
                                                            <label class="btn btn-default @if($diveLog->tank_type == 'steel') active @endif">
                                                                <input type="radio" name="tank_type" value="steel"
                                                                       autocomplete="off" @if($diveLog->tank_type == 'steel') checked @endif> Steel
                                                            </label>
                                                            <label class="btn btn-default @if($diveLog->tank_type == 'others') active @endif">
                                                                <input type="radio" name="tank_type" value="others"
                                                                       autocomplete="off" @if($diveLog->tank_type == 'others') checked @endif> Others
                                                            </label>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="row margin-top-60">
                                                    <div class="col-md-12">
                                                        <label>Oxygen %</label>
                                                        <div class="oxygen-slider">
                                                            <input type="hidden" name="oxygen" id="oxygen">
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="row">
                                                    <div class="col-md-4"><label>Average depth</label>
                                                        <div class="input-group">
                                                            <input type="text" class="form-control" id="average_depth" name="average_depth" value="{{ $diveLog->average_depth }}">
                                                            <div class="input-group-addon">Mtr</div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4"><label>Maximum depth</label>
                                                        <div class="input-group">
                                                            <input type="text" class="form-control" id="maximum_depth" name="maximum_depth" maxlength="5" value="{{ $diveLog->maximum_depth }}">
                                                            <div class="input-group-addon">Mtr</div>
                                                        </div>
                                                    </div>
                                                </div>
                                                @if($show)
                                                    <div class="row margin-top-60">
                                                        <div class="col-md-4"><label>Your surface Interval</label>
                                                            <div class="input-group" id="surface_interval_group">
                                                                <input type="text" class="form-control"
                                                                       id="surface_interval"
                                                                       name="surface_interval"
                                                                       maxlength="5"
                                                                       value="{{ $diveLog->surface_interval }}"
                                                                >
                                                                <div class="input-group-addon">Hrs</div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-8">
                                                        <span class="glyphicon glyphicon-hand-left"
                                                              onclick="calculate_surface_interval()"></span>
                                                            <span class="click_description">click here to see surface_interval</span>
                                                        </div>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="box-footer">
                            <a href="{{ route('scubaya::user::dive_logs::index', [  Auth::id() ])}}"><button type="button" class="btn btn-default">Cancel</button></a>
                            <input type="submit" class="btn btn-info pull-right" id="submit" data-toggle="modal" onclick="settingAllValues()" value="Update">
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
    @php
        $clientGeoInfo =   geoip($_SERVER['REMOTE_ADDR']);
    @endphp

    <script type="text/javascript">

        var diveCenterName  =   @php echo json_encode($diveCenterName) @endphp ;
        var diveSite        =   @php echo json_encode($diveSites) @endphp;

        var isWaveAvailable         =   '{{ $diveLog->waves }}';
        var isCurrentAvailable      =   '{{ $diveLog->current }}';
        var isVisibilityAvailable   =   '{{ $diveLog->visibility }}';

        let buddy   =   {{ $count }};
        let convert =   {
            _1:'one',
            _2:'Two',
            _3:'Three',
            _4:'Four'
        };

        $( "#dive_center" ).autocomplete({
            source: diveCenterName,
        });

        function changeStatus(data) {
            if($('#collapse_button').hasClass('collapsed')){
                $('#collapse_button').removeClass('active');
                $('#collapse_all').prop('value',1);

            }else {
                $('#collapse_button').addClass('active');
                $('#collapse_all').prop('value',0);
            }
        }

        $('#remove-button').on(' click',function(){
            if($('.add-buddy').prev().hasClass('half-width-input')){
                if($('.add-buddy').hasClass('btn-danger')){
                    $('.add-buddy').removeClass('btn-danger');
                    $('.add-buddy').addClass('btn-primary');
                    $('.add-buddy').html('Add buddy');
                }
                $('.add-buddy').prev().remove();
                buddy--;
                if(buddy==1){
                    $('#verify_my_dive').prop('checked',false);
                    $('#verify_my_dive').prop('disabled',true);
                }
            }else{
                $(this).prop('disabled',true);

            }
        });

        $(document).ready(function(scubaya) {
            scubaya(".add-buddy").on('click', function () {
                if($('#verify_my_dive').prop('disabled',true)){
                    $('#verify_my_dive').prop('disabled',false);
                }
                if(buddy<5){
                    if($('#remove-button').prop('disabled')){
                        $('#remove-button').prop('disabled',false);
                    }
                    scubaya(this).before('<div class="box-with-shadow half-width-input form-group">' +
                        '<div class="form-group">' +
                        '<div class="form-group">Buddy ' + convert['_' + buddy] + '</div>' +
                        '<label for="buddy_name">Buddy Name</label>' +
                        '<div class="input-group date">' +
                        '<input type="text" id="buddy_name" class="form-control" required name="buddy_name[' + buddy + ']">' +
                        '</div>' +
                        '<div class="form-group"></div>' +
                        '<label for="from">SCBY user Id</label>' +
                        '<div class="input-group date">' +
                        '<input type="text" id="scby_user_id" class="form-control" required name="scby_user_id[' + buddy + ']">' +
                        '</div>' +
                        '</div>' +
                        '<div class="form-group">' +
                        '<label for="buddy_type">Buddy Type</label>' +
                        '<div class="half-width-input">' +
                        '<select class="selectpicker form-control" name="buddy_type[' + buddy + ']"  id="buddy_type">' +
                        '<option value="instructor">Instructor</option>' +
                        '<option value="guide">Guide</option>' +
                        '<option value="buddy">Buddy</option>' +
                        '</select>' +
                        '</div>' +
                        '</div>' +
                        '</div>');
                    buddy++;
                    scubaya('.selectpicker').selectpicker('refresh');
                    scubaya('.datepicker').datepicker();
                } else if(buddy>=5) {
                    $('#add-button').removeClass('btn-primary');
                    $('#add-button').addClass('btn-danger');
                    $('#add-button').html("Can't add more");
                }
            });

            $( "#dive_site" ).autocomplete({
                source: diveSite,
            });

            $('.add-new-dive-site').click(function(){
                var site    =   $('#new_dive_site').val();
                var lat     =   $('#latitude').val();
                var long    =   $('#longitude').val();

                if(site == '' || lat == '' || long == '') {
                    if(site == '' || site == undefined) {
                        $('#new-dive-site p[data-name="site-title-error"]').show();
                    }

                    if(lat == '' || lat == undefined) {
                        $('#new-dive-site p[data-name="latitude-error"]').show();
                    }

                    if(long == '' || long == undefined) {
                        $('#new-dive-site p[data-name="longitude-error"]').show();
                    }
                } else {
                    if($.inArray(site, diveSite) == -1) {
                        diveSite.push(site);
                        $("#new-dive-site").modal('toggle');

                        var token = '{{ csrf_token() }}';

                        $.ajax({
                            url: $(this).data('url'),
                            method: 'post',
                            data: { 'name': site, lat:lat, long:long, _token:token},
                            success: function (response) {
                                console.log('Added successfully!!');
                            },
                            error: function (error) {
                                console.log('Something went wrong!!');
                            }
                        });
                    } else {
                        $('#new-dive-site p[data-name="site-error"]').show();
                    }
                }

            });

            var curLocation = [markers.lat, markers.lng];

            var map = L.map('location_log_dive').setView(curLocation, 4);

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
                var locationDiveSite    =  '';

                fetch('https://nominatim.openstreetmap.org/reverse?format=json&lon=' + lng + '&lat=' + lat).then(function(response) {
                    return response.json();
                }).then(function(json) {
                    if(typeof json.display_name !== 'undefined') {
                        var popLocation= new L.LatLng(lat,lng);
                        locationDiveSite += json.display_name ;
                        $('#new_dive_site').val(locationDiveSite);
                        L.popup()
                            .setLatLng(popLocation)
                            .setContent('<a data-place="'+json.display_name+'">'+json.display_name+'</a>')
                            .openOn(map);
                    }
                });

                $('#latitude').val(marker.getLatLng().lat);
                $('#longitude').val(marker.getLatLng().lng);
            });

            // lets be fancy for the demo and select the current month.

            $("#circles_slider_for_waves")
            // activate the slider with options
                .slider({
                    min: 0,
                    max: scale.length - 1,
                    value:isWaveAvailable ? scale.indexOf(isWaveAvailable) : ''
                })
                // add pips with the labels set to "months"
                .slider("pips", {
                    rest: "label",
                    labels: scale
                });

            if(isWaveAvailable == '') {
                $('#circles_slider_for_waves').css('display', 'none');
            }

            $("#circles_slider_for_current")
            // activate the slider with options
                .slider({
                    min: 0,
                    max: scale.length - 1,
                    value: isCurrentAvailable ? scale.indexOf(isCurrentAvailable) : ''
                })
                // add pips with the labels set to "months"
                .slider("pips", {
                    rest: "label",
                    labels: scale
                });

            if(isCurrentAvailable == '') {
                $("#circles_slider_for_current").css('display', 'none');
            }

            $("#circles_slider_for_visibility").slider({
                min:0,
                max:50,
                value:isVisibilityAvailable
            }).slider("pips",{
                step:2,
                labels:{"first":"0","last":"50+"}
            }).slider("float",{
                suffix:"m"
            });

            if(isVisibilityAvailable == '') {
                $("#circles_slider_for_visibility").css('display', 'none');
            }

            $(".oxygen-slider").slider({
                min: 0,
                max: 100,
                value: {{ $diveLog->oxygen ? $diveLog->oxygen : 0 }},
            }).slider("float", {
                suffix: "%"
            });

            $('#waves_availability').click(function(e){
                if($(this).prop('checked')){
                    $('#circles_slider_for_waves').fadeOut();
                }
                else{
                    $('#circles_slider_for_waves').fadeIn();
                }
            });

            $('#current_availability').click(function(e){
                if($(this).prop('checked')){
                    $('#circles_slider_for_current').fadeOut();
                }
                else{
                    $('#circles_slider_for_current').fadeIn();
                }
            });
            $('#visibility_availability').click(function(e){
                if($(this).prop('checked')){
                    $('#circles_slider_for_visibility').fadeOut();
                }
                else{
                    $('#circles_slider_for_visibility').fadeIn();
                }
            });

            $('#exit_timepicker').on('mouseleave', function() {
                checktimedifference();
            });

            $('#enter_timepicker').on('mouseleave', function() {
                checktimedifference();
            });

            $('#used_pressure').on('focus',function(){
                var startpressure = $('#start_pressure').val();
                var endpressure   = $('#end_pressure').val();
                var diff =  startpressure - endpressure;
                if(diff>0) {
                    $('#used_pressure').attr('value', diff);
                }else{
                    $('#used_pressure').attr('value',0);
                }
            });
        });

        $('.datepicker').datepicker();

        $('#enter_water_time').timepicker();
        $('#exit_water_time').timepicker();

        CKEDITOR.replace('notes', {
            enterMode: CKEDITOR.ENTER_BR
        });

        var scale = ["None", "Small", "Medium", "Large"];
        var waves = "None", current="None", visibility="0",oxygen="0";

        var markers = {
            "lat": "{{ !empty($diveLog->latitude)  ? $diveLog->latitude  : $clientGeoInfo['lat'] }}",
            "lng": "{{ !empty($diveLog->longitude) ? $diveLog->longitude : $clientGeoInfo['lon'] }}"
        };

        function checktimedifference() {
            var start_time = $('#enter_water_time').val();
            var end_time = $('#exit_water_time').val();
            var diff = ((new Date("1970-1-1 " + end_time) - new Date("1970-1-1 " + start_time) ) / 1000 / 60 / 60) * (60);
            if (diff > 0) {
                $('#total_time').attr('value', diff);
            } else {
                $('#total_time').attr('value', 0);
            }
        }

        function settingAllValues(){
            if(!$('#waves_availability').prop('checked')) {
                waves = scale[$('#circles_slider_for_waves').slider("option", "value")];
                $('#waves').val(waves);
            }

            if(!$('#current_availability').prop('checked')) {
                current = scale[$('#circles_slider_for_current').slider("option", "value")];
                $('#current').val(current);
            }

            if(!$('#visibility_availability').prop('checked')) {
                visibility = $('#circles_slider_for_visibility').slider("option", "value");
                $('#visibility').val(visibility);
            }

            oxygen = $('.oxygen-slider').slider("option", "value");
            $('#oxygen').attr('value',oxygen);
        }

        function calculate_surface_interval(){
            $.ajax({
                url: "{{route('scubaya::user::dive_logs::surface_interval',[Auth::id()])}}",
                context: document.body,
                data: {'average_depth':$('#average_depth').val(),
                    'total_time':$('#total_time').val()
                }
            }).done(function(data) { console.log(data);
                $('#surface_interval_group').fadeIn();
                $('#surface_interval').prop('value',data.surface_interval);
            });
        }

    </script>
@endsection