<div class="row">
    <div class="col-md-3">
        <div class="form-group">
            <label for="tariff_title">Tariff Title</label>
            <input type="text" class="form-control" id="tariff_title" placeholder="Enter Tariff Title" name="tariff_title" value="{{@$tariffs['tariff_title']}}">
        </div>
    </div>

    <div class="col-md-3">
        <div class="form-group">
            <label for="room" class="control-label">Room</label>
            (e.g : Room Single, Room Double)
            <select id="room" name="room" class="form-control select2" tabindex="-1">
                <option value="" disabled selected>-- Select Room --</option>
                @if(count($roomNames) > 0)
                    @foreach($roomNames as $name)
                        <option value="{{ $name->id }}" @if(@$tariffs['room_id'] && $tariffs['room_id'] == $name->id) selected @endif>{{ ucwords($name->name) }}</option>
                    @endforeach
                @endif
            </select>
        </div>
    </div>

    <div class="col-md-3">
        <div class="form-group">
            <label for="min_people" class="control-label">Min People</label>
            <select id="min_people" name="micro[min_people]" class="form-control">
                <?php for($i = 1; $i <= config('scubaya.min_people_in_room'); $i++){ ?>
                <option value="{{ $i }}" @if(@($additionalTariffData['micro']->min_people) && $i == $additionalTariffData['micro']->min_people) selected @endif>{{ $i }}</option>
                <?php } ?>
            </select>
        </div>
    </div>

    <div class="col-md-3">
        <div class="form-group">
            <label for="max_people" class="control-label">Max People</label>
            <select id="max_people" name="micro[max_people]" class="form-control">
                <?php for($i = 1; $i <= config('scubaya.min_people_in_room'); $i++){ ?>
                <option value="{{ $i }}" @if(@($additionalTariffData['micro']->max_people) && $i == $additionalTariffData['micro']->max_people) selected @endif>{{ $i }}</option>
                <?php } ?>
            </select>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-4">
        <div class="form-group">
            <label for="ignore_pppn">Ignore PPPN</label><br>
            <div class="btn-group" id="status" data-toggle="buttons">
                <label class="btn btn-default btn-on btn-sm @if(@$additionalTariffData['micro']->ignore_pppn === '1') active @elseif(empty($additionalTariffData['micro'])) active @endif">
                    <input type="radio" value="1" name="micro[ignore_pppn]" @if(@$additionalTariffData['micro']->ignore_pppn === '1') checked @elseif(empty($additionalTariffData['micro'])) checked @endif>Yes</label>

                <label class="btn btn-default btn-off btn-sm @if(@$additionalTariffData['micro']->ignore_pppn === '0') active @endif">
                    <input type="radio" value="0" name="micro[ignore_pppn]" @if(@$additionalTariffData['micro']->ignore_pppn === '0') checked @endif>No</label>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="form-group">
            <label for="allow_weekends">Allow Weekends</label><br>
            <div class="btn-group" id="status" data-toggle="buttons">
                <label class="btn btn-default btn-on btn-sm @if(@$additionalTariffData['micro']->allow_weekends === '1')  active @elseif(empty($additionalTariffData['micro'])) active @endif">
                    <input type="radio" value="1" name="micro[allow_weekends]" @if(@$additionalTariffData['micro']->allow_weekends === '1') checked @elseif(empty($additionalTariffData['micro'])) checked @endif>Yes</label>

                <label class="btn btn-default btn-off btn-sm @if(@$additionalTariffData['micro']->allow_weekends === '0') active @endif">
                    <input type="radio" value="0" name="micro[allow_weekends]" @if(@$additionalTariffData['micro']->allow_weekends === '0') checked @endif>No</label>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="form-group">
            <label for="weekends_only">Weekends Only</label><br>
            <div class="btn-group" id="status" data-toggle="buttons">
                <label class="btn btn-default btn-on btn-sm @if(@$additionalTariffData['micro']->weekends_only === '1')  active @elseif(empty($additionalTariffData['micro'])) active @endif">
                    <input type="radio" value="1" name="micro[weekends_only]" @if(@$additionalTariffData['micro']->weekends_only === '1') checked @elseif(empty($additionalTariffData['micro'])) checked @endif>Yes</label>

                <label class="btn btn-default btn-off btn-sm @if(@$additionalTariffData['micro']->weekends_only === '0') active @endif">
                    <input type="radio" value="0" name="micro[weekends_only]" @if(@$additionalTariffData['micro']->weekends_only === '0') checked @endif>No</label>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="form-group">
            <label for="tariff_description">Description</label>
            <textarea class="form-control" id="tariff_description" placeholder="Enter Tariff Description" name="tariff_description">{!! @$tariffs['tariff_description'] !!}</textarea>
        </div>
    </div>
</div>

