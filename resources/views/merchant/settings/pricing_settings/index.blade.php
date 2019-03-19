@extends('merchant.layouts.app')
@section('title', 'Pricing Settings')
@section('breadcrumb')
    <li><a href="#">Settings</a></li>
    <li class="active"><span>Pricing Settings</span></li>
@endsection

@section('content')
    @include('merchant.layouts.mainheader')

    <section id="room_pricing_setting_section" class="padding-20">
        <form name="room_pricing_setting" method="post" action="{{ route('scubaya::merchant::save_pricing_settings', [Auth::id()]) }}">
            {{ csrf_field() }}
            <div class="nav-tabs-custom">
                <ul class="nav nav-tabs">
                    <li class="active"><a href="#currency" data-toggle="tab" aria-expanded="true">Currency</a></li>
                    <li ><a href="#tax" data-toggle="tab" aria-expanded="true">Tax</a></li>
                </ul>

                <div class="tab-content">
                    <div class="tab-pane active margin-bottom-10" id="currency">
                        <div class="row margin-bottom-10">
                            <label for="tariff_currency" class="col-md-2">Currency</label>
                            <div class="col-md-4">
                                 <select name="tariff_currency" class="form-control">
                                     <option value="">-- Select Currency --</option>
                                     @if(count($currencies) > 0)
                                         @foreach($currencies as $currency)
                                             <option value="{{ @$currency->name }}" @if(@$currency->name == @$settings->mcurrency) selected @endif>{{ @$currency->name }}</option>
                                         @endforeach
                                     @endif
                                 </select>
                            </div>
                        </div>

                        <div class="row margin-bottom-10">
                            <label for="currency_format" class="col-md-2">Currency Format</label>
                            <div class="col-md-4">
                                <select name="currency_format" class="form-control">
                                    <option value="">-- Select Currency Format --</option>
                                    <option value="123,000.000" @if(@$settings->currency_format == '123,000.000') selected @endif>123,000.000</option>
                                </select>
                            </div>
                        </div>

                        <div class="row margin-bottom-10">
                            <label for="tariff_mode" class="col-md-2">Tariff Mode</label>
                            <div class="col-md-4">
                                <select name="tariff_mode" class="form-control" id="tariff_mode">
                                    <option value="">-- Select Tariff Mode --</option>
                                    <option value="micro" @if(@$settings->tariff_mode == 'micro') selected @endif>Micromanage</option>
                                    <option value="advance" @if(@$settings->tariff_mode == 'advance') selected @endif>Advance Tariff</option>
                                    <option value="normal" @if(@$settings->tariff_mode == 'normal') selected @endif>Normal Tariff</option>
                                </select>
                            </div>
                        </div>

                        <div class="micro_options @if(@$settings->tariff_mode != 'micro') hidden @endif ">
                            <div class="row margin-bottom-10">
                                <label for="tariff_default_price" class="col-md-2 control-label">Tariff Default Price</label>
                                <div class="col-md-4">
                                    <input type="text" class="form-control" id="tariff_default_price" name="tariff_default_price" value="{{$defaultValues['default_price'] or 100}}">
                                </div>
                                <div class="col-md-4">
                                    <p><strong>Default price for tariff</strong></p>
                                </div>
                            </div>

                            <div class="row margin-bottom-10">
                                <label for="tariff_default_min_nights" class="col-md-2 control-label">Tariff Default Min Nights</label>
                                <div class="col-md-4">
                                    <input type="text" class="form-control" id="tariff_default_min_nights" name="tariff_default_min_nights" value="{{ $defaultValues['default_min_nights'] or 1 }}">
                                </div>
                                <div class="col-md-4">
                                    <p><strong>Default minimum nights for tariff</strong></p>
                                </div>
                            </div>

                            <div class="row margin-bottom-10">
                                <label for="default_years_to_show" class="col-md-2 control-label">Years To Show</label>
                                <div class="col-md-4">
                                    <input type="text" class="form-control" id="default_years_to_show" name="default_years_to_show" value="{{ $defaultValues['default_years'] or 2 }}">
                                </div>
                                <div class="col-md-4">
                                    <p><strong>Defines the number of years to show when creating a tariff</strong></p>
                                </div>
                            </div>
                        </div>

                        <div class="row margin-bottom-10">
                            <label for="tariff_model" class="col-md-2">Select the tariffs model you want to use</label>
                            <div class="col-md-4">
                                <div class="btn-group" id="status" data-toggle="buttons">
                                    <label class="btn btn-default btn-on btn-sm @if(@$settings->tariff_model === '1') active @elseif(is_null(@$settings->tariff_model)) active @endif">
                                        <input type="radio" value="1" name="tariff_model" @if(@$settings->tariff_model === '1') checked @elseif(is_null(@$settings->tariff_model)) checked @endif>Flat Rate</label>

                                    <label class="btn btn-default btn-off btn-sm @if(@$settings->tariff_model === '0') active @endif">
                                        <input type="radio" value="0" name="tariff_model" @if(@$settings->tariff_model === '0') checked @endif>Variable</label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <p>
                                    <strong>
                                    You have two choices of tariff calculation methods.The first, the flat rate method allows you to offer a number of different tariffs
                                    to the guest and the rate for the stay is the same for the entire time.This is useful if you want to offer several different tariffs for the same date,
                                    eg bed and breakfast tariff and B&B and evening meal tariff.Choose the 'averages' tariff if you want to adjust your prices dependent on the date of question.
                                    The system will find all the tariffs for each day in the booking, and add them together then return the average rate across the period.
                                    </strong>
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="tab-pane margin-bottom-10" id="tax">
                        {{--<div class="row margin-bottom-10">
                            <label for="tax_rate" class="col-md-2">Tax Rate</label>
                            <div class="col-md-4">
                                <input class="form-control" name="tax_rate" value="@if(!empty(@$settings->tax_rate)) {{@$settings->tax_rate}} @endif" placeholder="Tax Rate">
                            </div>
                        </div>--}}

                        <div class="row margin-bottom-10">
                            <label for="is_tax_percentage" class="col-md-2">Is Percentage?</label>
                            <div class="col-md-4">
                                <div class="btn-group" id="status" data-toggle="buttons">
                                    <label class="btn btn-default btn-on btn-sm @if(@$settings->is_tax_percentage === '1') active @elseif(is_null(@$settings->is_tax_percentage)) active @endif">
                                        <input type="radio" value="1" name="is_tax_percentage" @if(@$settings->is_tax_percentage === '1') checked @elseif(is_null(@$settings->is_tax_percentage)) checked @endif>Yes</label>

                                    <label class="btn btn-default btn-off btn-sm @if(@$settings->is_tax_percentage === '0') active @endif">
                                        <input type="radio" value="0" name="is_tax_percentage" @if(@$settings->is_tax_percentage === '0') checked @endif>No</label>
                                </div>
                            </div>
                        </div>

                        <div class="row margin-bottom-10">
                            <label for="prices_gross" class="col-md-2">Prices Are Gross?</label>
                            <div class="col-md-4">
                                <div class="btn-group" id="status" data-toggle="buttons">
                                    <label class="btn btn-default btn-on btn-sm @if(@$settings->prices_gross === '1') active @elseif(is_null(@$settings->prices_gross)) active @endif">
                                        <input type="radio" value="1" name="prices_gross" @if(@$settings->prices_gross === '1') checked @elseif(is_null(@$settings->prices_gross)) checked @endif>Yes</label>

                                    <label class="btn btn-default btn-off btn-sm @if(@$settings->prices_gross === '0') active @endif">
                                        <input type="radio" value="0" name="prices_gross" @if(@$settings->prices_gross === '0') checked @endif>No</label>
                                </div>
                            </div>
                        </div>

                        <div class="row margin-bottom-10">
                            <label for="prices_pppn" class="col-md-2">Per Person, Per Night</label>
                            <div class="col-md-4">
                                <div class="btn-group" id="status" data-toggle="buttons">
                                    <label class="btn btn-default btn-on btn-sm @if(@$settings->prices_pppn === '1') active @elseif(is_null(@$settings->prices_pppn)) active @endif">
                                        <input type="radio" value="1" name="prices_pppn" @if(@$settings->prices_pppn === '1') checked @elseif(is_null(@$settings->prices_pppn)) checked @endif>Yes</label>

                                    <label class="btn btn-default btn-off btn-sm @if(@$settings->prices_pppn === '0') active @endif">
                                        <input type="radio" value="0" name="prices_pppn" @if(@$settings->prices_pppn === '0') checked @endif>No</label>
                                </div>
                            </div>
                        </div>

                        <div class="row margin-bottom-10">
                            <label for="tourist_tax_rate" class="col-md-2">Tourist Tax Rate</label>
                            <div class="col-md-4">
                                <input class="form-control" name="tourist_tax_rate" value="@if(!empty(@$settings->tourist_tax_rate)) {{@$settings->tourist_tax_rate}} @endif" placeholder="Tax Rate">
                            </div>
                        </div>

                        <div class="row margin-bottom-10">
                            <label for="is_tourist_rate_percentage" class="col-md-2">Is Percentage?</label>
                            <div class="col-md-4">
                                <div class="btn-group" id="status" data-toggle="buttons">
                                    <label class="btn btn-default btn-on btn-sm @if(@$settings->is_tourist_rate_percentage === '1') active @elseif(is_null(@$settings->is_tourist_rate_percentage)) active @endif">
                                        <input type="radio" value="1" name="is_tourist_rate_percentage" @if(@$settings->is_tourist_rate_percentage === '1') checked @elseif(is_null(@$settings->is_tourist_rate_percentage)) checked @endif>Yes</label>

                                    <label class="btn btn-default btn-off btn-sm @if(@$settings->is_tourist_rate_percentage === '0') active @endif">
                                        <input type="radio" value="0" name="is_tourist_rate_percentage" @if(@$settings->is_tourist_rate_percentage === '0') checked @endif>No</label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="box-footer">
                        <a href="{{ route('scubaya::merchant::dashboard', [Auth::id()]) }}"><button type="button" class="btn btn-default">Cancel</button></a>
                        <button type="submit" class="btn btn-info pull-right">Save</button>
                    </div>
                </div>
            </div>
        </form>
    </section>
    <script type="text/javascript">
        jQuery('#tariff_mode').change(function () {
            if(jQuery(this).val() == 'micro'){
                jQuery('.micro_options').removeClass('hidden');
            } else {
                jQuery('.micro_options').addClass('hidden');
            }
        });
    </script>
@endsection
