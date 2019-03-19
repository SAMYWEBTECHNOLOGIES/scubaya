@php
    $gears          =   json_decode($diveCentersObject->gears);
    $fillingStation =   json_decode($diveCentersObject->filling_station);
    $nitrox         =   $diveCentersObject->nitrox;
    $noOfBoats      =   \App\Scubaya\model\Boat::where('merchant_key', $diveCentersObject->merchant_key)
                                                ->where('dive_center_id', $diveCentersObject->id)
                                                ->where('is_boat_active', 1)
                                                ->count();
@endphp

@if($gears || $fillingStation || isset($nitrox) || $noOfBoats)
<hr class="type hrblue">
<section class="gear-rental-section">
    <div class="">
        <h2 class="blue">Gear-rental/selling</h2>

        @if($gears)
        <hr>
        <div class = "ui grid">
            <div class ="ui four wide column">
                <h5>Scuba gear:</h5>
            </div>

            <div class = "ui twelve wide column">
                @foreach($gears as $key => $value)
                    {{ ucwords($key).' Gear: ' }}
                    @foreach($value as $v)
                        {{ $v }}
                        @if(!$loop->last)
                            {{ ',' }}
                        @else
                            <br>
                        @endif
                    @endforeach
                @endforeach
            </div>
        </div>
        @endif

        @if($fillingStation)
        <hr>
        <div class = "ui grid">
            <div class ="ui four wide column">
                <h5>Filling Station</h5>
            </div>
            <div class = "ui twelve wide column">
                @foreach($fillingStation as $station)
                    {{ $station.' liters' }}
                    @if(!$loop->last)
                        {{ ',' }}
                    @endif
                @endforeach
            </div>
        </div>
        @endif

        @if(isset($nitrox))
        <hr>
        <div class = "ui grid">
            <div class ="ui four wide column">
                <h5>Nitrox</h5>
            </div>
            <div class = "ui twelve wide column">
                <p>{{ $nitrox ? 'yes' : 'no' }}</p>
            </div>
        </div>
        @endif

        @if($noOfBoats)
        <hr>
        <div class = "ui grid">
            <div class ="ui four wide column">
                <h5>No of boats</h5>
            </div>
            <div class = "ui twelve wide column">
                <p>{{ $noOfBoats }}</p>
            </div>
        </div>
        @endif
    </div>
</section>
@endif