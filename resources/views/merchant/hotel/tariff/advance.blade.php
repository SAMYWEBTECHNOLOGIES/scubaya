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
            <label for="rate" class="control-label">Rate per night</label>
            <input class="form-control" name="advance[rate]" placeholder="Rate Per Night" value="{{@$additionalTariffData['advance']->rate}}">
        </div>
    </div>

    <div class="col-md-3">
        <div class="form-group">
            <label for="min_rooms" class="control-label">Min Rooms Already Selected</label>
            <select id="min_rooms" name="advance[min_rooms]" class="form-control">
                <?php for($i = 0; $i <= 100; $i++){ ?>
                <option value="{{ $i }}" @if(@($additionalTariffData['advance']->min_rooms) && $i == $additionalTariffData['advance']->min_rooms) selected @endif>{{ $i }}</option>
                <?php } ?>
            </select>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-3">
        <div class="form-group">
            <label for="min_days" class="control-label">Min Days</label>
            <select id="min_days" name="advance[min_days]" class="form-control">
                <?php for($i = 1; $i <= 100; $i++){ ?>
                <option value="{{ $i }}" @if(@($additionalTariffData['advance']->min_days) && $i == $additionalTariffData['advance']->min_days) selected @endif>{{ $i }}</option>
                <?php } ?>
            </select>
        </div>
    </div>

    <div class="col-md-3">
        <div class="form-group">
            <label for="max_days" class="control-label">Max Days</label>
            <select id="max_days" name="advance[max_days]" class="form-control">
                <?php for($i = 1; $i <= 100; $i++){ ?>
                <option value="{{ $i }}" @if(@($additionalTariffData['advance']->max_days) && $i == $additionalTariffData['advance']->max_days) selected @endif>{{ $i }}</option>
                <?php } ?>
            </select>
        </div>
    </div>

    <div class="col-md-3">
        <div class="form-group">
            <label for="min_people" class="control-label">Min People</label>
            <select id="min_people" name="advance[min_people]" class="form-control">
                <?php for($i = 1; $i <= config('scubaya.min_people_in_room'); $i++){ ?>
                <option value="{{ $i }}" @if(@($additionalTariffData['advance']->min_people) && $i == $additionalTariffData['advance']->min_people) selected @endif>{{ $i }}</option>
                <?php } ?>
            </select>
        </div>
    </div>

    <div class="col-md-3">
        <div class="form-group">
            <label for="max_people" class="control-label">Max People</label>
            <select id="max_people" name="advance[max_people]" class="form-control">
                <?php for($i = 1; $i <= config('scubaya.min_people_in_room'); $i++){ ?>
                <option value="{{ $i }}" @if(@($additionalTariffData['advance']->max_people) && $i == $additionalTariffData['advance']->max_people) selected @endif>{{ $i }}</option>
                <?php } ?>
            </select>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-4">
        <div class="form-group">
            <label for="valid_from" class="control-label">Valid From</label>
            <input class="form-control datepicker" name="advance[valid_from]" value="{{@$additionalTariffData['advance']->valid_from}}">
        </div>
    </div>

    <div class="col-md-4">
        <div class="form-group">
            <label for="valid_to" class="control-label">Valid To</label>
            <input class="form-control datepicker" name="advance[valid_to]" value="{{@$additionalTariffData['advance']->valid_to}}">
        </div>
    </div>

    <div class="col-md-4">
        <div class="form-group">
            <label for="check-in-day" class="control-label">Check-in Day Of Week</label>
            <select name="advance[check_in_day]" class="form-control">
                    <option value="all">All</option>
                    <option value="sunday" @if(@($additionalTariffData['advance']->check_in_day) && 'sunday' == $additionalTariffData['advance']->check_in_day) selected @endif>Sunday</option>
                    <option value="monday" @if(@($additionalTariffData['advance']->check_in_day) && 'monday' == $additionalTariffData['advance']->check_in_day) selected @endif>Monday</option>
                    <option value="tuesday" @if(@($additionalTariffData['advance']->check_in_day) && 'tuesday' == $additionalTariffData['advance']->check_in_day) selected @endif>Tuesday</option>
                    <option value="wednesday" @if(@($additionalTariffData['advance']->check_in_day) && 'wednesday' == $additionalTariffData['advance']->check_in_day) selected @endif>Wednesday</option>
                    <option value="thursday" @if(@($additionalTariffData['advance']->check_in_day) && 'thursday' == $additionalTariffData['advance']->check_in_day) selected @endif>Thursday</option>
                    <option value="friday" @if(@($additionalTariffData['advance']->check_in_day) && 'friday' == $additionalTariffData['advance']->check_in_day) selected @endif>Friday</option>
                    <option value="saturday" @if(@($additionalTariffData['advance']->check_in_day) && 'saturday' == $additionalTariffData['advance']->check_in_day) selected @endif>Saturday</option>
            </select>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-4">
        <div class="form-group">
            <label for="ignore_pppn">Ignore PPPN</label><br>
            <div class="btn-group" id="status" data-toggle="buttons">
                <label class="btn btn-default btn-on btn-sm @if(@$additionalTariffData['advance']->ignore_pppn === '1') active @elseif(empty($additionalTariffData['advance'])) active @endif">
                    <input type="radio" value="1" name="advance[ignore_pppn]" @if(@$additionalTariffData['advance']->ignore_pppn === '1') checked @elseif(empty($additionalTariffData['advance'])) checked @endif>Yes</label>

                <label class="btn btn-default btn-off btn-sm @if(@$additionalTariffData['advance']->ignore_pppn === '0') active @endif">
                    <input type="radio" value="0" name="advance[ignore_pppn]" @if(@$additionalTariffData['advance']->ignore_pppn === '0') checked @endif>No</label>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="form-group">
            <label for="allow_weekends">Allow Weekends</label><br>
            <div class="btn-group" id="status" data-toggle="buttons">
                <label class="btn btn-default btn-on btn-sm @if(@$additionalTariffData['advance']->allow_weekends === '1')  active @elseif(empty($additionalTariffData['advance'])) active @endif">
                    <input type="radio" value="1" name="advance[allow_weekends]" @if(@$additionalTariffData['advance']->allow_weekends === '1') checked @elseif(empty($additionalTariffData['advance'])) checked @endif>Yes</label>

                <label class="btn btn-default btn-off btn-sm @if(@$additionalTariffData['advance']->allow_weekends === '0') active @endif">
                    <input type="radio" value="0" name="advance[allow_weekends]" @if(@$additionalTariffData['advance']->allow_weekends === '0') checked @endif>No</label>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="form-group">
            <label for="weekends_only">Weekends Only</label><br>
            <div class="btn-group" id="status" data-toggle="buttons">
                <label class="btn btn-default btn-on btn-sm @if(@$additionalTariffData['advance']->weekends_only === '1')  active @elseif(empty($additionalTariffData['advance'])) active @endif">
                    <input type="radio" value="1" name="advance[weekends_only]" @if(@$additionalTariffData['advance']->weekends_only === '1') checked @elseif(empty($additionalTariffData['advance'])) checked @endif>Yes</label>

                <label class="btn btn-default btn-off btn-sm @if(@$additionalTariffData['advance']->weekends_only === '0') active @endif">
                    <input type="radio" value="0" name="advance[weekends_only]" @if(@$additionalTariffData['advance']->weekends_only === '0') checked @endif>No</label>
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