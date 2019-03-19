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
            <label for="min_people" class="control-label">Min people</label>
            <select id="min_people" name="normal[min_people]" class="form-control">
                <?php for($i = 1; $i <= 100; $i++){ ?>
                <option value="{{ $i }}" @if(@($additionalTariffData['normal']->min_people) && $i == $additionalTariffData['normal']->min_people) selected @endif>{{ $i }}</option>
                <?php } ?>
            </select>
        </div>
    </div>

    <div class="col-md-3">
        <div class="form-group">
            <label for="max_people" class="control-label">Max people</label>
            <select id="max_people" name="normal[max_people]" class="form-control">
                <?php for($i = 1; $i <= 100; $i++){ ?>
                <option value="{{ $i }}" @if(@($additionalTariffData['normal']->max_people) && $i == $additionalTariffData['normal']->max_people) selected @endif>{{ $i }}</option>
                <?php } ?>
            </select>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-4">
        <div class="form-group">
            <label for="no_of_rooms" class="control-label">Number of rooms available</label>
            <select id="no_of_rooms" name="normal[no_of_rooms]" class="form-control">
                <?php for($i = 1; $i <= 100; $i++){ ?>
                <option value="{{ $i }}" @if(@($additionalTariffData['normal']->no_of_rooms) && $i == $additionalTariffData['normal']->no_of_rooms) selected @endif>{{ $i }}</option>
                <?php } ?>
            </select>
        </div>
    </div>

    <div class="col-md-4">
        <div class="form-group">
            <label for="rate" class="control-label">Rate per night</label>
            <input class="form-control" name="normal[rate]" placeholder="Rate Per Night" value="{{@$additionalTariffData['normal']->rate}}">
        </div>
    </div>

    <div class="col-md-4">
        <div class="form-group">
            <label for="max_people_in_booking" class="control-label">Max people in booking</label>
            <select id="max_people_in_booking" name="normal[max_people_in_booking]" class="form-control">
                <?php for($i = 1; $i <= 100; $i++){ ?>
                <option value="{{ $i }}" @if(@($additionalTariffData['normal']->max_people_in_booking) && $i == $additionalTariffData['normal']->max_people_in_booking) selected @endif>{{ $i }}</option>
                <?php } ?>
            </select>
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