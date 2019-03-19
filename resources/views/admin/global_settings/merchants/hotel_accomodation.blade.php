@extends('admin.layouts.app')
@section('title','Hotel And Accommodation Settings')
@section('content')
    <section id="room_pricing_setting_section" class="padding-20">
        @if(Session::has('success'))
            <div class="alert alert-success">
                <a href="#" class="close" data-dismiss="alert">&times;</a>
                <p>{{ Session::get('success') }}</p>
            </div>
        @endif
        <form name="room_pricing_setting" method="post" action="{{ route('scubaya::admin::global_settings::merchants::hotel_accommodation') }}">
            {{ csrf_field() }}
            <div class="nav-tabs-custom">
                <ul class="nav nav-tabs">
                    <li class="active"><a href="#micromanage" data-toggle="tab" aria-expanded="true">Micromanage Setting</a></li>
                </ul>

                <div class="tab-content">
                    <div class="tab-pane active margin-bottom-10" id="micromanage">
                        <div class="row margin-bottom-10">
                            <label for="tariff_default_price" class="col-md-2 control-label">Tariff Default Price</label>
                            <div class="col-md-4">
                                <input type="text" class="form-control" id="tariff_default_price" name="tariff_default_price" value="{{$global_settings['merchant.hotel_accomodation.tariff_default_price'] or 100 }}">
                            </div>
                            <div class="col-md-4">
                                <p>Default price for tariff</p>
                            </div>
                        </div>

                        <div class="row margin-bottom-10">
                            <label for="tariff_default_min_nights" class="col-md-2 control-label">Tariff Default Min Nights</label>
                            <div class="col-md-4">
                                <input type="text" class="form-control" id="tariff_default_min_nights" name="tariff_default_min_nights" value="{{$global_settings['merchant.hotel_accomodation.tariff_default_min_nights'] or 1 }}">
                            </div>
                            <div class="col-md-4">
                                <p>Default minimum nights for tariff</p>
                            </div>
                        </div>

                        <div class="row">
                            <label for="default_years_to_show" class="col-md-2 control-label">Years To Show</label>
                            <div class="col-md-4">
                                <input type="text" class="form-control" id="default_years_to_show" name="default_years_to_show" value="{{$global_settings['merchant.hotel_accomodation.default_years_to_show'] or 1}}">
                            </div>
                            <div class="col-md-4">
                                <p>Defines the number of years to show when creating a tariff</p>
                            </div>
                        </div>
                    </div>

                    <div class="box-footer">
                        <button type="submit" class="btn btn-primary ">Save</button>
                    </div>
                </div>
            </div>
        </form>
    </section>

@endsection