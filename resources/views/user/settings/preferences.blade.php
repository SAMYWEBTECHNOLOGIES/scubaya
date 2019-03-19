@extends('user.layouts.app')
@section('title','Preferences')
@section('contentheader')
    <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> User</a></li>
        <li class="active">Preferences</li>
    </ol>
@endsection
@php
    use Jenssegers\Agent\Agent as Agent;
    $Agent = new Agent();
@endphp
@section('content')
    <div class="content">
        <div class="row margin-20">
            <div class="col-md-12">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title">Preferences</h3>
                    </div>
                    @if(Session::has('error'))
                        <div class="alert alert-danger">
                            <a href="#" class="close" data-dismiss="alert">&times;</a>
                            <p>{{ Session::get('error') }}</p>
                        </div>
                    @endif
                    @if(Session::has('success_preferences'))
                        <div class="alert alert-success">
                            <a href="#" class="close" data-dismiss="alert">&times;</a>
                            <p>{{ Session::get('success_preferences') }}</p>
                        </div>
                    @endif

                    @if($errors->preferences->any())
                        <div class="alert alert-danger">
                            <ul>
                                <a href="#" class="close" data-dismiss="alert">&times;</a>
                                @foreach ($errors->preferences->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    <form method="post" action="{{route('scubaya::user::settings::preferences',[Auth::id()])}}">
                        {{csrf_field()}}
                        <div class="box-body" id="preference-screen">
                            <div class="row">
                                <div class = "col-md-6">
                                    <div class="form-group row">
                                        <label for="distance" data-toggle="tooltip" class="col-md-4 col-form-label"> Distance:</label>
                                        <div class="col-md-8">
                                            <div class="btn-group col-md-offset-1" id="status" data-toggle="buttons">
                                                <label class="btn btn-default btn-on btn-sm @if(@$preferences==null) active @endif @if(@$preferences->distance  ==  'meter') active @endif">
                                                    <input type="radio" value="meter" name="distance"
                                                           @if(@$preferences->distance  ==  'meter') checked @endif>Meter</label>

                                                <label class="btn btn-default btn-on btn-sm @if(@$preferences->distance  ==  'feet') active @endif">
                                                    <input type="radio" value="feet" name="distance"
                                                           @if(@$preferences->distance  ==  'feet') checked @endif>Feet</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="weight" data-toggle="tooltip" class="col-md-4 col-form-label"> Weight:</label>
                                        <div class="col-md-8">
                                            <div class="btn-group col-md-offset-1" id="status" data-toggle="buttons">
                                                <label class="btn btn-default btn-on btn-sm @if(@$preferences==null) active @endif @if(@$preferences->weight  ==  'kilogram') active @endif">
                                                    <input type="radio" value="kilogram" name="weight"
                                                           @if(@$preferences->weight  ==  'kilogram') checked @endif>Kilogram</label>

                                                <label class="btn btn-default btn-on btn-sm @if(@$preferences->weight  ==  'pounds') active @endif">
                                                    <input type="radio" value="pounds" name="weight"
                                                           @if(@$preferences->weight  ==  'pounds') checked @endif>Pounds</label>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label for="pressure" data-toggle="tooltip" class="col-md-4 col-form-label"> Pressure:</label>
                                        <div class="col-md-8">
                                            <div class="btn-group col-md-offset-1" id="pressure" data-toggle="buttons">
                                                <label class="btn btn-default btn-on btn-sm @if(@$preferences==null) active @endif @if(@$preferences->pressure  ==  'bar') active @endif">
                                                    <input type="radio" value="bar" name="pressure"
                                                           @if(@$preferences->pressure  ==  'bar') checked @endif>BAR</label>

                                                <label class="btn btn-default btn-on btn-sm @if(@$preferences->pressure  ==  'psi') active @endif">
                                                    <input type="radio" value="psi" name="pressure"
                                                           @if(@$preferences->pressure  ==  'psi') checked @endif>PSI</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="temperature" data-toggle="tooltip" class="col-md-4 col-form-label"> Temperature:</label>
                                        <div class="col-md-8">
                                            <div class="btn-group col-md-offset-1" id="temperature" data-toggle="buttons">
                                                <label class="btn btn-default btn-on btn-sm @if(@$preferences==null) active @endif @if(@$preferences->temperature  ==  'celcius') active @endif">
                                                    <input type="radio" value="celcius" name="temperature"
                                                           @if(@$preferences->temperature  ==  'celcius') checked @endif>Celcius(&deg;C)</label>

                                                <label class="btn btn-default btn-on btn-sm @if(@$preferences->temperature  ==  'fahrenheit') active @endif">
                                                    <input type="radio" value="fahrenheit" name="temperature"
                                                           @if(@$preferences->temperature  ==  'fahrenheit') checked @endif>Fahrenheit(&deg;F)</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="volume" data-toggle="tooltip" class="col-md-4 col-form-label"> Volume:</label>
                                        <div class="col-md-8">
                                            <div class="btn-group col-md-offset-1" id="volume" data-toggle="buttons">
                                                <label class="btn btn-default btn-on btn-sm @if(@$preferences==null) active @endif @if(@$preferences->volume  ==  'liter') active @endif">
                                                    <input type="radio" value="liter" name="volume"
                                                           @if(@$preferences->volume  ==  'liter') checked @endif>Liter</label>

                                                <label class="btn btn-default btn-on btn-sm @if(@$preferences->volume  ==  'cubic_feet') active @endif">
                                                    <input type="radio" value="cubic_feet" name="volume"
                                                           @if(@$preferences->volume  ==  'cubic_feet') checked @endif>Cubic
                                                    Feet(ft&sup3;)</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="date_format" data-toggle="tooltip" class="col-md-4 col-form-label"> Date Format:</label>
                                        <div class="col-md-8">
                                            <div class="btn-group col-md-offset-1" id="date_format" data-toggle="buttons">
                                                <label class="btn btn-default btn-on btn-sm @if(@$preferences==null) active @endif @if(@$preferences->date_format  ==  'dd-mm-yy') active @endif">
                                                    <input type="radio" value="dd-mm-yy" name="date_format"
                                                           @if(@$preferences->date_format  ==  'dd-mm-yy') checked @endif>dd-mm-yy</label>

                                                <label class="btn btn-default btn-on btn-sm @if(@$preferences->date_format  ==  'mm-dd-yy') active @endif">
                                                    <input type="radio" value="mm-dd-yy" name="date_format"
                                                           @if(@$preferences->date_format  ==  'mm-dd-yy') checked @endif>mm-dd-yy</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="time_format" data-toggle="tooltip" class="col-md-4 col-form-label"> Time Format:</label>
                                        <div class="col-md-8">
                                            <div class="btn-group col-md-offset-1" id="time_format" data-toggle="buttons">
                                                <label class="btn btn-default btn-on btn-sm @if(@$preferences==null) active @endif @if(@$preferences->time_format  ==  '12-hours') active @endif">
                                                    <input type="radio" value="12-hours" name="time_format"
                                                           @if(@$preferences->time_format  ==  '12-hours') checked @endif >12
                                                    hours</label>

                                                <label class="btn btn-default btn-on btn-sm @if(@$preferences->time_format  ==  '24-hours') active @endif">
                                                    <input type="radio" value="24-hours" name="time_format"
                                                           @if(@$preferences->time_format  ==  '24-hours') checked @endif>24
                                                    hours</label>
                                            </div>
                                        </div>
                                    </div>
                                    @if(($Agent->isMobile()))
                                    <div class="form-group row">
                                        <label for="coordinates_format" data-toggle="tooltip" class="col-md-4 col-form-label"> Coordinates Format:</label>
                                        <div class="col-md-8">
                                            <div class="btn-group col-md-offset-1" id="time_format" data-toggle="buttons">
                                                <label class="btn btn-default btn-on btn-sm @if(@$preferences==null) active @endif @if(@$preferences->coordinates_format  ==  'ddd.ddddd°') active @endif">
                                                    <input type="radio" value="ddd.ddddd&deg;" name="coordinates_format"
                                                           @if(@$preferences->coordinates_format  ==  'ddd.ddddd°') checked @endif >ddd.ddddd&deg;
                                                </label>

                                                <label class="btn btn-default btn-on btn-sm @if(@$preferences->coordinates_format  ==  'ddd°mm.mmm') active @endif">
                                                    <input type="radio" value="ddd&deg;mm.mmm" name="coordinates_format"
                                                           @if(@$preferences->coordinates_format  ==  'ddd°mm.mmm') checked @endif>ddd°mm.mmm
                                                </label>

                                                <label class="btn btn-default btn-on btn-sm @if(@$preferences->coordinates_format  ==  "ddd°mm'ss") active @endif">
                                                    <input type="radio" value="ddd&deg;mm'ss" name="coordinates_format"
                                                           @if(@$preferences->coordinates_format  ==  "ddd°mm'ss") checked @endif>ddd°mm'ss"
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    @endif
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group row">
                                        <label for="language" data-toggle="tooltip" class="col-md-4 col-form-label"> Language:</label>
                                        <div class="col-md-8">
                                            <select id="language" class="selectpicker form-control show-tick"
                                                    name="language">
                                                <option value="english">English(EN_UK)</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="currency" data-toggle="tooltip" class="col-md-4 col-form-label"> Currency</label>
                                        <div class="col-md-8">
                                            <select id="currency" data-size="5" data-selected-text-format="count > 4"
                                                    data-live-search="true" class="selectpicker form-control show-tick"
                                                    name="currency" title="currency">
                                                @foreach($currency_all as $currency)
                                                    <option title="{{$currency->currency_code}}"
                                                            data-tokens="{{$currency->currency_code}}"
                                                            value="{{$currency->currency_code}}">{{$currency->currency_name}}
                                                        ({{$currency->currency_code}})
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="departure_airport" data-toggle="tooltip" class="col-md-4 col-form-label"> Preffered Departure Airport</label>
                                        <div class="col-md-8">
                                            <select id="departure_airport" class="selectpicker form-control show-tick"
                                                    name="departure_airport">
                                                <option value="Schiphol">Schiphol(AMS)</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="newsletter" data-toggle="tooltip" class="col-md-4 col-form-label">Newsletter</label>
                                    </div>

                                    <div class ="form-group row">
                                        <div class="col-md-12">
                                           <input type = "checkbox"  name = "newsletter"  value = "1"  @if(@$preferences->newsletter == 1) checked @endif><span style = "margin-left: 5px">I agree that Scubaya.com sends me their newsletter</span>
                                        </div>
                                        <div class="col-md-12">
                                            <input type = "checkbox" name = "partners_related_offers"  value = "1" @if(@$preferences->partners_related_offers == 1) checked @endif ><span style = "margin-left: 5px">I agree that Scubaya.com sends me their partners related offers</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @if(!($Agent->isMobile()))
                                <div class="row">
                                    <div class="col-md-8">
                                        <div class="form-group row coordinates_format">
                                            <label for="coordinates_format" data-toggle="tooltip" class="col-md-3 col-form-label"> Coordinates Format:</label>
                                            <div class="col-md-8">
                                                <div class="btn-group" id="time_format" data-toggle="buttons">
                                                    <label class="btn btn-default btn-on btn-sm @if(@$preferences==null) active @endif @if(@$preferences->coordinates_format  ==  'ddd.ddddd°') active @endif">
                                                        <input type="radio" value="ddd.ddddd&deg;" name="coordinates_format"
                                                               @if(@$preferences->coordinates_format  ==  'ddd.ddddd°') checked @endif >ddd.ddddd&deg;
                                                    </label>

                                                    <label class="btn btn-default btn-on btn-sm @if(@$preferences->coordinates_format  ==  'ddd°mm.mmm') active @endif">
                                                        <input type="radio" value="ddd&deg;mm.mmm" name="coordinates_format"
                                                               @if(@$preferences->coordinates_format  ==  'ddd°mm.mmm') checked @endif>ddd°mm.mmm
                                                    </label>

                                                    <label class="btn btn-default btn-on btn-sm @if(@$preferences->coordinates_format  ==  "ddd°mm'ss") active @endif">
                                                        <input type="radio" value="ddd&deg;mm'ss" name="coordinates_format"
                                                               @if(@$preferences->coordinates_format  ==  "ddd°mm'ss") checked @endif>ddd°mm'ss"
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif
                            <div class="box-footer">
                                <button type="submit" class="btn btn-info pull-right">Save</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>



@if($preferences)
    <script type="text/javascript">
        jQuery(document).ready(function(scubaya) {
            scubaya('select[name=coordinates_format]').selectpicker('val',"{{@$preferences->coordinates_format}}");
            scubaya('select[name=language]').selectpicker('val',"{{@$preferences->language}}");
            scubaya('select[name=currency]').selectpicker('val',"{{@$preferences->currency}}");
            scubaya('select[name=departure_airport]').selectpicker('val',"{{@$preferences->departure_airport}}");
        });
    </script>
@endif

@endsection