<div class="row margin-bottom-10">
    <div class="col-md-4">
        <h4 class="blue">Set prices or minimum nights</h4>
        <select id="interval_price_selector" autocomplete="off" class="form-control">
            <option value="1">Set prices</option>
            <option value="2">Set minimum nights</option>
        </select>
    </div>
</div>

<div class="row margin-bottom-10">
    <div class="col-md-12">
        <div class="tariff_multi_input">
            <h4 class="blue">Set price per night by day of week</h4>
        </div>
        <div class="mindays_multi_input hidden">
            <h4 class="blue">Set minimum nights by day of week</h4>
        </div>

        @if(count($tariffData['dowInitArrays']) > 0)
            <table width="100%" class="table-responsive">
                <tr>
                    @foreach($tariffData['dowInitArrays'] as $dowInitArray)
                        <th>{!!$dowInitArray['BUTTON']!!} </br> {!!$dowInitArray['INPUT']!!} </th>
                    @endforeach
                </tr>
            </table>
        @endif
    </div>
</div>

<?php
if(@$additionalTariffData['micro']->price_or_min_nights_by_date_range){
    $price_or_min_nights_by_date_range = (array)json_decode($additionalTariffData['micro']->price_or_min_nights_by_date_range);
}
?>

<div class="row margin-bottom-10">
    <div class="col-md-12">
        <div class="tariff_multi_input">
            <h4 class="blue">Set price per night by date range</h4>
        </div>
        <div class="mindays_multi_input hidden">
            <h4 class="blue">Set minimum nights by date range</h4>
        </div>
    </div>

    <div class="col-md-3">
        <label for="start_date" class="control-label">Start Range</label>
        <input class="form-control datepicker" type="text" name="micro[start_date]" id="start_date" readonly value="{{@$price_or_min_nights_by_date_range['start_date']}}">
    </div>

    <div class="col-md-3">
        <label for="end_date" class="control-label">End Range</label>
        <input class="form-control datepicker" type="text" name="micro[end_date]" id="end_date" readonly value="{{@$price_or_min_nights_by_date_range['end_date']}}">
    </div>

    <div class="col-md-3 tariff_multi_input">
        <label class="control-label" for="picker_from">Rate</label>
        <div class="controls">
            <input type="text" class="input-mini form-control" name="micro[picker_rate_value]" id="picker_rate_value" value="@if(@$price_or_min_nights_by_date_range['price']) {{$price_or_min_nights_by_date_range['price']}} @else {{$tariffData['default_price']  or 100}} @endif">
        </div>
    </div>

    <div class="col-md-3 tariff_multi_input">
        <label for="minimum_days" class="control-label">Action</label>
        <div class="form-actions">
            <input type="button" id="set_value" value="Set Prices" onclick="jomres_micromanage_rate_picker('tariffinput')" class="btn btn-primary form-control">
        </div>
    </div>

    <div class="col-md-3 mindays_multi_input hidden">
        <label class="control-label" for="picker_from">Set minimum nights</label>
        <div class="controls">
            <input type="text" class="input-mini form-control" name="micro[picker_mindays_value]" id="picker_mindays_value" value="@if(@$price_or_min_nights_by_date_range['min_nights']) {{$price_or_min_nights_by_date_range['min_nights']}} @else {{$tariffData['default_min_nights'] or 1}} @endif">
        </div>
    </div>

    <div class="col-md-3 mindays_multi_input hidden">
        <label for="minimum_days" class="control-label">Action</label>
        <div class="form-actions">
            <input type="button" id="set_value" value="Set minimum days" onclick="jomres_micromanage_rate_picker('mindaysinput')" class="btn btn-primary form-control">
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="tariff_multi_input">
            <h4 class="blue">Set prices manually</h4>
        </div>
        <div class="mindays_multi_input hidden">
            <h4 class="blue">Set minimum nights manually</h4>
        </div>
    </div>

    <div class="col-md-12">
        <div id="manual_input_wrapper" class="alert alert-success">
            @if(count($tariffData['datesInYearsArray']) > 0)
                @foreach($tariffData['datesInYearsArray'] as $datesInYear)
                    <h4 class="year_month_title">{{$datesInYear['YEAR']}} {{$datesInYear['MONTH']}}</h4>
                    <table width="100%" class="table table-striped table-responsive">
                        <thead>
                        <tr>
                            <th>{!!$datesInYear['DAYS1']!!}</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <td>{!!$datesInYear['INPUTS1']!!}</td>
                        </tr>
                        </tbody>
                    </table>
                    <table width="100%" class="table table-striped table-condensed">
                        <thead>
                        <tr>
                            <th>{!!$datesInYear['DAYS2']!!}</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <td>{!!$datesInYear['INPUTS2']!!}</td>
                        </tr>
                        </tbody>
                    </table>
                @endforeach
            @endif
        </div>
    </div>
</div>
