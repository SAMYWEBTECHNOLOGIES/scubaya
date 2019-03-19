@extends('user.layouts.app')
@section('title','Log New Dive')
@section('contentheader')
    <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> User</a></li>
        <li><a href="#"><i class="fa fa-dashboard"></i> Dive Logs</a></li>
        <li class="active">Log New Dive</li>
    </ol>
@endsection
@section('content')
    <section class="content new-dive-logs">
        <div class="row margin-20">
            <div class="col-md-12">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title">Log New Dive</h3>
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
                    <form method="post" action="{{route('scubaya::user::dive_logs::create',[Auth::id()])}}">
                        {{csrf_field()}}
                        <div class="box-body">
                            <div class="row">
                                <div class="col-md-5">
                                    <div class="form-group">
                                        <label for="log_name">Log Name</label>
                                        <input type="text" class="form-control" id="log_name" name="log_name" value="{{ old('log_name') ? old('log_name'): @$userDiveLog->log_name }}">
                                    </div>
                                    <div class="form-group">
                                        <label for="dive_number">Dive Number</label>
                                        <input type="text" class="form-control" id="dive_number" name="dive_number" value="{{ old('dive_number') ? old('dive_number'): @$userDiveLog->dive_number }}">
                                    </div>
                                    <div class="form-group">
                                        <label for="date">Date</label>
                                        <div class="input-group">
                                            <span class="input-group-addon"><i class="glyphicon glyphicon-calendar"></i></span>
                                            <input type="text" class="form-control datepicker" id="date" data-date-format="yyyy/mm/dd" name="log_date" value="{{ old('log_date') ? old('log_date'): @$userDiveLog->log_date}}">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="training_dive" class="control-label" data-toggle="tooltip">Training
                                            Dive</label><br>
                                        <div class="btn-group" id="training_dive" data-toggle="buttons">
                                            <label class="btn btn-default btn-on btn-sm active">
                                                <input type="radio" value="1" name="training_dive" checked>YES</label>

                                            <label class="btn btn-default btn-off btn-sm">
                                                <input type="radio" value="0" name="training_dive">NO</label>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label for="dive_mode">Dive Mode</label>
                                        <select id="dive_mode" class="selectpicker form-control show-tick"
                                                name="dive_mode">
                                            <option value="oc recreational">OC Recreational</option>
                                            <option value="oc technical">OC Technical</option>
                                            <option value="cc/bo">CC/BO</option>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="dive_center">Dive Center:</label>
                                        <input type="text" class="form-control" id="dive_center" name="dive_center" value="{{ old('dive_center') ? old('dive_center'): @$userDiveLog->dive_center}}" >
                                    </div>
                                    <div class="form-group">
                                    <div class="box-with-shadow">
                                        <h4>Dive Buddy</h4>
                                        <div class="form-group">
                                            <input type="checkbox" value="1" id="verify_my_dive" name="verify_my_dive"> verify my dive
                                        </div>

                                        <div class="box-with-shadow half-width-input form-group">
                                            <div class="form-group">
                                                <div class="form-group">Buddy One</div>
                                                <label for="buddy_name">Buddy Name</label>
                                                <div class="input-group date">
                                                    <input type="text" class="form-control" required id="buddy_name"
                                                           name="buddy_name[1]" value="{{ old('buddy_name[1]') ? old('buddy_name[1]'): @$userDiveLog->buddy_name[1]}}"/>
                                                </div>
                                                <div class="form-group"></div>
                                                <label for="scby_user_id">SCBY user ID</label>

                                                <div class="input-group date">
                                                    <input type="text" required id="scby_user_id" class="form-control"
                                                           name="scby_user_id[1]" value="{{ old('scby_user_id[1]') ? old('scby_user_id[1]'): @$userDiveLog->scby_user_id[1]}}" />
                                                </div>
                                            </div>
                                            <div class="form-group">
                                            <div class="half-width-input">
                                                <label for="buddy_type">Buddy Type</label>
                                                <select class="selectpicker form-control" name="buddy_type[1]"
                                                        id="buddy_type">
                                                    <option value="instructor">Instructor</option>
                                                    <option value="guide">Guide</option>
                                                    <option value="buddy">Buddy</option>
                                                </select>
                                            </div>
                                            </div>
                                        </div>
                                        <button type="button" id="add-button"    class="btn btn-primary add-buddy">Add Buddy</button>
                                        <button type="button" id="remove-button" class="btn remove-buddy">Remove Buddy</button>
                                    </div>
                                    </div>
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
                                                                        <option>{{$submenu->name}}</option>
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
                                                  name="notes">{{old('template_content')}}</textarea>
                                    </div>
                                </div>
                                <div class="col-md-7">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group row">
                                                <label for="dive_site" class="col-sm-3 col-form-label"><i class="fa fa-map-marker blue map-marker"></i> Dive Site:</label>
                                                <div class="col-sm-6">
                                                    <input type="text" id="dive_site" name="dive_site" class="form-control" value="{{ old('dive_site') ? old('dive_site'): @$userDiveLog->dive_site}}"  >
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
                                                <div class="handle"><input type="hidden" id="collapse_all" name="collapse_all"></div>
                                            </button>
                                        </div>
                                        </div>
                                       <div id="diving_conditions" class="collapse in">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <h5>Day dive</h5>
                                                <div class="btn-group" data-toggle="buttons">
                                                    <button type="button" class="btn btn-default active">
                                                        <i class="wi wi-day-sunny" title="sunny day"></i>
                                                        <input type="radio" name="day_dive" value="sunny_day"
                                                               autocomplete="off" checked>
                                                    </button>
                                                    <button type="button" class="btn btn-default">
                                                        <i class="wi wi-day-cloudy" title="cloudy day"></i>
                                                        <input type="radio" name="day_dive" value="cloudy_day"
                                                               autocomplete="off">
                                                    </button>

                                                    <button type="button" class="btn btn-default">
                                                        <i class="wi wi-cloud" title="cloud day"></i>
                                                        <input type="radio" name="day_dive" value="cloud_day"
                                                               autocomplete="off">
                                                    </button>

                                                    <button type="button" class="btn btn-default">
                                                        <i class="wi wi-day-hail" title="hail day"></i>
                                                        <input type="radio" name="day_dive" value="hail_day"
                                                               autocomplete="off">
                                                    </button>
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <h5>Night dive</h5>
                                                <div class="btn-group" data-toggle="buttons">
                                                    <button type="button" class="btn btn-default active">
                                                        <i class="wi wi-night-clear" title="night clear"></i>
                                                        <input type="radio" name="night_dive" value="night_clear"
                                                               autocomplete="off" checked>
                                                    </button>

                                                    <button type="button" class="btn btn-default">
                                                        <i class="wi wi-night-alt-cloudy" title="night cloudy"></i>
                                                        <input type="radio" name="night_dive" value="night_alt_cloudy"
                                                               autocomplete="off">
                                                    </button>

                                                    <button type="button" class="btn btn-default">
                                                        <i class="wi wi-cloud" title="night cloud"></i>
                                                        <input type="radio" name="night_dive" value="night_cloud"
                                                               autocomplete="off">
                                                    </button>

                                                    <button type="button" class="btn btn-default">
                                                        <i class="wi wi-night-alt-rain" title="night rain"></i>
                                                        <input type="radio" name="night_dive" value="night_alt_rain"
                                                               autocomplete="off">
                                                    </button>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row dive-type">
                                            <div class="col-md-6"></div>
                                            <div class="btn-group" data-toggle="buttons">
                                                <button type="button" class="btn btn-default active">
                                                    <img src="{{asset('assets/images/dive-icons/wi-shore_dive.png')}}" />
                                                    <input type="radio" name="dive_type" value="boat_dive"
                                                           autocomplete="off" checked>
                                                    <span>Boat Dive &nbsp;</span>
                                                </button>
                                                <button type="button" class="btn btn-default">
                                                    <img src="{{asset('assets/images/dive-icons/wi-shore_dive.png')}}" />
                                                    <input type="radio" name="dive_type" value="shore_dive"
                                                           autocomplete="off" checked>
                                                    <span>Shore Dive</span>
                                                </button>

                                                <button type="button" class="btn btn-default">
                                                    <img src="{{asset('assets/images/dive-icons/wi-lake.png')}}" />
                                                    <input type="radio" name="dive_type" value="lake_dive"
                                                           autocomplete="off" checked>
                                                    <span>Lake Dive &nbsp;</span>
                                                </button>

                                                <button type="button" class="btn btn-default">
                                                    <img src="{{asset('assets/images/dive-icons/wi-sea-wave.png')}}" />
                                                    <input type="radio" name="dive_type" value="sea_dive"
                                                           autocomplete="off" checked>
                                                    <span>Sea Dive &nbsp;</span>
                                                </button>

                                                <button type="button" class="btn btn-default">
                                                    <img src="{{asset('assets/images/dive-icons/wi-river.png')}}" />
                                                    <input type="radio" name="dive_type" value="river_dive"
                                                           autocomplete="off" checked>
                                                    <span>River Dive</span>
                                                </button>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6 form-group">
                                                <label for="temperature">Air temperature</label>
                                                <div class="input-group">
                                                    <input type="text" class="form-control" id="temperature"
                                                           name="temperature" placeholder="Temperature">
                                                    <div class="input-group-addon">°C</div>
                                                </div>
                                            </div>
                                            <div class="col-md-6 form-group">
                                                <label for="altitude">Altitude:<b>( In Mtr )</b></label>
                                                <div class="input-group">
                                                    <input type="text" class="form-control" id="altitude"
                                                           name="altitude" placeholder="Altitude">
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
                                                        <input type="checkbox" value="1" id="waves_availability" name="waves_availability">NA
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
                                                        <input type="checkbox" value="1" id="current_availability" name="current_availability">NA
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
                                                        <input type="checkbox" value="1" id="visibility_availability"
                                                               name="visibility_availability">NA
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
                                                        <input type="text" class="form-control"
                                                               name="surface_temperature" id="surface_temperature"
                                                        >
                                                        <div class="input-group-addon">C</div>
                                                    </div>
                                                </div>
                                                <div class="row bottom-margin-top">
                                                    <label>Bottom:</label>
                                                    <div class="input-group">
                                                        <input type="text" name="bottom_temperature"
                                                               class="form-control" id="bottom_temperature">
                                                        <div class="input-group-addon">C</div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="wave-box well-lg " style="margin-top: 10px">
                                            <div class="row">
                                                <div class="col-md-4"><label>Enter water</label>
                                                    <div class="input-group bootstrap-timepicker timepicker"
                                                         id="enter_timepicker">

                                                        <input id="enter_water_time" name="enter_water_time"
                                                               type="text"
                                                               class="form-control input-small">
                                                        <span class="input-group-addon"><i
                                                                    class="glyphicon glyphicon-time"></i></span>
                                                    </div>
                                                </div>
                                                <div class="col-md-4"><label>Exit water</label>
                                                    <div class="input-group bootstrap-timepicker timepicker"
                                                         id="exit_timepicker">
                                                        <input id="exit_water_time" type="text"
                                                               name="exit_water_time"
                                                               class="form-control input-small">
                                                        <span class="input-group-addon"><i
                                                                    class="glyphicon glyphicon-time"></i></span>
                                                    </div>
                                                </div>
                                                <div class="col-md-1">
                                                    <div class="row" style="margin-top: 30px"><label>=</label></div>
                                                </div>
                                                <div class="col-md-3"><label>Total time</label>
                                                    <div class="input-group">
                                                        <input type="text" class="form-control" id="total_time"
                                                               name="total_time" maxlength="5">
                                                        <div class="input-group-addon">Min</div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row" style="margin-top: 10px">
                                                <div class="col-md-4">
                                                    <div class="dropdown">
                                                        <label>pressure</label>
                                                        <select class="form-control" name="enter_pressure">
                                                            @foreach($A_Z_listing_for_pressure as $a_z)
                                                                <option value={{$a_z}}>{{$a_z}}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="dropdown">
                                                        <label>pressure</label>
                                                        <select class="form-control" name="exit_pressure">
                                                            @foreach($A_Z_listing_for_pressure as $a_z)
                                                                <option value={{$a_z}}>{{$a_z}}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row margin-top-60">
                                                <div class="col-md-4"><label>Start pressure</label>
                                                    <div class="input-group">
                                                        <input type="text" class="form-control" id="start_pressure"
                                                               name="start_pressure"
                                                               placeholder="  :  " maxlength="5">
                                                        <div class="input-group-addon">Bar</div>
                                                    </div>
                                                </div>
                                                <div class="col-md-4"><label>End pressure</label>
                                                    <div class="input-group">
                                                        <input type="text" class="form-control" id="end_pressure"
                                                               name="end_pressure"
                                                               placeholder="  :  " maxlength="5">
                                                        <div class="input-group-addon">Bar</div>
                                                    </div>
                                                </div>
                                                <div class="col-md-1">
                                                    <div class="row" style="margin-top: 30px"><label>=</label></div>
                                                </div>
                                                <div class="col-md-3"><label>Used</label>
                                                    <div class="input-group">
                                                        <input type="text" class="form-control" id="used_pressure"
                                                               name="used_pressure" maxlength="5">
                                                        <div class="input-group-addon">Bar</div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row margin-top-60">
                                                <div class="col-md-3">
                                                    <label>Tank:</label>
                                                    <div class="input-group">
                                                        <input type="text" class="form-control" id="tank_total"
                                                               name="tank_total" maxlength="5">
                                                        <div class="input-group-addon">Ltr</div>
                                                    </div>
                                                </div>

                                                <div class="col-md-9">
                                                    <label>Tank Type:</label><br/>
                                                    <div class="btn-group" data-toggle="buttons">
                                                        <label class="btn btn-default active">
                                                            <input type="radio" name="tank_type" value="aluminium"
                                                                   autocomplete="off" checked>Aluminium
                                                        </label>
                                                        <label class="btn btn-default">
                                                            <input type="radio" name="tank_type" value="steel"
                                                                   autocomplete="off"> Steel
                                                        </label>
                                                        <label class="btn btn-default">
                                                            <input type="radio" name="tank_type" value="others"
                                                                   autocomplete="off"> Others
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
                                                        <input type="text" class="form-control" id="average_depth"
                                                               name="average_depth">
                                                        <div class="input-group-addon">Mtr</div>
                                                    </div>
                                                </div>
                                                <div class="col-md-4"><label>Maximum depth</label>
                                                    <div class="input-group">
                                                        <input type="text" class="form-control" id="maximum_depth"
                                                               name="maximum_depth" maxlength="5">
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
                                                                   name="surface_interval" maxlength="5">
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
                            <a href="{{ route('scubaya::user::dive_logs::index', [Auth::id()])}}"><button type="button" class="btn btn-default">Cancel</button></a>
                            <input type="submit" class="btn btn-info pull-right" id="submit" data-toggle="modal" onclick="settingAllValues()" value="Save">
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

        var diveCenterName = @php echo json_encode($diveCenterName) @endphp ;
        var diveSite       = @php echo json_encode($diveSiteName) @endphp;

        let buddy   =   2;
        let convert =   {
            _1:'one',
            _2:'Two',
            _3:'Three',
            _4:'Four'
        };

        $( "#dive_center" ).autocomplete({
            source: diveCenterName,
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
                            $('#dive_site').val(site);
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

            var markers = {
                "lat": "{{ !empty(old('latitude'))  ? old('latitude')  : $clientGeoInfo['lat'] }}",
                "lng": "{{ !empty(old('longitude')) ? old('longitude') : $clientGeoInfo['lon'] }}"
            };

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

            $('#notes').summernote({
                height: 300,
            });

            $('#surface_interval_group').fadeOut();

            // lets be fancy for the demo and select the current month.

            $("#circles_slider_for_waves")

            // activate the slider with options
                .slider({
                    min: 0,
                    max: scale.length - 1,
                })
                // add pips with the labels set to "months"
                .slider("pips", {
                    rest: "label",
                    labels: scale
                });
            $("#circles_slider_for_current")
            // activate the slider with options
                .slider({
                    min: 0,
                    max: scale.length - 1,
                })
                // add pips with the labels set to "months"
                .slider("pips", {
                    rest: "label",
                    labels: scale
                });
            $(".oxygen-slider").slider({
                min: 0,
                max: 100
            }).slider("float", {
                suffix: "%"
            });
            $("#circles_slider_for_visibility").slider({
                min:0,
                max:50
            }).slider("pips",{
                step:2,
                labels:{"first":"0","last":"50+"}
            }).slider("float",{
                suffix:"m"
            })
            $('#waves_availability').click(function(e){
                if($(this).prop('checked')){
                    $('#circles_slider_for_waves').fadeOut();
                    console.log($('#pressureenter_water').val());
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

        var scale = ["None", "Small", "Medium", "Large"];
        var waves = "None", current="None", visibility="0",oxygen="0";

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
            }).done(function(data) {
                $('#surface_interval_group').fadeIn();
            $('#surface_interval').prop('value',data.surface_interval);
            $('.click_description').fadeOut();
            });
        }

</script>
@endsection