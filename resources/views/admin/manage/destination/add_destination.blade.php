@extends('admin.layouts.app')
@section('title','Add Destination')
@section('breadcrumb')
    <li><a href="{{route('scubaya::admin::dashboard')}}">Manage</a></li>
    <li class="active"><a href="{{route('scubaya::admin::manage::destinations')}}">Destinations</a></li>
    <li class="active"><span>Add Destination</span></li>
@endsection
@section('content')
    <section class="container screen-fit">
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title">Add Destination</h3>
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

            <form role="form" method="post" id="destinationForm" action="{{route('scubaya::admin::manage::add_destination')}}" enctype="multipart/form-data">
                {{ csrf_field() }}
                <div class="box-body">
                    <div class="row">
                        <div class = "col-md-4">
                            <div class="form-group">
                                <label for="status" class="control-label" data-toggle="tooltip">Active</label><br>
                                <div class="btn-group" id="status" data-toggle="buttons">
                                    <label class="btn btn-default btn-on btn-sm active">
                                        <input type="radio" value="1" name="active" checked>YES</label>
                                    <label class="btn btn-default btn-off btn-sm">
                                        <input type="radio" value="0" name="active">NO</label>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="status" class="control-label" data-toggle="tooltip">Is Sub destination</label><br>
                                <div class="btn-group" id="status" data-toggle="buttons">
                                    <label class="btn btn-default btn-on btn-sm" id = "is_subdestination_active">
                                        <input type="radio" value="1" name="is_sub_destination" >YES</label>

                                    <label class="btn btn-default btn-off btn-sm active" id = "is_subdestination_deactive">
                                        <input type="radio" value="0" name="is_sub_destination" checked>NO</label>
                                </div>
                            </div>
                            <div class="form-group" style="padding-top: 9px;">
                                <label for="destination_name" data-toggle="tooltip">Destination name</label>
                                <input type="text" class="form-control" id="destination_name" name="destination_name">
                            </div>
                            <div class="form-group">
                                <label for="destination_sub_name" data-toggle="tooltip">Destination sub name</label>
                                <input type="text" class="form-control" id="destination_sub_name" name="destination_sub_name">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group" id = "is-subdestination-of" style = "display:none">
                                <label for="is_sub_destination">Is Sub destination of</label>
                                <select  class="selectpicker form-control show-tick" name="is_subdestination_of" title="Select Main Destination">
                                    @foreach($destination_main as $key => $maindestination)
                                        <option  title="{{$maindestination->name}}" value="{{$maindestination->id}}">{{$maindestination->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group language_spoken">
                                <label for="language_spoken"  data-toggle="tooltip">Language spoken</label>
                                <select id="language_spoken" multiple class="selectpicker form-control show-tick" name="language_spoken[]" title="Select Language">
                                   @foreach($languagesSpoken as $language)
                                        <option value="{{$language->name}}">{{$language->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="country">Country</label>
                                <input type="text" class="form-control" name="country" id="country">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group thumbnail_main_image">
                                <label for="main_image" data-toggle="tooltip" title="Upload main image"><i class="fa fa-upload" aria-hidden="true"></i>   Upload main image</label>
                                <input type="file" class="form-control" id="main_image" name="image" onchange="readURL(this)">
                            </div>

                            <div class="form-group thumbnail_main_image">
                                <label for="images" data-toggle="tooltip" title="Upload Max 6 images"><i class="fa fa-upload" aria-hidden="true"></i>   Upload max 6 images</label>
                                <input type="file" class="form-control" id="max_images" name="images[]" multiple onchange="readURL(this)">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12 margin-bottom-10">
                            <div id="destination-location-map" style="width: 100%; height: 300px"></div>
                        </div>
                        <div class="col-md-4">
                            <label>Location</label>
                            <input name="location" class="form-control" id="destination-location">
                        </div>

                        <div class="col-md-4">
                            <label>Latitude</label>
                            <input name="latitude" class="form-control" id="destination-latitude">
                        </div>

                        <div class="col-md-4">
                            <label>Longitude</label>
                            <input name="longitude" class="form-control" id="destination-longitude">
                        </div>
                    </div>

                    <div class ="row">
                        <div class="col-md-12">
                            <h4 class="blue">Edit Info</h4>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="country_currency">Country Currency</label>
                                <select id="country_currency" class="selectpicker form-control show-tick" name="country_currency" title="Select Currency">
                                    @foreach($currency_all as $currency)
                                        <option  title="{{$currency->currency_name}} - {{$currency->symbol}}" value="{{$currency->symbol}}">{{$currency->currency_name}} - {{$currency->symbol}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="country_currency">Accepted Currency</label>
                                <select id="accepted_country_currency" data-size="5" data-selected-text-format="count > 4" data-live-search="true" multiple class="selectpicker form-control show-tick" name="accepted_country_currency[]" title="Select Currency">
                                    @foreach($currency_all as $currency)
                                        <option  title="{{$currency->currency_code}}" data-tokens="{{$currency->currency_code}}" value="{{$currency->currency_code}}">{{$currency->currency_name}}({{$currency->currency_code}}) </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="geographic_area">Geographical Area</label>
                                <select id="geographic_area" class="selectpicker form-control show-tick" name="geographical_area" title="Select Geographical Area" data-size="5">
                                    <option value="Mediterranean Sea">Mediterranean Sea</option>
                                    <option value="Atlantic Ocean">Atlantic Ocean</option>
                                    <option value="Red Sea & Arabia"> Red Sea & Arabia</option>
                                    <option value="Asia">Asia</option>
                                    <option value="Caribbean">Caribbean</option>
                                    <option value="Indian Ocean & East Africa">Indian Ocean & East Africa</option>
                                    <option value="American Pacific">American Pacific</option>
                                    <option value="Oceanian Pacific">Oceanian Pacific</option>
                                    <option value="Cold Waters">Cold Waters</option>
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="region">Region</label>
                                <select id="region" class="selectpicker form-control show-tick" name="region" title="Select Region" data-size="5">
                                    <option value="Asia">Asia</option>
                                    <option value="Commonwealth countries">Commonwealth countries</option>
                                    <option value="Intercontinental areas (Western Hemisphere)">Intercontinental areas (Western Hemisphere)</option>
                                    <option value="Developing countries"> Developing countries</option>
                                    <option value="Europe">Europe</option>
                                    <option value="Africa">Africa</option>
                                    <option value="French Community">French Community</option>
                                    <option value="Indian Ocean">Indian Ocean</option>
                                    <option value="Atlantic Ocean">Atlantic Ocean</option>
                                    <option value="Intercontinental areas (Eastern Hemisphere)">Intercontinental areas (Eastern Hemisphere)</option>
                                    <option value="North America">North America</option>
                                    <option value="Pacific Ocean">Pacific Ocean</option>
                                    <option value="Cold regions">Cold regions</option>
                                    <option value="Arctic Ocean; Arctic regions">Arctic Ocean; Arctic regions</option>
                                    <option value="South America">South America</option>
                                    <option value="Antarctic Ocean; Antarctica">Antarctic Ocean; Antarctica</option>
                                    <option value="Australasia">Australasia</option>
                                    <option value="Tropics">Tropics</option>
                                    <option value="Earth">Earth</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="capital_wikipedia">Capital</label>
                                <input type="text" class="form-control" name="capital_wikipedia" id="capital_wikipedia">
                            </div>
                            <div class="form-group">
                                <label>Time Zone</label>
                                <select class="selectpicker form-control show-tick" data-size="5" name="time_zone" id="time_zone" title="Time Zone">
                                    <option value="-12">(GMT-12:00) International Date Line West</option>
                                    <option value="-11">(GMT-11:00) Midway Island, Samoa</option>
                                    <option value="-10">(GMT-10:00) Hawaii</option>
                                    <option value="-9">(GMT-09:00) Alaska</option>
                                    <option value="-8">(GMT-08:00) Pacific Time (US & Canada)</option>
                                    <option value="-8">(GMT-08:00) Tijuana, Baja California</option>
                                    <option value="-7">(GMT-07:00) Arizona</option>
                                    <option value="-7">(GMT-07:00) Chihuahua, La Paz, Mazatlan</option>
                                    <option value="-7">(GMT-07:00) Mountain Time (US & Canada)</option>
                                    <option  value="-6">(GMT-06:00) Central America</option>
                                    <option  value="-6">(GMT-06:00) Central Time (US & Canada)</option>
                                    <option  value="-6">(GMT-06:00) Guadalajara, Mexico City, Monterrey</option>
                                    <option  value="-6">(GMT-06:00) Saskatchewan</option>
                                    <option  value="-5">(GMT-05:00) Bogota, Lima, Quito, Rio Branco</option>
                                    <option  value="-5">(GMT-05:00) Eastern Time (US & Canada)</option>
                                    <option  value="-5">(GMT-05:00) Indiana (East)</option>
                                    <option  value="-4">(GMT-04:00) Atlantic Time (Canada)</option>
                                    <option  value="-4">(GMT-04:00) Caracas, La Paz</option>
                                    <option  value="-4">(GMT-04:00) Manaus</option>
                                    <option  value="-4">(GMT-04:00) Santiago</option>
                                    <option  value="-3.5">(GMT-03:30) Newfoundland</option>
                                    <option  value="-3">(GMT-03:00) Brasilia</option>
                                    <option  value="-3">(GMT-03:00) Buenos Aires, Georgetown</option>
                                    <option  value="-3">(GMT-03:00) Greenland</option>
                                    <option  value="-3">(GMT-03:00) Montevideo</option>
                                    <option  value="-2">(GMT-02:00) Mid-Atlantic</option>
                                    <option  value="-1">(GMT-01:00) Cape Verde Is.</option>
                                    <option  value="-1">(GMT-01:00) Azores</option>
                                    <option  value="0">(GMT+00:00) Casablanca, Monrovia, Reykjavik</option>
                                    <option  value="0">(GMT+00:00) Greenwich Mean Time : Dublin, Edinburgh, Lisbon, London</option>
                                    <option  value="1">(GMT+01:00) Amsterdam, Berlin, Bern, Rome, Stockholm, Vienna</option>
                                    <option  value="1">(GMT+01:00) Belgrade, Bratislava, Budapest, Ljubljana, Prague</option>
                                    <option  value="1">(GMT+01:00) Brussels, Copenhagen, Madrid, Paris</option>
                                    <option  value="1">(GMT+01:00) Sarajevo, Skopje, Warsaw, Zagreb</option>
                                    <option  value="1">(GMT+01:00) West Central Africa</option>
                                    <option  value="2">(GMT+02:00) Amman</option>
                                    <option  value="2">(GMT+02:00) Athens, Bucharest, Istanbul</option>
                                    <option  value="2">(GMT+02:00) Beirut</option>
                                    <option  value="2">(GMT+02:00) Cairo</option>
                                    <option  value="2">(GMT+02:00) Harare, Pretoria</option>
                                    <option  value="2">(GMT+02:00) Helsinki, Kyiv, Riga, Sofia, Tallinn, Vilnius</option>
                                    <option  value="2">(GMT+02:00) Jerusalem</option>
                                    <option  value="2">(GMT+02:00) Minsk</option>
                                    <option  value="2">(GMT+02:00) Windhoek</option>
                                    <option  value="3">(GMT+03:00) Kuwait, Riyadh, Baghdad</option>
                                    <option  value="3">(GMT+03:00) Moscow, St. Petersburg, Volgograd</option>
                                    <option  value="3">(GMT+03:00) Nairobi</option>
                                    <option  value="3">(GMT+03:00) Tbilisi</option>
                                    <option  value="3.5">(GMT+03:30) Tehran</option>
                                    <option  value="4">(GMT+04:00) Abu Dhabi, Muscat</option>
                                    <option  value="4">(GMT+04:00) Baku</option>
                                    <option  value="4">(GMT+04:00) Yerevan</option>
                                    <option  value="4.5">(GMT+04:30) Kabul</option>
                                    <option  value="5">(GMT+05:00) Yekaterinburg</option>
                                    <option  value="5">(GMT+05:00) Islamabad, Karachi, Tashkent</option>
                                    <option  value="5.5">(GMT+05:30) Sri Jayawardenapura</option>
                                    <option  value="5.5">(GMT+05:30) Chennai, Kolkata, Mumbai, New Delhi</option>
                                    <option  value="5.75">(GMT+05:45) Kathmandu</option>
                                    <option  value="6">(GMT+06:00) Almaty, Novosibirsk</option>
                                    <option  value="6">(GMT+06:00) Astana, Dhaka</option>
                                    <option  value="6.5">(GMT+06:30) Yangon (Rangoon)</option>
                                    <option  value="7">(GMT+07:00) Bangkok, Hanoi, Jakarta</option>
                                    <option  value="7">(GMT+07:00) Krasnoyarsk</option>
                                    <option  value="8">(GMT+08:00) Beijing, Chongqing, Hong Kong, Urumqi</option>
                                    <option  value="8">(GMT+08:00) Kuala Lumpur, Singapore</option>
                                    <option  value="8">(GMT+08:00) Irkutsk, Ulaan Bataar</option>
                                    <option  value="8">(GMT+08:00) Perth</option>
                                    <option  value="8">(GMT+08:00) Taipei</option>
                                    <option  value="9">(GMT+09:00) Osaka, Sapporo, Tokyo</option>
                                    <option  value="9">(GMT+09:00) Seoul</option>
                                    <option  value="9">(GMT+09:00) Yakutsk</option>
                                    <option  value="9.5">(GMT+09:30) Adelaide</option>
                                    <option  value="9.5">(GMT+09:30) Darwin</option>
                                    <option  value="10">(GMT+10:00) Brisbane</option>
                                    <option  value="10">(GMT+10:00) Canberra, Melbourne, Sydney</option>
                                    <option  value="10">(GMT+10:00) Hobart</option>
                                    <option  value="10">(GMT+10:00) Guam, Port Moresby</option>
                                    <option  value="10">(GMT+10:00) Vladivostok</option>
                                    <option  value="11">(GMT+11:00) Magadan, Solomon Is., New Caledonia</option>
                                    <option  value="12">(GMT+12:00) Auckland, Wellington</option>
                                    <option  value="12">(GMT+12:00) Fiji, Kamchatka, Marshall Is.</option>
                                    <option  value="13">(GMT+13:00) Nuku'alofa</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="tipping">Tipping incl</label>
                                <select id="tipping" class="selectpicker form-control show-tick" name="tipping" title="Select Tipping">
                                    <optgroup label="Recommended">
                                        <option value = "Recommended(round up bill)">Recommended (round up bill)</option>
                                        <option value = "Recommended(5-10%)">Recommended (5-10%)</option>
                                        <option value = "Recommended(10%)">Recommended(10%)</option>
                                        <option value = "Recommended(15%)">Recommended(15%)</option>
                                        <option value = "Recommended in restaurants(10%),except if included">Recommended in restaurants (10%),except if included</option>
                                        <option value = "Recommended in restaurants(10-15%), except if included">Recommended in restaurants (10-15%), except if included</option>
                                        <option value = "Recommended in restaurants(15%), except if included">Recommended in restaurants (15%), except if included</option>
                                        <option value = "Recommended if not included(10%)">Recommended if not included (10%)</option>
                                        <option value = "Recommended if not included(15%)">Recommended if not included (15%)</option>
                                        <option value = "Recommended if not included(10-15%)">Recommended if not included (10-15%)</option>
                                        <option value = "Recommended(10-15%),especially in restaurants">Recommended (10-15%),especially in restaurants</option>
                                    </optgroup>
                                    <optgroup label="Appreciated">
                                        <option value = "Appreciated(5%)">Appreciated (5%)</option>
                                        <option value = "Appreciated(10%)">Appreciated (10%)</option>
                                        <option value = "Appreciated(5-10%)">Appreciated (5-10%)</option>
                                        <option value = "Appreciated(round up bill)">Appreciated (round up bill)</option>
                                    </optgroup>
                                    <optgroup label="Rare">
                                        <option value = "Rare(recommended for hotel staff)">Rare (recommended for hotel staff)</option>
                                    </optgroup>
                                    <optgroup label="Obligatory">
                                        <option value = "Obligatory(except if included)">Obligatory (except if included)</option>
                                        <option value = "Obligatory(10-15%),except if included">Obligatory (10-15%), except if included</option>
                                        <option value = "Obligatory in restaurants(10-15%),except if included">Obligatory in restaurants (10-15%), except if included</option>
                                        <option value = "Obligatory(15-20%)">Obligatory (15-20%)</option>
                                    </optgroup>
                                    <option value = "never">Never</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="electric_voltage">Electric Voltage</label>
                                <input type="text" class="form-control" name="electric_voltage" id="electric_voltage">
                            </div>
                            <div class="form-group">
                                <label for="religion">Religion</label>
                                <input type="text" class="form-control" name="religion" id="religion">
                            </div>
                            <div class="form-group">
                                <label for="population">Population</label>
                                <input type="text" class="form-control form-group" name="population" id="population">
                            </div>
                            <div class="form-group">
                                <label for="phone code">Phone code</label>
                                <input type="text" class="form-control form-group" name="phone_code" id="phone_code">
                            </div>
                            <div class="form-group">
                                <label for="hdi rank"><b>HDI rank</b></label>
                                <input type="text" class="form-control form-group" name="hdi_rank" id="hdi_rank">
                            </div>
                            <div class="form-group">
                                <label for="formality eg.visum requried"><b>formality eg.visum requried</b></label>
                                <div class = "row" id = "show_allcountry"></div>
                                <!-- The Modal -->
                                <div id="countriesFlags" class="modal fade bd-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
                                    <div class="modal-dialog modal-sm">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h4>Countries</h4>
                                            </div>
                                            <div class="modal-body">
                                                <div class="country-select" id ="accepted_country">
                                                    <ul class="country-list"></ul>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div  id = "selected_country"></div>
                                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#countriesFlags">Add Country</button>

                                {{--<input type = "button" id="add-country-button" class="btn btn-primary" value = "Add country" ></input>--}}
                            </div>
                        </div>
                        <div class = "col-md-4">
                            <div class="form-group">
                                <label for="active" class="control-label" data-toggle="tooltip">Show Water Temperature</label><br>
                                <div class="btn-group" id="status" data-toggle="buttons">
                                    <label class="btn btn-default btn-on btn-sm active">
                                        <input type="radio" value="1" name="water_temp" checked>YES</label>

                                    <label class="btn btn-default btn-off btn-sm @if(old('course_repeat') === '0') active @endif">
                                        <input type="radio" value="0" name="water_temp" @if(old('course_repeat') === '0') checked @endif>NO</label>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="active" class="control-label" data-toggle="tooltip">Show Weather</label><br>
                                <div class="btn-group" id="status" data-toggle="buttons">
                                    <label class="btn btn-default btn-on btn-sm active">
                                        <input type="radio" value="1" name="weather" checked>YES</label>

                                    <label class="btn btn-default btn-off btn-sm @if(old('course_repeat') === '0') active @endif">
                                        <input type="radio" value="0" name="weather" @if(old('course_repeat') === '0') checked @endif>NO</label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <h4 class="blue">About Destination</h4>
                        </div>
                        <div class = "col-md-6">
                            <div class="from-group">
                                <label for="add_short_description">Add Short Description</label>
                                <textarea  rows="4" class="form-control form-group" id="add_short_description" placeholder="Short Description" name="add_short_description">{{old('add_short_description')}}</textarea>
                            </div>
                            <div class="from-group">
                                <label for="add_long_description">Add Long Description</label>
                                <textarea  rows="4" class="form-control form-group" id="add_long_description" placeholder="Long Description" name="add_long_description" >{{old('add_short_description')}}</textarea>
                            </div>
                                <h4 class="destination_season">Exposure period to cyclone and (rain) stroms</h4>
                            <div class="box-with-shadow">
                                <h4>Exposure</h4>
                                <div class = "row">
                                    <div class ="col-md-6">
                                        <div class="form-group">
                                            <input type="checkbox" value="1" id="whole_year_exposure" name="whole_year_exposure"> The Whole Year
                                        </div>
                                    </div>
                                    <div class ="col-md-6">
                                        <div class="form-group">
                                            <input type="checkbox" value="1" id="no_exposure" name="no_exposure"> No exposure
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group exposure_box ">
                                    <div class="box-with-shadow form-group">
                                        <div class="row">
                                            <div class="col-md-1">
                                                <label for="from">From</label>
                                            </div>
                                            <div class="col-md-6">
                                                <select class="selectpicker form-control show-tick" name="exposure_from">
                                                    <option value = "01">Jan</option>
                                                    <option value = "02">Feb</option>
                                                    <option value = "03">March</option>
                                                    <option value = "04">Apr</option>
                                                    <option value = "05">May</option>
                                                    <option value = "06">June</option>
                                                    <option value = "07">July</option>
                                                    <option value = "08">Aug</option>
                                                    <option value = "09">Sept</option>
                                                    <option value = "10">Oct</option>
                                                    <option value = "11">Nov</option>
                                                    <option value = "12">Dec</option>
                                                </select>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <input type="checkbox" value="1" id="half_from_exposure" name="exposure_from_state"> Half
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="box-with-shadow form-group">
                                        <div class="row">
                                            <div class="col-md-1">
                                                <label for="from">Till</label>
                                            </div>
                                            <div class="col-md-6">
                                                <select class="selectpicker form-control show-tick" name="exposure_till">
                                                    <option value = "01">Jan</option>
                                                    <option value = "02">Feb</option>
                                                    <option value = "03">March</option>
                                                    <option value = "04">Apr</option>
                                                    <option value = "05">May</option>
                                                    <option value = "06">June</option>
                                                    <option value = "07">July</option>
                                                    <option value = "08">Aug</option>
                                                    <option value = "09">Sept</option>
                                                    <option value = "10">Oct</option>
                                                    <option value = "11">Nov</option>
                                                    <option value = "12">Dec</option>
                                                </select>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <input type="checkbox" value="1" id="half_till_exposure" name="exposure_till_state"> Half
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <h4 class = "destination_season">Rain season</h4>
                            <div class="box-with-shadow">
                                <h4>Rain</h4>
                                <div class = "row">
                                    <div class ="col-md-6">
                                        <div class="form-group">
                                            <input type="checkbox" value="1" id="whole_year_rain" name="whole_year_rain"> The Whole Year
                                        </div>
                                    </div>
                                    <div class ="col-md-6">
                                        <div class="form-group">
                                            <input type="checkbox" value="1" id="no_rain" name="no_rain"> No exposure
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group rain_box ">
                                    <div class="box-with-shadow form-group">
                                        <div class="row">
                                            <div class="col-md-1">
                                                <label for="from">From</label>
                                            </div>
                                            <div class="col-md-6">
                                                <select class="selectpicker form-control show-tick" name="rain_from">
                                                    <option value = "01">Jan</option>
                                                    <option value = "02">Feb</option>
                                                    <option value = "03">March</option>
                                                    <option value = "04">Apr</option>
                                                    <option value = "05">May</option>
                                                    <option value = "06">June</option>
                                                    <option value = "07">July</option>
                                                    <option value = "08">Aug</option>
                                                    <option value = "09">Sept</option>
                                                    <option value = "10">Oct</option>
                                                    <option value = "11">Nov</option>
                                                    <option value = "12">Dec</option>
                                                </select>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <input type="checkbox" value="1" id="half_from_rain" name="rain_from_state"> Half
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="box-with-shadow form-group">
                                        <div class="row">
                                            <div class="col-md-1">
                                                <label for="from">Till</label>
                                            </div>
                                            <div class="col-md-6">
                                                <select class="selectpicker form-control show-tick" name="rain_till">
                                                    <option value = "01">Jan</option>
                                                    <option value = "02">Feb</option>
                                                    <option value = "03">March</option>
                                                    <option value = "04">Apr</option>
                                                    <option value = "05">May</option>
                                                    <option value = "06">June</option>
                                                    <option value = "07">July</option>
                                                    <option value = "08">Aug</option>
                                                    <option value = "09">Sept</option>
                                                    <option value = "10">Oct</option>
                                                    <option value = "11">Nov</option>
                                                    <option value = "12">Dec</option>
                                                </select>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <input type="checkbox" value="1" id="half_till_rain" name="rain_till_state"> Half
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="from-group">
                                <label for="add_dive_description">Add Dive Description</label>
                                <textarea  rows="4" class="form-control form-group" id="add_dive_description" placeholder="Dive Description" name="add_dive_description">{{old('add_dive_description')}}</textarea>
                            </div>
                            <div class="from-group">
                                <label for="add_tourist_description">Add Tourist Description</label>
                                <textarea  rows="4" class="form-control form-group" id="add_tourist_description" placeholder="Tourist Description" name="add_tourist_description">{{old('add_tourist_description')}}</textarea>
                            </div>
                                <h4 class="destination_season">Dive season</h4>
                            <div class="box-with-shadow">
                                <h4>Dive</h4>
                                <div class = "row">
                                    <div class ="col-md-6">
                                        <div class="form-group">
                                            <input type="checkbox" value="1" id="whole_year" name="whole_year"> The Whole Year
                                        </div>
                                    </div>
                                    <div class ="col-md-6">
                                        <div class="form-group">
                                            <input type="checkbox" value="1" id="no_dive_season" name="no_dive_season"> No exposure
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group season_box ">
                                    <div class="box-with-shadow form-group">
                                        <div class="row">
                                            <div class="col-md-1">
                                                <label for="from">From</label>
                                            </div>
                                            <div class="col-md-6">
                                                <select class="selectpicker form-control show-tick" name="season_from">
                                                    <option value = "01">Jan</option>
                                                    <option value = "02">Feb</option>
                                                    <option value = "03">March</option>
                                                    <option value = "04">Apr</option>
                                                    <option value = "05">May</option>
                                                    <option value = "06">June</option>
                                                    <option value = "07">July</option>
                                                    <option value = "08">Aug</option>
                                                    <option value = "09">Sept</option>
                                                    <option value = "10">Oct</option>
                                                    <option value = "11">Nov</option>
                                                    <option value = "12">Dec</option>
                                                </select>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <input type="checkbox" value="1" id="half_from_season" name="season_from_state"> Half
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="box-with-shadow form-group">
                                        <div class="row">
                                            <div class="col-md-1">
                                                <label for="from">Till</label>
                                            </div>
                                            <div class="col-md-6">
                                                <select class="selectpicker form-control show-tick" name="season_till">
                                                    <option value = "01">Jan</option>
                                                    <option value = "02">Feb</option>
                                                    <option value = "03">March</option>
                                                    <option value = "04">Apr</option>
                                                    <option value = "05">May</option>
                                                    <option value = "06">June</option>
                                                    <option value = "07">July</option>
                                                    <option value = "08">Aug</option>
                                                    <option value = "09">Sept</option>
                                                    <option value = "10">Oct</option>
                                                    <option value = "11">Nov</option>
                                                    <option value = "12">Dec</option>
                                                </select>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <input type="checkbox" value="1" id="half_till_season" name="season_till_state"> Half
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <h3 class="destination_season">Dive conditions</h3>
                            <div class="form-group enviroment_label">
                                <label for="reef_sea_floor">Reef/ Sea Floor</label>
                                <div class="half-width-input">
                                    <select class="selectpicker form-control show-tick" id="reef_sea_floor" name="reef_sea_floor" title ="Select Reef/Sea Floor">
                                        <option value="none">None</option>
                                        <option value="1">1 Star</option>
                                        <option value="2">2 Stars</option>
                                        <option value="3">3 Stars</option>
                                        <option value="4">4 Stars</option>
                                    </select>
                                </div>
                                <label for="macro">Macro</label>
                                <div class="half-width-input">
                                    <select class="selectpicker form-control show-tick" id="macro" name="macro" title = "Select Macro">
                                        <option value="none">None</option>
                                        <option value="1">1 Star</option>
                                        <option value="2">2 Stars</option>
                                        <option value="3">3 Stars</option>
                                        <option value="4">4 Stars</option>
                                    </select>
                                </div>

                                <label for="pelagic">Pelagic</label>
                                <div class="half-width-input">
                                    <select class="selectpicker form-control show-tick" id="pelagic" name="pelagic" title = "Select Pelagic">
                                        <option value="none">None</option>
                                        <option value="1">1 Star</option>
                                        <option value="2">2 Stars</option>
                                        <option value="3">3 Stars</option>
                                        <option value="4">4 Stars</option>
                                    </select>
                                </div>

                                <label for="wreck">Wreck</label>
                                <div class="half-width-input">
                                    <select class="selectpicker form-control show-tick" id="wreck" name="wreck" title = "Select Wreck">
                                        <option value="none">None</option>
                                        <option value="1">1 Star</option>
                                        <option value="2">2 Stars</option>
                                        <option value="3">3 Stars</option>
                                        <option value="4">4 Stars</option>
                                    </select>
                                </div>
                                <label for="wreck">Climate</label>
                                <div class="half-width-input">
                                    <select class="selectpicker form-control show-tick" id="climate" name="climate" title = "Select Climate">
                                        <option value="Tropical">Tropical</option>
                                        <option value="Dessert">Dessert</option>
                                        <option value="Rain">Rain</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class = "col-md-12">
                            <div class="table-responsive">
                                <table class="table table-striped">
                                    <thead>
                                    <tr>
                                        <th class="text-center" scope="col"></th>
                                        <th class="text-center" scope="col">Jan</th>
                                        <th class="text-center" scope="col">Feb</th>
                                        <th class="text-center" scope="col">March</th>
                                        <th class="text-center" scope="col">Apr</th>
                                        <th class="text-center" scope="col">May</th>
                                        <th class="text-center" scope="col">June</th>
                                        <th class="text-center" scope="col">July</th>
                                        <th class="text-center" scope="col">Aug</th>
                                        <th class="text-center" scope="col">Sept</th>
                                        <th class="text-center" scope="col">Oct</th>
                                        <th class="text-center" scope="col">Nov</th>
                                        <th class="text-center" scope="col">Dec</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <tr>
                                        <th scope="row">Water temp</th>
                                        <td ><input type="text" class="form-control" name="watertemp[jan]" ></td>
                                        <td ><input type="text" class="form-control" name="watertemp[feb]" ></td>
                                        <td ><input type="text" class="form-control" name="watertemp[mar]" ></td>
                                        <td ><input type="text" class="form-control" name="watertemp[apr]" ></td>
                                        <td ><input type="text" class="form-control" name="watertemp[may]" ></td>
                                        <td ><input type="text" class="form-control" name="watertemp[june]" ></td>
                                        <td ><input type="text" class="form-control" name="watertemp[july]" ></td>
                                        <td ><input type="text" class="form-control" name="watertemp[aug]" ></td>
                                        <td ><input type="text" class="form-control" name="watertemp[sept]" ></td>
                                        <td ><input type="text" class="form-control" name="watertemp[oct]" ></td>
                                        <td ><input type="text" class="form-control" name="watertemp[nov]" ></td>
                                        <td ><input type="text" class="form-control" name="watertemp[dec]" ></td>
                                    </tr>
                                    <tr>
                                        <th scope="row">Min air temp</th>
                                        <td ><input type="text" class="form-control" name="minairtemp[jan]" ></td>
                                        <td ><input type="text" class="form-control" name="minairtemp[feb]" ></td>
                                        <td ><input type="text" class="form-control" name="minairtemp[mar]" ></td>
                                        <td ><input type="text" class="form-control" name="minairtemp[apr]" ></td>
                                        <td ><input type="text" class="form-control" name="minairtemp[may]" ></td>
                                        <td ><input type="text" class="form-control" name="minairtemp[june]" ></td>
                                        <td ><input type="text" class="form-control" name="minairtemp[july]" ></td>
                                        <td ><input type="text" class="form-control" name="minairtemp[aug]" ></td>
                                        <td ><input type="text" class="form-control" name="minairtemp[sept]" ></td>
                                        <td ><input type="text" class="form-control" name="minairtemp[oct]" ></td>
                                        <td ><input type="text" class="form-control" name="minairtemp[nov]" ></td>
                                        <td ><input type="text" class="form-control" name="minairtemp[dec]" ></td>
                                    </tr>
                                    <tr>
                                        <th scope="row">Max air temp</th>
                                        <td ><input type="text" class="form-control" name="maxairtemp[jan]" ></td>
                                        <td ><input type="text" class="form-control" name="maxairtemp[feb]" ></td>
                                        <td ><input type="text" class="form-control" name="maxairtemp[mar]" ></td>
                                        <td ><input type="text" class="form-control" name="maxairtemp[apr]" ></td>
                                        <td ><input type="text" class="form-control" name="maxairtemp[may]" ></td>
                                        <td ><input type="text" class="form-control" name="maxairtemp[june]" ></td>
                                        <td ><input type="text" class="form-control" name="maxairtemp[july]" ></td>
                                        <td ><input type="text" class="form-control" name="maxairtemp[aug]" ></td>
                                        <td ><input type="text" class="form-control" name="maxairtemp[sept]" ></td>
                                        <td ><input type="text" class="form-control" name="maxairtemp[oct]" ></td>
                                        <td ><input type="text" class="form-control" name="maxairtemp[nov]" ></td>
                                        <td ><input type="text" class="form-control" name="maxairtemp[dec]" ></td>
                                    </tr>
                                    <tr>
                                        <th scope="row">Rainfall</th>
                                        <td ><input type="text" class="form-control" name="rainfalltemp[jan]" ></td>
                                        <td ><input type="text" class="form-control" name="rainfalltemp[feb]" ></td>
                                        <td ><input type="text" class="form-control" name="rainfalltemp[mar]" ></td>
                                        <td ><input type="text" class="form-control" name="rainfalltemp[apr]" ></td>
                                        <td ><input type="text" class="form-control" name="rainfalltemp[may]" ></td>
                                        <td ><input type="text" class="form-control" name="rainfalltemp[june]" ></td>
                                        <td ><input type="text" class="form-control" name="rainfalltemp[july]" ></td>
                                        <td ><input type="text" class="form-control" name="rainfalltemp[aug]" ></td>
                                        <td ><input type="text" class="form-control" name="rainfalltemp[sept]" ></td>
                                        <td ><input type="text" class="form-control" name="rainfalltemp[oct]" ></td>
                                        <td ><input type="text" class="form-control" name="rainfalltemp[nov]" ></td>
                                        <td ><input type="text" class="form-control" name="rainfalltemp[dec]" ></td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <h4 class="blue">Destination Tips Info</h4>
                        </div>
                        <div class="col-md-5">
                            <input type="button" id="add-tips_information" class = "btn btn-primary add-tips_information pull-right" value = "Add New"></input>
                        </div>
                    </div>
                    <div class= "row" id ="tips_description_row">
                        <div id ="tips_description">
                            <div class="col-md-4">
                                <div class="form-group ">
                                    <input type="text" class="form-control" id="" name="tips_title[1]" placeholder="Add Title">
                                </div>
                            </div>
                            <div class="col-md-5">
                                <div class="from-group">
                                    <textarea  rows="4" class="form-control form-group"  name="tips_information[1]"></textarea>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <input type="button"  class = "btn btn-primary remove-tips_information" value = "Remove"></input>
                            </div>
                        </div>

                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <h4 class="blue">Decompression Info</h4>
                        </div>
                        <div class="form-group">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="location">Map Decompression chambers</label>
                                    <i class="fa fa-map-marker" style="font-size:24px;color:red;margin-left: 2rem;"></i><span style="font-size:12px">Drag and drop the pin on the location</span>
                                    <div id="location" style="width: 100%; height: 300px"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="location"></div>
                    <div class="box-footer">
                        <a href="{{ route('scubaya::admin::manage::destinations') }}"><button type="button" class="btn btn-default">Cancel</button></a>
                        <button type="submit" class="btn btn-info pull-right">Save</button>
                    </div>
                </div>
            </form>
        </div>
    </section>
    @include('admin.layouts.destination_script')
    {{-- For Dive Season --}}

    <script type="text/javascript">
        jQuery(document).ready(function(scubaya) {

            $("#country").countrySelect();

            var countryData = $.fn.countrySelect.getCountryData();
            var html        =   '';

            $.each(countryData, function(i, c) {

                html   +=
                    '            <li class="country" data-country-code="'+c.iso2+'">\n' +
                    '                <input name="selector_country[]"  class="count_Checkbox" type="checkbox" value="'+c.iso2+'" />'+
                    '                &nbsp; <div class="flag '+c.iso2+'"></div>\n' +
                    '                <span class="country-name">'+c.name+'</span>'+
                    '            </li>\n';
            });

            $('#countriesFlags #accepted_country').find('ul.country-list').append(html);

            $('.country-select input[type="checkbox"]').on('click', function() {

                var countryvisa = $(this).val();
                var flagicon = '{!! (asset("plugins/country-Picker/css/flags")) !!}';

                if ($(this).is(':checked')) {
                    var visa_html = '<span title="all_flags-'+countryvisa+'"><div class= "col-md-3"><img src = "'+flagicon+'/'+countryvisa+'.png"/><span class= "margin-rightflag">yes</span></div></span>';
                    $('#show_allcountry').append(visa_html);
                } else {
                    $('span[title="all_flags-' + countryvisa + '"]').remove();
                }
            });

            let tips_season = 2;

            $('#add-tips_information').click(function() {
                $('#tips_description_row').append('<div id ="tips_description">\n' +
                    '<div class="col-md-4">' +
                    '<div class="form-group ">' +
                    '<input type="text" class="form-control" id="" name="tips_title['+tips_season+']"  placeholder="Add title">\n' +
                    ' </div>' +
                    '</div>' +
                    '<div class="col-md-5">' +
                    '<div class="from-group">'+
                    '<textarea  rows="4" class="form-control form-group"  name="tips_information['+tips_season+']"></textarea>\n' +
                    '</div>' +
                    '</div>' +
                    '<div class="col-md-3">' +
                    '<input type="button"  class = "btn btn-primary remove-tips_information" value = "Remove"></input>\n' +
                    '</div>' +
                    '</div>' +
                    '')
                tips_season++;
            });
            $('#tips_description_row').on('click','.remove-tips_information',function() {
                $(this).parent().parent().remove();
                tips_season--;
            });

            $('#is_subdestination_active input').on('change', function() {
                $('#is-subdestination-of').show();
            });

            $('#is_subdestination_deactive input').on('change', function() {
                $('#is-subdestination-of').hide();
            });

            $('#whole_year_exposure').click(function(){
                if($(this).prop('checked')){
                    $('.exposure_box').hide();
                }else{
                    $('.exposure_box').show();
                }
            });
            $('#no_exposure').click(function(){
                if($(this).prop('checked')){
                    $('.exposure_box').hide();
                }else{
                    $('.exposure_box').show();
                }
            });

            $('#whole_year_rain').click(function(){
                if($(this).prop('checked')){
                    $('.rain_box').hide();
                }else{
                    $('.rain_box').show();
                }
            });
            $('#no_rain').click(function(){
                if($(this).prop('checked')){
                    $('.rain_box').hide();
                }else{
                    $('.rain_box').show();
                }
            });

            $('#whole_year').click(function(){
                if($(this).prop('checked')){
                    $('.season_box').hide();
                }else{
                    $('.season_box').show();
                }
            });
            $('#no_dive_season').click(function(){
                if($(this).prop('checked')){
                    $('.season_box').hide();
                }else{
                    $('.season_box').show();
                }
            });
        });
    </script>
@endsection
