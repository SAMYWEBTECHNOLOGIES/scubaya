@if(array_key_exists('diveSitesWithinRadius', $diveSites) && count($diveSites['diveSitesWithinRadius']) > 0
|| array_key_exists('diveSitesNotInRadius', $diveSites) && count($diveSites['diveSitesNotInRadius']) > 0)
@php
    if(count($diveSites['diveSitesWithinRadius'])) {
        $random     =   array_random($diveSites['diveSitesWithinRadius']);
    } elseif (count($diveSites['diveSitesNotInRadius'])) {
        $random     =   array_random($diveSites['diveSitesNotInRadius']);
    }

    $diveSite   =   \App\Scubaya\model\DiveSite::where('id', $random['key'])->first();
@endphp
<hr class="type hrblue">
<section class="dive_sites_section">
    <h2 class="blue">Dives Site</h2>
    <div class = "ui grid ">
        <div class="sixteen wide column">
            <div id="dive-sites" style="height: 400px;"></div>
        </div>
    </div>

    <div class="ui two column stackable grid">
        <div class="column">
            <h2 class="blue">Types of dives</h2>
            @php
                $diveSiteType   =   (array)json_decode($diveSite->type);
            @endphp

            @if($diveSiteType)
                @foreach($diveSiteType as $type)
                     <hr>
                    {{ ucwords($type) }} Dive
                    <br>
                @endforeach
            @endif

            @if($diveCentersObject->discovery_dives || $diveCentersObject->fun_dives || $diveCentersObject->other_dives)
            <h2 class = blue>Dives offered by the dive center</h2>

            @if($diveCentersObject->discovery_dives)
            @php
                $discoveryDives =   (array)json_decode($diveCentersObject->discovery_dives);
            @endphp
            <hr>
            <div class="ui two column grid">
                <div class="column">Discovery dives:</div>
                <div class="column">
                    @foreach($discoveryDives as $dive)
                        {{ ucwords($dive) }}
                        <br>
                    @endforeach
                </div>
            </div>
            @endif

            @if($diveCentersObject->fun_dives)
            @php
                $funDives =   (array)json_decode($diveCentersObject->fun_dives);
            @endphp
            <hr>
            <div class="ui two column grid">
                <div class="column">Fun dives:</div>
                <div class="column">
                    @foreach($funDives as $dive)
                        {{ ucwords($dive) }}
                        <br>
                    @endforeach
                </div>
            </div>
            @endif

            @if($diveCentersObject->other_dives)
            @php
                $otherDives =   (array)json_decode($diveCentersObject->other_dives);
            @endphp
            <hr>
            <div class="ui two column grid">
                <div class="column">Other dives:</div>
                <div class="column">
                    @foreach($otherDives as $dive)
                        {{ ucwords($dive) }}
                        <br>
                    @endforeach
                </div>
            </div>
            @endif
            @endif
        </div>

        <div class="column" style="padding-bottom: 45px;">
            <div class ="ui raised segment divesite_rightpadding">
                @if($diveSite->image)
                    <img class= "divesite_img" id="dive-site-image" src="{{asset('assets/images/scubaya/dive_sites/'.$diveSite->id.'-'.$diveSite->image)}}" alt="Scubaya - Dive site"/>
                @else
                    <img class= "divesite_img" id="dive-site-image" src="{{asset('assets/front/images/dive-site.jpg')}}" alt="Scubaya - Dive site"/>
                @endif
                <div class = "divestie_content">
                    <h2 id="dive-site-name">{{ isset($diveSite->name) ? ucwords($diveSite->name) : '' }}</h2>

                    @if($diveSite->diver_level)
                    <hr>
                    <div class="ui two column grid">
                        <div class="column">Dive level</div>
                        <div class="column right floated left aligned" id="dive-level">{{ ucwords($diveSite->diver_level) }}</div>
                    </div>
                    @endif

                    @if($diveSite->max_depth && $diveSite->avg_depth)
                    <hr>
                    <div class="ui two column grid">
                        <div class="column">Depth</div>
                        <div class="column right floated left aligned">
                            Maximum : <span id="max-depth">{{ $diveSite->max_depth }}m</span>
                            /
                            Average : <span id="avg-depth">{{ $diveSite->avg_depth }}m</span>
                        </div>
                    </div>
                    @endif

                    @if($diveSite->current)
                    <hr>
                    <div class="ui two column grid">
                        <div class="column">Current</div>
                        <div class="column right floated left aligned" id="current">{{ ucwords($diveSite->current) }}</div>
                    </div>
                    @endif

                    @if($diveSite->max_visibility && $diveSite->avg_visibility)
                    <hr>
                    <div class="ui two column grid">
                        <div class="column">Visibility</div>
                        <div class="column right floated left aligned">
                            Maximum : <span id="max-visibility">{{ $diveSite->max_visibility }}m</span>
                            /
                            Average : <span id="avg-visibility">{{ $diveSite->avg_visibility }}m</span>
                        </div>
                    </div>
                    @endif
                    <hr>
                    {{--<div class = "see_location blue">
                        <i class="fa fa-map-marker" style="font-size:24px"></i>
                        <span>see location on the map</span>
                    </div>--}}
                </div>
            </div>
        </div>
    </div>
</section>
@endif