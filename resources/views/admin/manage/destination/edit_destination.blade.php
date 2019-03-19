@extends('admin.layouts.app')
@section('title','Edit Destination')
@section('breadcrumb')
    <li><a href="{{route('scubaya::admin::dashboard')}}">Manage</a></li>
    <li class="active"><a href="{{route('scubaya::admin::manage::destinations')}}">Destinations</a></li>
    <li class="active"><span>{{$destination->name}}</span></li>
@endsection

@section('content')
    <section class="container screen-fit">
        <div class="box box-primary ">
            <div class="box-header with-border">
                <h3 class ="box-title">Edit Destination</h3>
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
            @php
                $label = array('1'=>'One','2'=>'Two','3'=>'Three','4'=>'Four','5'=>'Five');
            @endphp

            <form role="form" method="post" id="destinationForm" action = "{{route('scubaya::admin::manage::edit_destination',[$destination->id])}}" enctype="multipart/form-data">
                {{ csrf_field() }}
                <div class="box-body">
                    <div class="row">
                        <div class = "col-md-4">
                            <div class="form-group">
                                <label for="status" class="control-label" data-toggle="tooltip">Active</label><br>
                                <div class="btn-group" id="status" data-toggle="buttons">
                                    <label class="btn btn-default btn-on btn-sm @if($destination->active == 1) active @endif">
                                        <input type="radio" value="1" name="active" @if($destination->active == 1) checked="checked" @endif >YES</label>
                                    <label class="btn btn-default btn-off btn-sm @if($destination->active == 0) active @endif">
                                        <input type="radio" value="0" name="active" @if($destination->active == 0) checked="checked" @endif >NO</label>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="status" class="control-label" data-toggle="tooltip">Is Sub destination</label><br>
                                <div class="btn-group" id="status" data-toggle="buttons">
                                    <label  id = "is_subdestination_active" class="btn btn-default btn-on btn-sm @if($destination->is_sub_destination == 1) active @endif">
                                        <input type="radio" value="1"  name="is_sub_destination" @if($destination->is_sub_destination == 1) checked @endif>YES</label>

                                    <label id = "is_subdestination_deactive" class="btn btn-default btn-off btn-sm @if($destination->is_sub_destination == 0) active @endif">
                                        <input type="radio" value="0"  name="is_sub_destination" @if($destination->is_sub_destination == 0) checked @endif>NO</label>
                                </div>
                            </div>

                            <div class="form-group" style="padding-top: 9px;">
                                <label for="destination_name" data-toggle="tooltip">Destination name</label>
                                <input type="text" class="form-control" id="destination_name" value = "{{$destination->name}}" name="destination_name">
                            </div>
                            <div class="form-group">
                                <label for="destination_sub_name" data-toggle="tooltip">Destination sub name</label>
                                <input type="text" class="form-control" id="destination_sub_name" name="destination_sub_name" value = "{{$destination->sub_name}}">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group" id = "is-subdestination-of" @if($destination->is_sub_destination == 0) style = "display:none" @endif>
                                <label for="is_sub_destination">Is Sub destination of</label>
                                <select  class="selectpicker form-control show-tick" name="is_subdestination_of" title="Select main destination">
                                    @foreach($main_destinations as $main_destination)
                                        <option  title="{{$main_destination->name}}" @if($main_destination->id == $destination->is_subdestination_of) selected @endif value="{{$main_destination->id}}">{{$main_destination->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                @php
                                $language_all = json_decode($destination->language_spoken);
                                @endphp
                                <label for="language_spoken" data-toggle="tooltip">Language spoken</label>
                                <select id="language_spoken" multiple class="selectpicker form-control show-tick" name="language_spoken[]" title="Select Language">
                                    @foreach($languagesSpoken as $language)
                                        <option @if($language_all)@if(in_array($language->name,$language_all)) selected @endif @endif  value="{{$language->name}}">{{$language->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="country">Country</label>
                                <input type="text" class="form-control" name="country" id="country">
                            </div>
                            </div>
                            {{--<div class="form-group">--}}
                                {{--<label for="country">Country</label>--}}
                                {{--<input type="text" class="form-control" name="country_name" id="country">--}}
                            {{--</div>--}}
                        <div class="col-md-4">
                            <div class="form-group thumbnail_main_image">
                                <label for="main_image" data-toggle="tooltip" title="Upload main image"><i class="fa fa-upload" aria-hidden="true"></i>   Upload main image</label>
                                <input type="file" class="form-control" id="main_image" name="image" value = "{{$destination->image}}" onchange="readURL(this)">
                                @if($destination->image)
                                <img src = "{{asset('/assets/images/scubaya/destination/'.$destination->id.'-'.$destination->image)}}" width = "30%" height = "30%">
                                @endif
                            </div>

                            <div class="form-group thumbnail_main_image">
                                @php
                                    $gallery_img = json_decode($destination->images);
                                @endphp
                                <label for="images" data-toggle="tooltip" title="Upload Max 6 images"><i class="fa fa-upload" aria-hidden="true"></i>   Upload max 6 images</label>
                                <input type="file" class="form-control" id="max_images" name="images[]" value ="{{$destination->images}}" multiple onchange="readURL(this)">
                                @if($gallery_img)
                                    @foreach($gallery_img as $key =>$item)
                                        <img src = "{{asset('/assets/images/scubaya/destination/gallery/destination-'.$destination->id.'/'.$item)}}" width = "30%" height = "30%">
                                    @endforeach
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="row ">
                        <div class="col-md-12">
                            <div id="destination-location-map" class="margin-bottom-10" style="width: 100%; height: 300px"></div>
                        </div>

                        <div class="col-md-4">
                            <label>Location</label>
                            <input name="location" class="form-control" id="destination-location" value="{{ $destination->location }}">
                        </div>

                        <div class="col-md-4">
                            <label>Latitude</label>
                            <input name="latitude" class="form-control" id="destination-latitude" value="{{ $destination->latitude }}">
                        </div>

                        <div class="col-md-4">
                            <label>Longitude</label>
                            <input name="longitude" class="form-control" id="destination-longitude" value="{{ $destination->longitude }}">
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
                                        <option  @if($destination->country_currency== $currency->symbol)selected @endif  title="{{$currency->currency_name}} - {{$currency->symbol}}" value="{{$currency->symbol}}">{{$currency->currency_name}} - {{$currency->symbol}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="country_currency">Accepted Currency</label>
                                @php
                                $accepted_currency = json_decode($destination->accepted_currency)
                                @endphp
                                <select id="accepted_country_currency" data-size="5" data-selected-text-format="count > 4" data-live-search="true" multiple class="selectpicker form-control show-tick" name="accepted_country_currency[]" title="Select Currency">
                                    @foreach($currency_all as $currency)
                                        <option  @if($accepted_currency)@if(in_array($currency->currency_code,$accepted_currency)) selected @endif @endif title="{{$currency->currency_code}}" data-tokens="{{$currency->currency_code}}" value="{{$currency->currency_code}}">{{$currency->currency_name}}({{$currency->currency_code}}) </option>
                                    @endforeach
                                </select>

                            </div>
                            <div class="form-group">
                                <label for="geographic_area">Geographical Area</label>
                                <select id="geographic_area" class="selectpicker form-control show-tick" name="geographical_area" title="Select Geographical Area" data-size="5">
                                    <option @if($destination->geographical_area == 'Asia')selected @endif value="Asia">Asia</option>
                                    <option @if($destination->geographical_area == 'Commonwealth countries')selected @endif value="Commonwealth countries">Commonwealth countries</option>
                                    <option @if($destination->geographical_area == 'Intercontinental areas (Western Hemisphere)')selected @endif value="Intercontinental areas (Western Hemisphere)">Intercontinental areas (Western Hemisphere)</option>
                                    <option @if($destination->geographical_area == 'Developing countries')selected @endif value="Developing countries"> Developing countries</option>
                                    <option @if($destination->geographical_area == 'Europe')selected @endif value="Europe">Europe</option>
                                    <option @if($destination->geographical_area == 'Africa')selected @endif value="Africa">Africa</option>
                                    <option @if($destination->geographical_area == 'French Community')selected @endif value="French Community">French Community</option>
                                    <option @if($destination->geographical_area == 'Indian Ocean')selected @endif value="Indian Ocean">Indian Ocean</option>
                                    <option @if($destination->geographical_area == 'Atlantic Ocean')selected @endif value="Atlantic Ocean">Atlantic Ocean</option>
                                    <option @if($destination->geographical_area == 'Intercontinental areas (Eastern Hemisphere)')selected @endif value="Intercontinental areas (Eastern Hemisphere)">Intercontinental areas (Eastern Hemisphere)</option>
                                    <option @if($destination->geographical_area == 'North America')selected @endif value="North America">North America</option>
                                    <option @if($destination->geographical_area == 'Pacific Ocean')selected @endif value="Pacific Ocean">Pacific Ocean</option>
                                    <option @if($destination->geographical_area == 'Cold regions')selected @endif value="Cold regions">Cold regions</option>
                                    <option @if($destination->geographical_area == 'Arctic Ocean; Arctic regions')selected @endif value="Arctic Ocean; Arctic regions">Arctic Ocean; Arctic regions</option>
                                    <option @if($destination->geographical_area == 'South America')selected @endif value="South America">South America</option>
                                    <option @if($destination->geographical_area == 'Antarctic Ocean; Antarctica')selected @endif value="Antarctic Ocean; Antarctica">Antarctic Ocean; Antarctica</option>
                                    <option @if($destination->geographical_area == 'Australasia')selected @endif value="Australasia">Australasia</option>
                                    <option @if($destination->geographical_area == 'Tropics')selected @endif value="Tropics">Tropics</option>
                                    <option @if($destination->geographical_area == 'Earth')selected @endif value="Earth">Earth</option>
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="region">Region</label>
                                <select id="region" class="selectpicker form-control show-tick" name="region" title="Select Region" data-size="5">
                                    <option @if($destination->region == 'Asia')selected @endif value="Asia">Asia</option>
                                    <option @if($destination->region == 'Commonwealth countries')selected @endif value="Commonwealth countries">Commonwealth countries</option>
                                    <option @if($destination->region == 'Intercontinental areas (Western Hemisphere)')selected @endif value="Intercontinental areas (Western Hemisphere)">Intercontinental areas (Western Hemisphere)</option>
                                    <option @if($destination->region == 'Developing countries')selected @endif value="Developing countries"> Developing countries</option>
                                    <option @if($destination->region == 'Europe')selected @endif value="Europe">Europe</option>
                                    <option @if($destination->region == 'Africa')selected @endif value="Africa">Africa</option>
                                    <option @if($destination->region == 'French Community')selected @endif value="French Community">French Community</option>
                                    <option @if($destination->region == 'Indian Ocean')selected @endif value="Indian Ocean">Indian Ocean</option>
                                    <option @if($destination->region == 'Atlantic Ocean')selected @endif value="Atlantic Ocean">Atlantic Ocean</option>
                                    <option @if($destination->region == 'Intercontinental areas (Eastern Hemisphere)')selected @endif value="Intercontinental areas (Eastern Hemisphere)">Intercontinental areas (Eastern Hemisphere)</option>
                                    <option @if($destination->region == 'North America')selected @endif value="North America">North America</option>
                                    <option @if($destination->region == 'Pacific Ocean')selected @endif value="Pacific Ocean">Pacific Ocean</option>
                                    <option @if($destination->region == 'Cold regions')selected @endif value="Cold regions">Cold regions</option>
                                    <option @if($destination->region == 'Arctic Ocean; Arctic regions')selected @endif value="Arctic Ocean; Arctic regions">Arctic Ocean; Arctic regions</option>
                                    <option @if($destination->region == 'South America')selected @endif value="South America">South America</option>
                                    <option @if($destination->region == 'Antarctic Ocean; Antarctica')selected @endif value="Antarctic Ocean; Antarctica">Antarctic Ocean; Antarctica</option>
                                    <option @if($destination->region == 'Australasia')selected @endif value="Australasia">Australasia</option>
                                    <option @if($destination->region == 'Tropics')selected @endif value="Tropics">Tropics</option>
                                    <option @if($destination->region == 'Earth')selected @endif value="Earth">Earth</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="capital_wikipedia">Capital</label>
                                <input type="text" class="form-control" name="capital_wikipedia" value = "{{$destination->capital_wikipedia}}" id="capital_wikipedia">
                            </div>
                            <div class="form-group">
                                <label>Time Zone</label>
                                <select class="selectpicker form-control show-tick" data-size="5" name="time_zone" id="time_zone" title="Time Zone">
                                    <option @if($destination->time_zone == '-12')selected @endif value="-12">(GMT-12:00) International Date Line West</option>
                                    <option @if($destination->time_zone == '-11')selected @endif value="-11">(GMT-11:00) Midway Island, Samoa</option>
                                    <option @if($destination->time_zone == '-10')selected @endif value="-10">(GMT-10:00) Hawaii</option>
                                    <option @if($destination->time_zone == '-9')selected @endif value="-9">(GMT-09:00) Alaska</option>
                                    <option @if($destination->time_zone == '-8')selected @endif value="-8">(GMT-08:00) Pacific Time (US & Canada)</option>
                                    <option @if($destination->time_zone == '-8')selected @endif value="-8">(GMT-08:00) Tijuana, Baja California</option>
                                    <option @if($destination->time_zone == '-7')selected @endif value="-7">(GMT-07:00) Arizona</option>
                                    <option @if($destination->time_zone == '-7')selected @endif value="-7">(GMT-07:00) Chihuahua, La Paz, Mazatlan</option>
                                    <option @if($destination->time_zone == '-7')selected @endif value="-7">(GMT-07:00) Mountain Time (US & Canada)</option>
                                    <option @if($destination->time_zone == '-6')selected @endif value="-6">(GMT-06:00) Central America</option>
                                    <option @if($destination->time_zone == '-6')selected @endif value="-6">(GMT-06:00) Central Time (US & Canada)</option>
                                    <option @if($destination->time_zone == '-6')selected @endif value="-6">(GMT-06:00) Guadalajara, Mexico City, Monterrey</option>
                                    <option @if($destination->time_zone == '-6')selected @endif value="-6">(GMT-06:00) Saskatchewan</option>
                                    <option @if($destination->time_zone == '-5')selected @endif value="-5">(GMT-05:00) Bogota, Lima, Quito, Rio Branco</option>
                                    <option @if($destination->time_zone == '-5')selected @endif value="-5">(GMT-05:00) Eastern Time (US & Canada)</option>
                                    <option @if($destination->time_zone == '-5')selected @endif value="-5">(GMT-05:00) Indiana (East)</option>
                                    <option @if($destination->time_zone == '-4')selected @endif value="-4">(GMT-04:00) Atlantic Time (Canada)</option>
                                    <option @if($destination->time_zone == '-4')selected @endif value="-4">(GMT-04:00) Caracas, La Paz</option>
                                    <option @if($destination->time_zone == '-4')selected @endif value="-4">(GMT-04:00) Manaus</option>
                                    <option @if($destination->time_zone == '-4')selected @endif value="-4">(GMT-04:00) Santiago</option>
                                    <option @if($destination->time_zone == '-3.5')selected @endif value="-3.5">(GMT-03:30) Newfoundland</option>
                                    <option @if($destination->time_zone == '-3')selected @endif value="-3">(GMT-03:00) Brasilia</option>
                                    <option @if($destination->time_zone == '-3')selected @endif value="-3">(GMT-03:00) Buenos Aires, Georgetown</option>
                                    <option @if($destination->time_zone == '-3')selected @endif value="-3">(GMT-03:00) Greenland</option>
                                    <option @if($destination->time_zone == '-3')selected @endif value="-3">(GMT-03:00) Montevideo</option>
                                    <option @if($destination->time_zone == '-2')selected @endif value="-2">(GMT-02:00) Mid-Atlantic</option>
                                    <option @if($destination->time_zone == '-1')selected @endif value="-1">(GMT-01:00) Cape Verde Is.</option>
                                    <option @if($destination->time_zone == '-1')selected @endif value="-1">(GMT-01:00) Azores</option>
                                    <option @if($destination->time_zone == '-0')selected @endif value="0">(GMT+00:00) Casablanca, Monrovia, Reykjavik</option>
                                    <option @if($destination->time_zone == '-0')selected @endif value="0">(GMT+00:00) Greenwich Mean Time : Dublin, Edinburgh, Lisbon, London</option>
                                    <option @if($destination->time_zone == '1')selected @endif value="1">(GMT+01:00) Amsterdam, Berlin, Bern, Rome, Stockholm, Vienna</option>
                                    <option @if($destination->time_zone == '1')selected @endif value="1">(GMT+01:00) Belgrade, Bratislava, Budapest, Ljubljana, Prague</option>
                                    <option @if($destination->time_zone == '1')selected @endif value="1">(GMT+01:00) Brussels, Copenhagen, Madrid, Paris</option>
                                    <option @if($destination->time_zone == '1')selected @endif value="1">(GMT+01:00) Sarajevo, Skopje, Warsaw, Zagreb</option>
                                    <option @if($destination->time_zone == '1')selected @endif value="1">(GMT+01:00) West Central Africa</option>
                                    <option @if($destination->time_zone == '2')selected @endif value="2">(GMT+02:00) Amman</option>
                                    <option @if($destination->time_zone == '2')selected @endif value="2">(GMT+02:00) Athens, Bucharest, Istanbul</option>
                                    <option @if($destination->time_zone == '2')selected @endif value="2">(GMT+02:00) Beirut</option>
                                    <option @if($destination->time_zone == '2')selected @endif value="2">(GMT+02:00) Cairo</option>
                                    <option @if($destination->time_zone == '2')selected @endif value="2">(GMT+02:00) Harare, Pretoria</option>
                                    <option @if($destination->time_zone == '2')selected @endif value="2">(GMT+02:00) Helsinki, Kyiv, Riga, Sofia, Tallinn, Vilnius</option>
                                    <option @if($destination->time_zone == '2')selected @endif value="2">(GMT+02:00) Jerusalem</option>
                                    <option @if($destination->time_zone == '2')selected @endif value="2">(GMT+02:00) Minsk</option>
                                    <option @if($destination->time_zone == '3')selected @endif value="2">(GMT+02:00) Windhoek</option>
                                    <option @if($destination->time_zone == '3')selected @endif value="3">(GMT+03:00) Kuwait, Riyadh, Baghdad</option>
                                    <option @if($destination->time_zone == '3')selected @endif value="3">(GMT+03:00) Moscow, St. Petersburg, Volgograd</option>
                                    <option @if($destination->time_zone == '3')selected @endif value="3">(GMT+03:00) Nairobi</option>
                                    <option @if($destination->time_zone == '3')selected @endif value="3">(GMT+03:00) Tbilisi</option>
                                    <option @if($destination->time_zone == '3.5')selected @endif value="3.5">(GMT+03:30) Tehran</option>
                                    <option @if($destination->time_zone == '4')selected @endif value="4">(GMT+04:00) Abu Dhabi, Muscat</option>
                                    <option @if($destination->time_zone == '4')selected @endif value="4">(GMT+04:00) Baku</option>
                                    <option @if($destination->time_zone == '4')selected @endif value="4">(GMT+04:00) Yerevan</option>
                                    <option @if($destination->time_zone == '4.5')selected @endif value="4.5">(GMT+04:30) Kabul</option>
                                    <option @if($destination->time_zone == '5')selected @endif value="5">(GMT+05:00) Yekaterinburg</option>
                                    <option @if($destination->time_zone == '5')selected @endif value="5">(GMT+05:00) Islamabad, Karachi, Tashkent</option>
                                    <option @if($destination->time_zone == '5.5')selected @endif value="5.5">(GMT+05:30) Sri Jayawardenapura</option>
                                    <option @if($destination->time_zone == '5.5')selected @endif value="5.5">(GMT+05:30) Chennai, Kolkata, Mumbai, New Delhi</option>
                                    <option @if($destination->time_zone == '5.75')selected @endif value="5.75">(GMT+05:45) Kathmandu</option>
                                    <option @if($destination->time_zone == '6')selected @endif value="6">(GMT+06:00) Almaty, Novosibirsk</option>
                                    <option @if($destination->time_zone == '6')selected @endif value="6">(GMT+06:00) Astana, Dhaka</option>
                                    <option @if($destination->time_zone == '6.5')selected @endif value="6.5">(GMT+06:30) Yangon (Rangoon)</option>
                                    <option @if($destination->time_zone == '7')selected @endif value="7">(GMT+07:00) Bangkok, Hanoi, Jakarta</option>
                                    <option @if($destination->time_zone == '7')selected @endif value="7">(GMT+07:00) Krasnoyarsk</option>
                                    <option @if($destination->time_zone == '8')selected @endif value="8">(GMT+08:00) Beijing, Chongqing, Hong Kong, Urumqi</option>
                                    <option @if($destination->time_zone == '8')selected @endif value="8">(GMT+08:00) Kuala Lumpur, Singapore</option>
                                    <option @if($destination->time_zone == '8')selected @endif value="8">(GMT+08:00) Irkutsk, Ulaan Bataar</option>
                                    <option @if($destination->time_zone == '8')selected @endif value="8">(GMT+08:00) Perth</option>
                                    <option @if($destination->time_zone == '8')selected @endif value="8">(GMT+08:00) Taipei</option>
                                    <option @if($destination->time_zone == '9')selected @endif value="9">(GMT+09:00) Osaka, Sapporo, Tokyo</option>
                                    <option @if($destination->time_zone == '9')selected @endif value="9">(GMT+09:00) Seoul</option>
                                    <option @if($destination->time_zone == '9')selected @endif value="9">(GMT+09:00) Yakutsk</option>
                                    <option @if($destination->time_zone == '9.5')selected @endif value="9.5">(GMT+09:30) Adelaide</option>
                                    <option @if($destination->time_zone == '9.5')selected @endif value="9.5">(GMT+09:30) Darwin</option>
                                    <option @if($destination->time_zone == '10')selected @endif value="10">(GMT+10:00) Brisbane</option>
                                    <option @if($destination->time_zone == '10')selected @endif value="10">(GMT+10:00) Canberra, Melbourne, Sydney</option>
                                    <option @if($destination->time_zone == '10')selected @endif value="10">(GMT+10:00) Hobart</option>
                                    <option @if($destination->time_zone == '10')selected @endif value="10">(GMT+10:00) Guam, Port Moresby</option>
                                    <option @if($destination->time_zone == '10')selected @endif value="10">(GMT+10:00) Vladivostok</option>
                                    <option @if($destination->time_zone == '11')selected @endif value="11">(GMT+11:00) Magadan, Solomon Is., New Caledonia</option>
                                    <option @if($destination->time_zone == '12')selected @endif value="12">(GMT+12:00) Auckland, Wellington</option>
                                    <option @if($destination->time_zone == '12')selected @endif value="12">(GMT+12:00) Fiji, Kamchatka, Marshall Is.</option>
                                    <option @if($destination->time_zone == '13')selected @endif value="13">(GMT+13:00) Nuku'alofa</option>
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="tipping">Tipping incl</label>
                                <select id="tipping" class="selectpicker form-control show-tick" name="tipping" title="Select Tipping">
                                    <optgroup label="Recommended">
                                        <option @if($destination->tipping=='Recommended(round up bill)')selected @endif value = "Recommended(round up bill)">Recommended (round up bill)</option>
                                        <option @if($destination->tipping=='Recommended(5-10%)')selected @endif value = "Recommended(5-10%)">Recommended (5-10%)</option>
                                        <option @if($destination->tipping=='Recommended(10%)')selected @endif value = "Recommended(10%)">Recommended(10%)</option>
                                        <option @if($destination->tipping=='Recommended(15%)')selected @endif value = "Recommended(15%)">Recommended(15%)</option>
                                        <option @if($destination->tipping=='Recommended in restaurants(10%),except if included')selected @endif value = "Recommended in restaurants(10%),except if included">Recommended in restaurants (10%),except if included</option>
                                        <option @if($destination->tipping=='Recommended in restaurants(10-15%), except if included')selected @endif value = "Recommended in restaurants(10-15%), except if included">Recommended in restaurants (10-15%), except if included</option>
                                        <option @if($destination->tipping=='Recommended in restaurants(15%), except if included')selected @endif value = "Recommended in restaurants(15%), except if included">Recommended in restaurants (15%), except if included</option>
                                        <option @if($destination->tipping=='Recommended if not included(10%)')selected @endif value = "Recommended if not included(10%)">Recommended if not included (10%)</option>
                                        <option @if($destination->tipping=='Recommended if not included(15%)')selected @endif value = "Recommended if not included(15%)">Recommended if not included (15%)</option>
                                        <option @if($destination->tipping=='Recommended if not included(10-15%)')selected @endif value = "Recommended if not included(10-15%)">Recommended if not included (10-15%)</option>
                                        <option @if($destination->tipping=='Recommended(10-15%),especially in restaurants')selected @endif value = "Recommended(10-15%),especially in restaurants">Recommended (10-15%),especially in restaurants</option>
                                    </optgroup>
                                    <optgroup label="Appreciated">
                                        <option @if($destination->tipping=='Appreciated(5%)')selected @endif value = "Appreciated(5%)">Appreciated (5%)</option>
                                        <option @if($destination->tipping=='Appreciated(10%)')selected @endif value = "Appreciated(10%)">Appreciated (10%)</option>
                                        <option @if($destination->tipping=='Appreciated(5-10%)')selected @endif value = "Appreciated(5-10%)">Appreciated (5-10%)</option>
                                        <option @if($destination->tipping=='Appreciated(round up bill)')selected @endif value = "Appreciated(round up bill)">Appreciated (round up bill)</option>
                                    </optgroup>
                                    <optgroup label="Rare">
                                        <option @if($destination->tipping=='Rare(recommended for hotel staff)')selected @endif value = "Rare(recommended for hotel staff)">Rare (recommended for hotel staff)</option>
                                    </optgroup>
                                    <optgroup label="Obligatory">
                                        <option @if($destination->tipping=='Obligatory(except if included)')selected @endif value = "Obligatory(except if included)">Obligatory (except if included)</option>
                                        <option @if($destination->tipping=='Obligatory(10-15%),except if included')selected @endif value = "Obligatory(10-15%),except if included">Obligatory (10-15%), except if included</option>
                                        <option @if($destination->tipping=='Obligatory in restaurants(10-15%),except if included')selected @endif value = "Obligatory in restaurants(10-15%),except if included">Obligatory in restaurants (10-15%), except if included</option>
                                        <option @if($destination->tipping=='Obligatory(15-20%)')selected @endif value = "Obligatory(15-20%)">Obligatory (15-20%)</option>
                                    </optgroup>
                                    <option @if($destination->tipping=='never')selected @endif value = "never">Never</option>
                                </select>
                            </div>

                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="electric_voltage">Electric Voltage</label>
                                <input type="text" class="form-control" name="electric_voltage" value = "{{$destination->voltage}}" id="electric_voltage">
                            </div>
                            <div class="form-group">
                                <label for="religion">Religion</label>
                                <input type="text" class="form-control" name="religion"  id="religion" value = "{{$destination->religion}}">
                            </div>
                            <div class="form-group">
                                <label for="population">Population</label>
                                <input type="text" class="form-control form-group" name="population" id="population" value = "{{$destination->population}}">
                            </div>
                            <div class="form-group">
                                <label for="phone code">Phone code</label>
                                <input type="text" class="form-control form-group" name="phone_code" id="phone_code" value = "{{$destination->phone_code}}">
                            </div>
                            <div class="form-group">
                                <label for="hdi rank"><b>HDI rank</b></label>
                                <input type="text" class="form-control form-group" name="hdi_rank" id="hdi_rank" value = "{{$destination->hdi_rank}}">
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
                            </div>
                        </div>
                        <div class = "col-md-4">
                            <div class="form-group">
                                <label for="active" class="control-label" data-toggle="tooltip">Show Water Temperature</label><br>
                                <div class="btn-group" id="status" data-toggle="buttons">
                                    <label class="btn btn-default btn-on btn-sm @if($destination->water_temperature == 1) active @endif">
                                        <input type="radio" value="1" name="water_temp" @if($destination->water_temperature == 1) checked @endif>YES</label>

                                    <label class="btn btn-default btn-off btn-sm @if($destination->water_temperature == 0) active @endif">
                                        <input type="radio" value="0" name="water_temp" @if($destination->water_temperature == 0) checked @endif>NO</label>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="active" class="control-label" data-toggle="tooltip">Show Weather</label><br>
                                <div class="btn-group" id="status" data-toggle="buttons">
                                    <label class="btn btn-default btn-on btn-sm @if($destination->weather == 1) active @endif">
                                        <input type="radio" value="1" name="weather" @if($destination->weather == 1) checked @endif>YES</label>

                                    <label class="btn btn-default btn-off btn-sm @if($destination->weather == 0) active @endif">
                                        <input type="radio" value="0" name="weather" @if($destination->weather == 0) checked @endif>NO</label>
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
                                <textarea  rows="4" class="form-control form-group" id="add_short_description" placeholder="Short Description" name="add_short_description">{{$destination->short_description}}</textarea>
                            </div>
                            <div class="from-group">
                                <label for="add_long_description">Add Long Description</label>
                                <textarea  rows="4" class="form-control form-group" id="add_long_description" placeholder="Long Description" name="add_long_description">{{$destination->long_description}}</textarea>
                            </div>
                            <h4 class="destination_season">Exposure period to cyclone and (rain) stroms</h4>
                            <div class="box-with-shadow">
                                <h4>Exposure</h4>
                                @php
                                    $exposureSeasonInfo   = json_decode($destination->exposure_season);
                                    $fromMonth            = key($exposureSeasonInfo->info->from);
                                    $tillMonth            = key($exposureSeasonInfo->info->till);
                                    $exposureFromState    = current($exposureSeasonInfo->info->from);
                                    $exposureTillState    = current($exposureSeasonInfo->info->till);
                                @endphp
                                <div class = "row">
                                    <div class ="col-md-6">
                                        <div class="form-group">
                                            <input type="checkbox" @if($exposureSeasonInfo->whole_year==1) checked @endif value="1" id="whole_year_exposure" name="whole_year_exposure"> The Whole Year
                                        </div>
                                    </div>
                                    <div class ="col-md-6">
                                        <div class="form-group">
                                            <input type="checkbox" @if($exposureSeasonInfo->no_exposure==1) checked @endif value="1" id="no_exposure" name="no_exposure"> No exposure
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
                                                    <option @if($fromMonth=="01") selected @endif value = "01">Jan</option>
                                                    <option @if($fromMonth=="02") selected @endif value = "02">Feb</option>
                                                    <option @if($fromMonth=="03") selected @endif value = "03">March</option>
                                                    <option @if($fromMonth=="04") selected @endif value = "04">Apr</option>
                                                    <option @if($fromMonth=="05") selected @endif value = "05">May</option>
                                                    <option @if($fromMonth=="06") selected @endif value = "06">June</option>
                                                    <option @if($fromMonth=="07") selected @endif value = "07">July</option>
                                                    <option @if($fromMonth=="08") selected @endif value = "08">Aug</option>
                                                    <option @if($fromMonth=="09") selected @endif value = "09">Sept</option>
                                                    <option @if($fromMonth=="10") selected @endif value = "10">Oct</option>
                                                    <option @if($fromMonth=="11") selected @endif value = "11">Nov</option>
                                                    <option @if($fromMonth=="12") selected @endif value = "12">Dec</option>
                                                </select>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <input type="checkbox" @if($exposureFromState=="1") checked @endif value="1" id="half_from_exposure" name="exposure_from_state"> Half
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
                                                    <option @if($tillMonth=="01") selected @endif value = "01">Jan</option>
                                                    <option @if($tillMonth=="02") selected @endif value = "02">Feb</option>
                                                    <option @if($tillMonth=="03") selected @endif value = "03">March</option>
                                                    <option @if($tillMonth=="04") selected @endif value = "04">Apr</option>
                                                    <option @if($tillMonth=="05") selected @endif value = "05">May</option>
                                                    <option @if($tillMonth=="06") selected @endif value = "06">June</option>
                                                    <option @if($tillMonth=="07") selected @endif value = "07">July</option>
                                                    <option @if($tillMonth=="08") selected @endif value = "08">Aug</option>
                                                    <option @if($tillMonth=="09") selected @endif value = "09">Sept</option>
                                                    <option @if($tillMonth=="10") selected @endif value = "10">Oct</option>
                                                    <option @if($tillMonth=="11") selected @endif value = "11">Nov</option>
                                                    <option @if($tillMonth=="12") selected @endif value = "12">Dec</option>
                                                </select>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <input type="checkbox" @if($exposureTillState=="1") checked @endif value="1" id="half_till_exposure" name="exposure_till_state"> Half
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <h4 class = "destination_season">Rain season</h4>
                            @php
                                $rainSeasonInfo   = json_decode($destination->rain_season);
                                $rainFromMonth    = key($rainSeasonInfo->info->from);
                                $rainTillMonth    = key($rainSeasonInfo->info->till);
                                $rainFromState    = current($rainSeasonInfo->info->from);
                                $rainTillState    = current($rainSeasonInfo->info->till);
                            @endphp
                            <div class="box-with-shadow">
                                <h4>Rain</h4>
                                <div class = "row">
                                    <div class ="col-md-6">
                                        <div class="form-group">
                                            <input type="checkbox" @if($rainSeasonInfo->whole_year=='1') checked @endif value="1" id="whole_year_rain" name="whole_year_rain"> The Whole Year
                                        </div>
                                    </div>
                                    <div class ="col-md-6">
                                        <div class="form-group">
                                            <input type="checkbox" @if($rainSeasonInfo->no_rain=='1') checked @endif value="1" id="no_rain" name="no_rain"> No rain season
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
                                                    <option @if($rainFromMonth =="01") selected @endif value = "01">Jan</option>
                                                    <option @if($rainFromMonth =="02") selected @endif value = "02">Feb</option>
                                                    <option @if($rainFromMonth =="03") selected @endif value = "03">March</option>
                                                    <option @if($rainFromMonth =="04") selected @endif value = "04">Apr</option>
                                                    <option @if($rainFromMonth =="05") selected @endif value = "05">May</option>
                                                    <option @if($rainFromMonth =="06") selected @endif value = "06">June</option>
                                                    <option @if($rainFromMonth =="07") selected @endif value = "07">July</option>
                                                    <option @if($rainFromMonth =="08") selected @endif value = "08">Aug</option>
                                                    <option @if($rainFromMonth =="09") selected @endif value = "09">Sept</option>
                                                    <option @if($rainFromMonth =="10") selected @endif value = "10">Oct</option>
                                                    <option @if($rainFromMonth =="11") selected @endif value = "11">Nov</option>
                                                    <option @if($rainFromMonth =="12") selected @endif value = "12">Dec</option>
                                                </select>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <input type="checkbox" @if($rainFromState=="1") checked @endif value="1" id="half_from_rain" name="rain_from_state"> Half
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
                                                    <option @if($rainTillMonth =="01") selected @endif value = "01">Jan</option>
                                                    <option @if($rainTillMonth =="02") selected @endif value = "02">Feb</option>
                                                    <option @if($rainTillMonth =="03") selected @endif value = "03">March</option>
                                                    <option @if($rainTillMonth =="04") selected @endif value = "04">Apr</option>
                                                    <option @if($rainTillMonth =="05") selected @endif value = "05">May</option>
                                                    <option @if($rainTillMonth =="06") selected @endif value = "06">June</option>
                                                    <option @if($rainTillMonth =="07") selected @endif value = "07">July</option>
                                                    <option @if($rainTillMonth =="08") selected @endif value = "08">Aug</option>
                                                    <option @if($rainTillMonth =="09") selected @endif value = "09">Sept</option>
                                                    <option @if($rainTillMonth =="10") selected @endif value = "10">Oct</option>
                                                    <option @if($rainTillMonth =="11") selected @endif value = "11">Nov</option>
                                                    <option @if($rainTillMonth =="12") selected @endif value = "12">Dec</option>
                                                </select>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <input type="checkbox" @if($rainTillState=="1") checked @endif value="1" id="half_till_rain" name="rain_till_state"> Half
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
                                <textarea  rows="4" class="form-control form-group" id="add_dive_description" placeholder="Dive Description" name="add_dive_description">{{$destination->dive_description}}</textarea>
                            </div>
                            <div class="from-group">
                                <label for="add_tourist_description">Add Tourist Description</label>
                                <textarea  rows="4" class="form-control form-group" id="add_tourist_description" placeholder="Tourist Description" name="add_tourist_description">{{$destination->tourist_description}}</textarea>
                            </div>
                            <h4 class="destination_season">Dive season</h4>
                            @php
                                $SeasonInfo       = json_decode($destination->season);
                                $diveFromMonth    = key($SeasonInfo->info->from);
                                $diveTillMonth    = key($SeasonInfo->info->till);
                                $diveFromState    = current($SeasonInfo->info->from);
                                $diveTillState    = current($SeasonInfo->info->till);
                            @endphp
                            <div class="box-with-shadow">
                                <h4>Dive</h4>
                                <div class = "row">
                                    <div class ="col-md-6">
                                        <div class="form-group">
                                            <input type="checkbox"  @if($SeasonInfo->whole_year==1) checked @endif value="1" id="whole_year" name="whole_year"> The Whole Year
                                        </div>
                                    </div>
                                    <div class ="col-md-6">
                                        <div class="form-group">
                                            <input type="checkbox"  @if($SeasonInfo->no_dive_season==1) checked @endif value="1" id="no_dive_season" name="no_dive_season"> No dive
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
                                                    <option @if($diveFromMonth =="01") selected @endif value = "01">Jan</option>
                                                    <option @if($diveFromMonth =="02") selected @endif value = "02">Feb</option>
                                                    <option @if($diveFromMonth =="03") selected @endif value = "03">March</option>
                                                    <option @if($diveFromMonth =="04") selected @endif value = "04">Apr</option>
                                                    <option @if($diveFromMonth =="05") selected @endif value = "05">May</option>
                                                    <option @if($diveFromMonth =="06") selected @endif value = "06">June</option>
                                                    <option @if($diveFromMonth =="07") selected @endif value = "07">July</option>
                                                    <option @if($diveFromMonth =="08") selected @endif value = "08">Aug</option>
                                                    <option @if($diveFromMonth =="09") selected @endif value = "09">Sept</option>
                                                    <option @if($diveFromMonth =="10") selected @endif value = "10">Oct</option>
                                                    <option @if($diveFromMonth =="11") selected @endif value = "11">Nov</option>
                                                    <option @if($diveFromMonth =="12") selected @endif value = "12">Dec</option>
                                                </select>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <input type="checkbox" @if($diveFromState=="1") checked @endif value="1" id="half_from_season" name="season_from_state"> Half
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
                                                    <option @if($diveTillMonth =="01") selected @endif value = "01">Jan</option>
                                                    <option @if($diveTillMonth =="02") selected @endif value = "02">Feb</option>
                                                    <option @if($diveTillMonth =="03") selected @endif value = "03">March</option>
                                                    <option @if($diveTillMonth =="04") selected @endif value = "04">Apr</option>
                                                    <option @if($diveTillMonth =="05") selected @endif value = "05">May</option>
                                                    <option @if($diveTillMonth =="06") selected @endif value = "06">June</option>
                                                    <option @if($diveTillMonth =="07") selected @endif value = "07">July</option>
                                                    <option @if($diveTillMonth =="08") selected @endif value = "08">Aug</option>
                                                    <option @if($diveTillMonth =="09") selected @endif value = "09">Sept</option>
                                                    <option @if($diveTillMonth =="10") selected @endif value = "10">Oct</option>
                                                    <option @if($diveTillMonth =="11") selected @endif value = "11">Nov</option>
                                                    <option @if($diveTillMonth =="12") selected @endif value = "12">Dec</option>
                                                </select>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <input type="checkbox" @if($diveTillState=="1") checked @endif value="1" id="half_till_season" name="season_till_state"> Half
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
                                    <select class="selectpicker form-control show-tick" id="reef_sea_floor" name="reef_sea_floor" title = "Select Reef/Sea Floor">
                                        <option @if($destination->rs_floor == 'none')selected @endif value="none">None</option>
                                        <option @if($destination->rs_floor == '1')selected @endif value="1">1 Star</option>
                                        <option @if($destination->rs_floor == '2')selected @endif value="2">2 Stars</option>
                                        <option @if($destination->rs_floor == '3')selected @endif value="3">3 Stars</option>
                                        <option @if($destination->rs_floor == '4')selected @endif value="4">4 Stars</option>
                                    </select>
                                </div>
                                <label for="macro">Macro</label>
                                <div class="half-width-input">
                                    <select class="selectpicker form-control show-tick" id="macro" name="macro" title = "Select Macro">
                                        <option @if($destination->macro == 'none')selected @endif value="none">None</option>
                                        <option @if($destination->macro == '1')selected @endif value="1">1 Star</option>
                                        <option @if($destination->macro == '2')selected @endif value="2">2 Stars</option>
                                        <option @if($destination->macro == '3')selected @endif value="3">3 Stars</option>
                                        <option @if($destination->macro == '4')selected @endif value="4">4 Stars</option>
                                    </select>
                                </div>

                                <label for="pelagic">Pelagic</label>
                                <div class="half-width-input">
                                    <select class="selectpicker form-control show-tick" id="pelagic" name="pelagic" title = "Select Pelagic">
                                        <option @if($destination->pelagic == 'none')selected @endif value="none">None</option>
                                        <option @if($destination->pelagic == '1')selected @endif value="1">1 Star</option>
                                        <option @if($destination->pelagic == '2')selected @endif value="2">2 Stars</option>
                                        <option @if($destination->pelagic == '3')selected @endif value="3">3 Stars</option>
                                        <option @if($destination->pelagic == '4')selected @endif value="4">4 Stars</option>
                                    </select>
                                </div>

                                <label for="wreck">Wreck</label>
                                <div class="half-width-input">
                                    <select class="selectpicker form-control show-tick" id="wreck" name="wreck" title = "Select Wreck">
                                        <option @if($destination->wreck == 'none')selected @endif value="none">None</option>
                                        <option @if($destination->wreck == '1')selected @endif value="1">1 Star</option>
                                        <option @if($destination->wreck == '2')selected @endif value="2">2 Stars</option>
                                        <option @if($destination->wreck == '3')selected @endif value="3">3 Stars</option>
                                        <option @if($destination->wreck == '4')selected @endif value="4">4 Stars</option>
                                    </select>
                                </div>
                                <label for="wreck">Climate</label>
                                <div class="half-width-input">
                                    <select class="selectpicker form-control show-tick" id="climate" name="climate" title = "Select Climate">
                                        <option @if($destination->climate == 'Tropical')selected @endif value="Tropical">Tropical</option>
                                        <option @if($destination->climate == 'Dessert')selected @endif value="Dessert">Dessert</option>
                                        <option @if($destination->climate == 'Rain')selected @endif value="Rain">Rain</option>
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
                                        @php
                                            $watertemp = json_decode($destination->water_temp);
                                        @endphp
                                        <th scope="row">Water temp</th>
                                        <td ><input type="text" class="form-control" name="watertemp[jan]" value = "{{$watertemp->jan}}" ></td>
                                        <td ><input type="text" class="form-control" name="watertemp[feb]" value = "{{$watertemp->feb}}" ></td>
                                        <td ><input type="text" class="form-control" name="watertemp[mar]" value = "{{$watertemp->mar}}" ></td>
                                        <td ><input type="text" class="form-control" name="watertemp[apr]" value = "{{$watertemp->apr}}" ></td>
                                        <td ><input type="text" class="form-control" name="watertemp[may]" value = "{{$watertemp->may}}" ></td>
                                        <td ><input type="text" class="form-control" name="watertemp[june]"value = "{{$watertemp->june}}"></td>
                                        <td ><input type="text" class="form-control" name="watertemp[july]"value = "{{$watertemp->july}}"></td>
                                        <td ><input type="text" class="form-control" name="watertemp[aug]" value = "{{$watertemp->aug}}" ></td>
                                        <td ><input type="text" class="form-control" name="watertemp[sept]"value = "{{$watertemp->sept}}"></td>
                                        <td ><input type="text" class="form-control" name="watertemp[oct]" value = "{{$watertemp->oct}}" ></td>
                                        <td ><input type="text" class="form-control" name="watertemp[nov]" value = "{{$watertemp->nov}}" ></td>
                                        <td ><input type="text" class="form-control" name="watertemp[dec]" value = "{{$watertemp->dec}}" ></td>


                                    </tr>
                                    <tr>
                                        @php
                                            $minairtemp = json_decode($destination->min_air_temp);
                                        @endphp
                                        <th scope="row">Min air temp</th>
                                        <td ><input type="text" class="form-control" name="minairtemp[jan]"  value = "{{$minairtemp->jan}}"></td>
                                        <td ><input type="text" class="form-control" name="minairtemp[feb]"  value = "{{$minairtemp->feb}}"></td>
                                        <td ><input type="text" class="form-control" name="minairtemp[mar]"  value = "{{$minairtemp->mar}}"></td>
                                        <td ><input type="text" class="form-control" name="minairtemp[apr]"  value = "{{$minairtemp->apr}}"></td>
                                        <td ><input type="text" class="form-control" name="minairtemp[may]"  value = "{{$minairtemp->may}}"></td>
                                        <td ><input type="text" class="form-control" name="minairtemp[june]" value = "{{$minairtemp->june}}"></td>
                                        <td ><input type="text" class="form-control" name="minairtemp[july]" value = "{{$minairtemp->july}}"></td>
                                        <td ><input type="text" class="form-control" name="minairtemp[aug]"  value = "{{$minairtemp->aug}}"></td>
                                        <td ><input type="text" class="form-control" name="minairtemp[sept]" value = "{{$minairtemp->sept}}"></td>
                                        <td ><input type="text" class="form-control" name="minairtemp[oct]"  value = "{{$minairtemp->oct}}"></td>
                                        <td ><input type="text" class="form-control" name="minairtemp[nov]"  value = "{{$minairtemp->nov}}"></td>
                                        <td ><input type="text" class="form-control" name="minairtemp[dec]"  value = "{{$minairtemp->dec}}"></td>
                                    </tr>
                                    <tr>
                                        @php
                                            $maxairtemp = json_decode($destination->max_air_temp);
                                        @endphp
                                        <th scope="row">Max air temp</th>
                                        <td ><input type="text" class="form-control" name="maxairtemp[jan]" value = "{{$maxairtemp->jan}}"></td>
                                        <td ><input type="text" class="form-control" name="maxairtemp[feb]" value = "{{$maxairtemp->feb}}"></td>
                                        <td ><input type="text" class="form-control" name="maxairtemp[mar]" value = "{{$maxairtemp->mar}}"></td>
                                        <td ><input type="text" class="form-control" name="maxairtemp[apr]" value = "{{$maxairtemp->apr}}"></td>
                                        <td ><input type="text" class="form-control" name="maxairtemp[may]" value = "{{$maxairtemp->may}}"></td>
                                        <td ><input type="text" class="form-control" name="maxairtemp[june]"value = "{{$maxairtemp->june}}"></td>
                                        <td ><input type="text" class="form-control" name="maxairtemp[july]"value = "{{$maxairtemp->july}}"></td>
                                        <td ><input type="text" class="form-control" name="maxairtemp[aug]" value = "{{$maxairtemp->aug}}"></td>
                                        <td ><input type="text" class="form-control" name="maxairtemp[sept]"value = "{{$maxairtemp->sept}}"></td>
                                        <td ><input type="text" class="form-control" name="maxairtemp[oct]" value = "{{$maxairtemp->oct}}"></td>
                                        <td ><input type="text" class="form-control" name="maxairtemp[nov]" value = "{{$maxairtemp->nov}}"></td>
                                        <td ><input type="text" class="form-control" name="maxairtemp[dec]" value = "{{$maxairtemp->dec}}"></td>
                                    </tr>
                                    <tr>
                                        @php
                                            $raintemp = json_decode($destination->rain_fall_temp);
                                        @endphp
                                        <th scope="row">Rainfall</th>
                                        <td ><input type="text" class="form-control" name="rainfalltemp[jan]" value = "{{$raintemp->jan}}"></td>
                                        <td ><input type="text" class="form-control" name="rainfalltemp[feb]" value = "{{$raintemp->feb}}"></td>
                                        <td ><input type="text" class="form-control" name="rainfalltemp[mar]" value = "{{$raintemp->mar}}"></td>
                                        <td ><input type="text" class="form-control" name="rainfalltemp[apr]" value = "{{$raintemp->apr}}"></td>
                                        <td ><input type="text" class="form-control" name="rainfalltemp[may]" value = "{{$raintemp->may}}"></td>
                                        <td ><input type="text" class="form-control" name="rainfalltemp[june]"value = "{{$raintemp->june}}"></td>
                                        <td ><input type="text" class="form-control" name="rainfalltemp[july]"value = "{{$raintemp->july}}"></td>
                                        <td ><input type="text" class="form-control" name="rainfalltemp[aug]" value = "{{$raintemp->aug}}"></td>
                                        <td ><input type="text" class="form-control" name="rainfalltemp[sept]"value = "{{$raintemp->sept}}"></td>
                                        <td ><input type="text" class="form-control" name="rainfalltemp[oct]" value = "{{$raintemp->oct}}"></td>
                                        <td ><input type="text" class="form-control" name="rainfalltemp[nov]" value = "{{$raintemp->nov}}"></td>
                                        <td ><input type="text" class="form-control" name="rainfalltemp[dec]" value = "{{$raintemp->dec}}"></td>
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
                        @php $counttips = 1; @endphp
                        @if($destination->destination_tips)
                            @foreach(json_decode($destination->destination_tips) as $tipsinfo )
                                <div id ="tips_description">
                                    <div class="col-md-4">
                                        <div class="form-group ">
                                            <input type="text" class="form-control" id="" name="tips_title[{{$counttips}}]" value = "{{$tipsinfo->label}}" placeholder="Add title">
                                        </div>
                                    </div>
                                    <div class="col-md-5">
                                        <div class="from-group">
                                            <textarea  rows="4" class="form-control form-group"  name="tips_information[{{$counttips}}]">{{$tipsinfo->description}}</textarea>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <input type="button"  class = "btn btn-primary remove-tips_information" value = "Remove"></input>
                                    </div>
                                </div>
                                @php $counttips++; @endphp
                            @endforeach

                        @endif
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <h4 class="blue">Decompression Info</h4>
                        </div>
                        <div class="form-group">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="location">Map Decompression chambers</label>
                                    <i class="fa fa-map-marker" style="font-size:24px;color:#ff0000;margin-left: 2rem;"></i><span style="font-size:12px">Drag and drop the pin on the location</span>
                                    <div id="location" style="width: 100%; height: 300px"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="location">
                        @php
                            $locations_decompress = json_decode($destination->map_decompression_chambers);
                        @endphp
                        @if($locations_decompress)
                            @foreach($locations_decompress as $key => $value)
                                <div class="row margin-bottom-10">
                                    <div class="col-md-8">
                                        <div class="col-md-6">
                                            {{$key}}
                                        </div>
                                        <div class="col-md-6 text-right">
                                            <span class="location-publish-label">Show in Front End</span>
                                            <div class="btn-group" id="status" data-toggle="buttons">
                                                <label class="btn btn-default btn-on btn-sm @if($value->show == 1) active @endif">
                                                    <input type="radio" value="1" name="decompression[{{$key}}][show]" @if($value->show == 1) checked @endif>YES</label>
                                                <label class="btn btn-default btn-off btn-sm @if($value->show == 0) active @endif">
                                                    <input type="radio" value="0" name="decompression[{{$key}}][show]" @if($value->show == 0) checked @endif>NO</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <input name="decompression[{{$key}}][lat]" type="hidden" class="form-control" value="{{ $value->lat }}">
                                        <input name="decompression[{{$key}}][long]" type="hidden" class="form-control" value="{{ $value->long }}">
                                    </div>
                                </div>
                            @endforeach
                        @endif
                    </div>
                </div>
                <div class="box-footer">
                    <a href="{{ route('scubaya::admin::manage::destinations') }}"><button type="button" class="btn btn-default">Cancel</button></a>
                    <button type="submit" class="btn btn-info pull-right">Update</button>
                </div>
            </form>
        </div>
    </section>
    @include('admin.layouts.destination_script')
    {{-- For Dive Season --}}

    <script type="text/javascript">
        jQuery(document).ready(function(scubaya) {
            var countryData     =   $.fn.countrySelect.getCountryData();
            var html            =   '';var countryChecked = '';
            var visaCountries   =   '{!! ($destination->visa_countries) !!}';
            var flagicon        =   '{!! (asset("plugins/country-Picker/css/flags")) !!}';

            $.each(countryData, function(i, c) {
                if(visaCountries!= 'null') {
                    if (visaCountries.indexOf(c.iso2) >= 0) {
                        countryChecked = 'checked';
                        var flag_code = c.iso2;
                        var visa_html = '<span title="all_flags-' + c.iso2 + '">' +
                            '<div class= "col-md-3"><img src = "' + flagicon + '/' + flag_code + '.png"/>' +
                            '<span class= "margin-rightflag">yes</span>' +
                            '</div></span>';
                        $('#show_allcountry').append(visa_html);

                    } else {
                        countryChecked = '';
                    }
                }

                html   +=
                    '            <li class="country" data-country-code="'+c.iso2+'">\n' +
                    '                <input name="selector_country[]"  class="count_Checkbox" type="checkbox" '+countryChecked+' value="'+c.iso2+'" />'+
                    '                &nbsp; <div class="flag '+c.iso2+'"></div>\n' +
                    '                <span class="country-name">'+c.name+'</span>'+
                    '            </li>\n';
            });

            $('#countriesFlags #accepted_country').find('ul.country-list').append(html);

            $('.country-select input[type="checkbox"]').on('click', function() {

                var countryvisa = $(this).val();
                if ($(this).is(':checked')) {
                    var visa_html = '<span title="all_flags-'+countryvisa+'"><div class= "col-md-3"><img src = "'+flagicon+'/'+countryvisa+'.png"/><span class= "margin-rightflag">yes</span></div></span>';
                    $('#show_allcountry').append(visa_html);
                } else {
                    $('span[title="all_flags-' + countryvisa + '"]').remove();
                }
            });

            let tips_season = {{$counttips}};

            $('#add-tips_information').click(function() {
                $('#tips_description_row').append('<div id ="tips_description">\n' +
                    '<div class="col-md-4">\n' +
                    '<div class="form-group ">\n' +
                    '<input type="text" class="form-control" id="" name="tips_title['+tips_season+']"  placeholder="Add title">\n' +
                    ' </div>\n' +
                    '</div>\n' +
                    '<div class="col-md-5">\n' +
                    '<div class="from-group">\n' +
                    '<textarea  rows="4" class="form-control form-group"  name="tips_information['+tips_season+']"></textarea>\n' +
                    '</div>\n' +
                    '</div>\n' +
                    '<div class="col-md-3">\n' +
                    '<input type="button"  class = "btn btn-primary remove-tips_information" value = "Remove"></input>\n' +
                    '</div>\n' +
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

            var noSeason = document.getElementById("no_dive_season");
            var wholeYearSeason = document.getElementById("whole_year");
            if (noSeason.checked == true || wholeYearSeason.checked == true){
                $('.season_box').hide();
            }

            var noExposure = document.getElementById("no_exposure");
            var wholeYearExposure = document.getElementById("whole_year_exposure");
            if (noExposure.checked == true || wholeYearExposure.checked == true){
                $('.exposure_box').hide();
            }

            var noRain = document.getElementById("no_rain");
            var wholeYearRain = document.getElementById("whole_year_rain");
            if (noRain.checked == true || wholeYearRain.checked == true){
                $('.rain_box').hide();
            }

            var country =   "{{ ucwords($destination->country) }}";
            $("#country").countrySelect();
            $("#country").countrySelect("setCountry", country);
        });

    </script>
@endsection