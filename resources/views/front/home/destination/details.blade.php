@extends('front.layouts.master')
@section('page-title')
    Destination
@endsection
@section('content')
    @include('front._partials.header')

    @php
        use Jenssegers\Agent\Agent as Agent;
        $Agent = new Agent();

        $DiveSites              =   array();

        $diveCenterSearchUrl    =   route('scubaya::diveCenters', ['search']);
        $diveCenterSearchUrl    .=  '?country='.str_slug($destinationInfo->country);
    @endphp

    @if(!($Agent->isMobile()))
        <div class="ui fluid container destination-header-image">
            @if($destinationInfo->image)
            <img alt="{{$destinationInfo->name}}" src="{{asset('assets/images/scubaya/destination/'.$destinationInfo->id.'-'.$destinationInfo->image)}}"/>
            @else
                <img alt="Scubaya - Sub Destination" src="{{ asset('assets/front/images/dive-site.jpg') }}">
            @endif
            <div class="destination-overlay">
                <h1 class="text-center">{{ ucwords($destinationInfo->name) }}</h1>
                <h3 class ="text-center">{{ ucwords($destinationInfo->sub_name) }}</h3>
            </div>
        </div>
    @endif
    <section id="destination-detail-context">
        <div class="destination-detail-section">
            <div class="ui stackable grid">
                <div class="eleven wide column">
                    <h1>Diving In {{ ucwords($destinationInfo->name) }} <span class="geo-area">{{ ucwords($destinationInfo->geographical_area) }}</span></h1>

                    <div class="about-destination-description description_less">
                        <h4>About scuba diving in</h4>
                        <p>{{$destinationInfo->long_description}}</p>
                    </div>
                </div>
                <div class="five wide column">
                    @if($diveCenters)
                        <h3><b>{{ $diveCenters }} dive centers</b></h3>
                    @endif

                    @if($hotels)
                        <h3><b>{{ $hotels }} dive hotels</b></h3>
                    @endif
                    <h3><b>100 dive sites</b></h3>
                    <a href="{{ $diveCenterSearchUrl }}">
                        <button class="ui button search-dive-center-button">Search Dive Centers</button>
                    </a>
                </div>
            </div>
        </div>
        <hr class="type hrblue">
        <div class="destination-info">
            <div class="ui stackable grid">
                <div class="eleven wide column">
                    @if($destinationInfo->dive_description)
                        <div class="dive-description-destination description_less">
                            <h4>Dive Description</h4>
                            <p>{{$destinationInfo->dive_description}}</p>
                        </div>
                    @endif
                    @if($destinationInfo->tourist_description)
                        <div class="tourist-description-destination description_less">
                            <h4>Tourists Description</h4>
                            <p>{{$destinationInfo->tourist_description}}</p>
                        </div>
                    @endif
                    @php
                        $exposure_season = json_decode($destinationInfo->exposure_season);
                    @endphp
                    @if(isset($exposure_season->no_exposure) && $exposure_season->no_exposure==0)
                        @php
                            $startExposure      =   key($exposure_season->info->from);
                            $endExposure        =   key($exposure_season->info->till);
                            $exposureInfo       =   array();
                            $exposureSeasonInfo =   (array)($exposure_season->info);

                            foreach($exposureSeasonInfo as $info => $value) {
                                foreach($value as $exposure_key => $exposure_val)
                                {
                                    $key                = (int)$exposure_key;
                                    $exposureInfo[$key] =  $exposure_val;
                                }
                            }

                        @endphp
                        <div class="exposure-season">
                            <h4>Exposure period to cyclone and (rain) stroms</h4>
                            <table class="ui unstackable table">
                                <thead>
                                <tr><th></th>
                                    <th width="8.33%">Jan</th>
                                    <th width="8.33%">Feb</th>
                                    <th width="8.33%">March</th>
                                    <th width="8.33%">Apr</th>
                                    <th width="8.33%">May</th>
                                    <th width="8.33%">June</th>
                                    <th width="8.33%">July</th>
                                    <th width="8.33%">Aug</th>
                                    <th width="8.33%">Sept</th>
                                    <th width="8.33%">Oct</th>
                                    <th width="8.33%">Nov</th>
                                    <th width="8.33%">Dec</th>
                                </tr></thead>
                                <tbody>
                                <tr class="active">
                                    <td><i class="fa fa-chevron-circle-right" aria-hidden="true"></i></td>
                                    @if($exposure_season->whole_year == 0)
                                        @for($i = 1; $i <=12; $i++)
                                            <td>
                                               @if(isset($exposureInfo) && $i >= $startExposure && $i <= $endExposure)
                                                    @if(isset($exposureInfo[$i]) && $exposureInfo[$i] == 0)
                                                        <div id="full-square"></div>
                                                    @elseif(isset($exposureInfo[$i]) && $exposureInfo[$i] == 1)
                                                        @if($i == $startExposure)
                                                            <div id="half-square">
                                                                <div id="triangle-topleft"></div>
                                                            </div>
                                                        @else
                                                            <div id="half-square">
                                                                <div id="triangle-topright"></div>
                                                            </div>
                                                        @endif
                                                    @else
                                                        <div id="full-square"></div>
                                                    @endif
                                               @else
                                               @endif
                                            </td>
                                        @endfor
                                    @else
                                        @for($i = 1; $i <= 12; $i++)
                                            <td><div id="full-square"></div></td>
                                        @endfor
                                    @endif
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    @endif
                    @php
                        $rain_season = json_decode($destinationInfo->rain_season);
                    @endphp
                    @if(isset($rain_season->no_rain) && $rain_season->no_rain==0)
                        @php
                            $startRain         =   key($rain_season->info->from);
                            $endRain           =   key($rain_season->info->till);
                            $rainInfo          =   array();
                            $rainSeasonInfo    =   (array)($rain_season->info);

                            foreach($rainSeasonInfo as $rain_info => $rain_value) {
                                foreach($rain_value as $rain_key => $rain_val)
                                {
                                    $key                = (int)$rain_key;
                                    $rainInfo[$key]     =  $rain_val;
                                }
                            }
                        @endphp
                        <div class="rain-season">
                            <h4>Rain season</h4>
                            <table class="ui unstackable table">
                                <thead>
                                <tr><th></th>
                                    <th width="8.33%">Jan</th>
                                    <th width="8.33%">Feb</th>
                                    <th width="8.33%">March</th>
                                    <th width="8.33%">Apr</th>
                                    <th width="8.33%">May</th>
                                    <th width="8.33%">June</th>
                                    <th width="8.33%">July</th>
                                    <th width="8.33%">Aug</th>
                                    <th width="8.33%">Sept</th>
                                    <th width="8.33%">Oct</th>
                                    <th width="8.33%">Nov</th>
                                    <th width="8.33%">Dec</th>
                                </tr></thead>
                                <tbody>
                                <tr class="active">
                                    <td><i class="fa fa-chevron-circle-right" aria-hidden="true"></i></td>
                                    @if($rain_season->whole_year == 0)
                                        @for($i = 1; $i <=12; $i++)
                                            <td>
                                                @if(isset($rainInfo) && $i >= $startRain && $i <= $endRain)
                                                    @if(isset($rainInfo[$i]) && $rainInfo[$i] == 0)
                                                        <div id="full-square"></div>
                                                    @elseif(isset($rainInfo[$i]) && $rainInfo[$i] == 1)
                                                        @if($i == $startRain)
                                                            <div id="half-square">
                                                                <div id="triangle-topleft"></div>
                                                            </div>
                                                        @else
                                                            <div id="half-square">
                                                                <div id="triangle-topright"></div>
                                                            </div>
                                                        @endif
                                                    @else
                                                        <div id="full-square"></div>
                                                    @endif
                                                @else
                                                @endif
                                            </td>
                                        @endfor
                                    @else
                                        @for($i = 1; $i <= 12; $i++)
                                            <td><div id="full-square"></div></td>
                                        @endfor
                                    @endif
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    @endif
                    @php
                        $dive_season = json_decode($destinationInfo->season);
                    @endphp
                    @if(isset($dive_season->no_dive_season) && $dive_season->no_dive_season == 0)
                        @php
                            $startDive         =   key($dive_season->info->from);
                            $endDive           =   key($dive_season->info->till);
                            $diveInfo          =   array();
                            $diveSeasonInfo    =   (array)($dive_season->info);

                            foreach($diveSeasonInfo as $dive_info => $dive_value) {
                                foreach($dive_value as $dive_key => $dive_val)
                                {
                                    $key            = (int)$dive_key;
                                    $diveInfo[$key] =  $dive_val;
                                }
                            }

                        @endphp
                        <div class="dive-season">
                            <h4>Dive season</h4>
                            <table class="ui unstackable table">
                                <thead>
                                <tr><th></th>
                                    <th width="8.33%">Jan</th>
                                    <th width="8.33%">Feb</th>
                                    <th width="8.33%">March</th>
                                    <th width="8.33%">Apr</th>
                                    <th width="8.33%">May</th>
                                    <th width="8.33%">June</th>
                                    <th width="8.33%">July</th>
                                    <th width="8.33%">Aug</th>
                                    <th width="8.33%">Sept</th>
                                    <th width="8.33%">Oct</th>
                                    <th width="8.33%">Nov</th>
                                    <th width="8.33%">Dec</th>
                                </tr></thead>
                                <tbody>
                                <tr class="active">
                                    <td><i class="fa fa-chevron-circle-right" aria-hidden="true"></i></td>
                                    @if($dive_season->whole_year == 0)
                                        @for($i = 1; $i <=12; $i++)
                                            <td>
                                                @if(isset($diveInfo) && $i >= $startDive && $i <= $endDive)
                                                    @if(isset($diveInfo[$i]) && $diveInfo[$i] == 0)
                                                        <div id="full-square"></div>
                                                    @elseif(isset($diveInfo[$i]) && $diveInfo[$i] == 1)
                                                        @if($i == $startDive)
                                                            <div id="half-square">
                                                                <div id="triangle-topleft"></div>
                                                            </div>
                                                        @else
                                                            <div id="half-square">
                                                                <div id="triangle-topright"></div>
                                                            </div>
                                                        @endif
                                                    @else
                                                        <div id="full-square"></div>
                                                    @endif
                                                @else
                                                @endif
                                            </td>
                                        @endfor
                                    @else
                                        @for($i = 1; $i <= 12; $i++)
                                            <td><div id="full-square"></div></td>
                                        @endfor
                                    @endif
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
                <div class="five wide column">
                    <div class= "ui raised segments side-segment">
                        @if($destinationInfo->rs_floor && $destinationInfo->rs_floor != 'none')
                            <div class="ui two column grid">
                                <div class ="column">
                                    <h4>Reef/ Sea Floor</h4>
                                </div>
                                <div class="column">
                                    @for($i = 0; $i < $destinationInfo->rs_floor; $i++)
                                        <i class="fa fa-star checked" aria-hidden="true"></i>
                                    @endfor
                                </div>
                            </div>
                        @endif
                        @if($destinationInfo->macro && $destinationInfo->macro != 'none')
                            <div class="ui two column grid">
                                <div class="column">
                                    <h4>Macro</h4>
                                </div>
                                <div class="column">
                                    @for($i = 0; $i < $destinationInfo->macro; $i++)
                                    <i class="fa fa-star checked" aria-hidden="true"></i>
                                    @endfor
                                </div>
                            </div>
                        @endif
                        @if($destinationInfo->pelagic && $destinationInfo->pelagic != 'none')
                            <div class="ui two column grid">
                                <div class ="column">
                                    <h4>Pelagic</h4>
                                </div>
                                <div class="column">
                                    @for ($i = 0; $i < $destinationInfo->pelagic; $i++)
                                        <i class="fa fa-star checked" aria-hidden="true"></i>
                                    @endfor
                                </div>
                            </div>
                        @endif
                        @if($destinationInfo->wreck && $destinationInfo->wreck != 'none')
                            <div class="ui two column grid">
                                <div class ="column">
                                    <h4>Wreck</h4>
                                </div>
                                <div class="column">
                                    @for ($i = 0; $i < $destinationInfo->wreck; $i++)
                                        <i class="fa fa-star checked" aria-hidden="true"></i>
                                    @endfor
                                </div>
                            </div>
                        @endif
                        @if($destinationInfo->climate)
                            <div class="ui two column grid">
                                <div class ="column">
                                    <h4>Climate</h4>
                                </div>
                                <div class="column">
                                    {{$destinationInfo->climate}}
                                </div>
                            </div>
                        @endif
                        @if($languageSpoken)
                            <h4>Languages Spoken</h4>
                            <div class="sixteen wide column">
                                @foreach($languageSpoken as $language)
                                    <i class="{{$language->country_code}} flag fontsize_socialicon"></i>
                                @endforeach
                            </div>
                        @endif
                        @if($destinationInfo->country_currency)
                            <h4>Country Currency</h4>
                            <div class="sixteen wide column">
                                {{$destinationInfo->country_currency}}
                            </div>
                        @endif
                        @php
                            $acceptedCurrencies = json_decode($destinationInfo->accepted_currency);
                        @endphp
                        @if($acceptedCurrencies)
                            <h4>Accepted Currencies</h4>
                            <div class="sixteen wide column">
                                @foreach($acceptedCurrencies as $currency)
                                    <span class = "margin-currency">{{$currency}}</span>
                                @endforeach
                            </div>
                        @endif
                        @if($destinationInfo->voltage)
                            <h4>Electric voltage</h4>
                            <div class="sixteen wide column">
                                {{$destinationInfo->voltage}}
                            </div>
                        @endif
                        @if($destinationInfo->region)
                            <h4>Region</h4>
                            <div class="sixteen wide column">
                                {{$destinationInfo->region}}
                            </div>
                        @endif
                        @if($destinationInfo->capital_wikipedia)
                            <h4>Capital</h4>
                            <div class="sixteen wide column">
                                {{$destinationInfo->capital_wikipedia}}
                            </div>
                        @endif
                        @if($destinationInfo->religion)
                            <h4>Religion</h4>
                            <div class="sixteen wide column">
                                {{$destinationInfo->religion}}
                            </div>
                        @endif
                        @if($destinationInfo->population)
                            <h4>Population</h4>
                            <div class="sixteen wide column">
                                {{$destinationInfo->population}}
                            </div>
                        @endif
                        @if($destinationInfo->hdi_rank)
                            <h4>HDI rank</h4>
                            <div class="sixteen wide column">
                                {{$destinationInfo->hdi_rank}}
                            </div>
                        @endif
                        @if($destinationInfo->phone_code)
                            <h4>Phone code</h4>
                            <div class="sixteen wide column">
                                {{$destinationInfo->phone_code}}
                            </div>
                        @endif
                        @if($destinationInfo->tipping)
                            <h4>Country tipping</h4>
                                <div class="sixteen wide column">
                                {{$destinationInfo->tipping}}
                            </div>
                        @endif
                        @php
                            $visaCountries = json_decode($destinationInfo->visa_countries);
                        @endphp
                        @if($visaCountries!= null)
                            <h4>Formality eg.visum requried</h4>
                            <div class="visa-countries">
                                <div class="ui mini horizontal list">
                                    @foreach($visaCountries as $visa_country)
                                        <div class="item">
                                            <i class="{{$visa_country}} flag ui avatar image"></i>
                                            <div class="content">
                                                yes
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        <hr class="type hrblue">

        <div class="destination-gallery-section">
            @if(count($subDestinations)>0)
            <h2 class = "text-center">Best Diving Destinations in {{ ucwords($destinationInfo->name) }}</h2>
            <div class=" ui stackable cards centered sub-destinations @if(count($subDestinations) > 2) three @endif">
                @foreach($subDestinations as $subDestination)
                    <div class="card">
                        <div class="image">
                            <img alt="{{$subDestination->name}}" src="{{asset('assets/images/scubaya/destination/'.$subDestination->id.'-'.$subDestination->image)}}"/>
                        </div>
                        <div class="content">
                            <a class="header" href = "{{ route('scubaya::destination::sub_destination_details',[$subDestination->id, str_slug($subDestination->name)]) }}">{{ ucwords($subDestination->name) }}</a>
                        </div>
                    </div>
                @endforeach
            </div>
            @endif
            @php
                $gallery = json_decode($destinationInfo->images);
            @endphp
            @if($gallery)
            <div class="destination_gallery">
                <div class="ui stackable two column grid">
                    <div class="column">
                        <div class="slider-main-image" id = "slide_index">
                            @foreach($gallery as $image)
                                <div class="item">
                                    <img src = "{{asset('assets/images/scubaya/destination/gallery/destination-'.$destinationInfo->id.'/'.$image)}}" width=" 100%"/>
                                </div>
                            @endforeach
                        </div>
                    </div>
                    <div class="column">
                        <div class="slider-navigation ui three doubling cards">
                            @foreach($gallery as $image)
                                     <a href="#" class ="card">
                                        <div class="image">
                                            <img width = "150px" src ="{{asset('assets/images/scubaya/destination/gallery/destination-'.$destinationInfo->id.'/'.$image)}}"/>
                                        </div>
                                    </a>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
            @endif
            <div class="temp-table">
                <table class="ui unstackable table">
                    <tr>
                        <th></th>
                        <th>Jan</th>
                        <th>Feb</th>
                        <th>Mar</th>
                        <th>Apr</th>
                        <th>May</th>
                        <th>June</th>
                        <th>July</th>
                        <th>Aug</th>
                        <th>Sept</th>
                        <th>Oct</th>
                        <th>Nov</th>
                        <th>Dec</th>
                    </tr>
                    <tbody>
                    <tr class="active">
                        @php $water_temp = json_decode($destinationInfo->water_temp); @endphp
                        <td>Water Temp</td>
                        <td>{{$water_temp->jan}}</td>
                        <td>{{$water_temp->feb}}</td>
                        <td>{{$water_temp->mar}}</td>
                        <td>{{$water_temp->apr}}</td>
                        <td>{{$water_temp->may}}</td>
                        <td>{{$water_temp->june}}</td>
                        <td>{{$water_temp->july}}</td>
                        <td>{{$water_temp->aug}}</td>
                        <td>{{$water_temp->sept}}</td>
                        <td>{{$water_temp->oct}}</td>
                        <td>{{$water_temp->nov}}</td>
                        <td>{{$water_temp->dec}}</td>
                    </tr>
                    <tr>
                        @php $max_air_temp = json_decode($destinationInfo->max_air_temp); @endphp
                        <td>Max Air Temp</td>
                        <td>{{$max_air_temp->jan}}</td>
                        <td>{{$max_air_temp->feb}}</td>
                        <td>{{$max_air_temp->mar}}</td>
                        <td>{{$max_air_temp->apr}}</td>
                        <td>{{$max_air_temp->may}}</td>
                        <td>{{$max_air_temp->june}}</td>
                        <td>{{$max_air_temp->july}}</td>
                        <td>{{$max_air_temp->aug}}</td>
                        <td>{{$max_air_temp->sept}}</td>
                        <td>{{$max_air_temp->oct}}</td>
                        <td>{{$max_air_temp->nov}}</td>
                        <td>{{$max_air_temp->dec}}</td>
                    </tr>
                    <tr class = "active">
                        @php $min_air_temp = json_decode($destinationInfo->min_air_temp); @endphp
                        <td>Min Air Temp</td>
                        <td>{{$min_air_temp->jan}}</td>
                        <td>{{$min_air_temp->feb}}</td>
                        <td>{{$min_air_temp->mar}}</td>
                        <td>{{$min_air_temp->apr}}</td>
                        <td>{{$min_air_temp->may}}</td>
                        <td>{{$min_air_temp->june}}</td>
                        <td>{{$min_air_temp->july}}</td>
                        <td>{{$min_air_temp->aug}}</td>
                        <td>{{$min_air_temp->sept}}</td>
                        <td>{{$min_air_temp->oct}}</td>
                        <td>{{$min_air_temp->nov}}</td>
                        <td>{{$min_air_temp->dec}}</td>
                    </tr>
                    <tr>
                        @php $rainfall = json_decode($destinationInfo->rain_fall_temp); @endphp
                        <td>Rainfall</td>
                        <td>{{$rainfall->jan}}</td>
                        <td>{{$rainfall->feb}}</td>
                        <td>{{$rainfall->mar}}</td>
                        <td>{{$rainfall->apr}}</td>
                        <td>{{$rainfall->may}}</td>
                        <td>{{$rainfall->june}}</td>
                        <td>{{$rainfall->july}}</td>
                        <td>{{$rainfall->aug}}</td>
                        <td>{{$rainfall->sept}}</td>
                        <td>{{$rainfall->oct}}</td>
                        <td>{{$rainfall->nov}}</td>
                        <td>{{$rainfall->dec}}</td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
        @if($destinationInfo->destination_tips)
        <hr class="type hrblue">
            @php
                $destinationTips = json_decode($destinationInfo->destination_tips);
            @endphp
            <div class ="destination-tips-info">
                <h2 class ="blue">Destination Tips Info</h2>
                <div class="ui stackable two column grid">
                    @foreach($destinationTips as $tip => $value)
                        <div class="column description_less">
                            <h4>{{ ucwords($value->label) }}</h4>
                            <p>{{$value->description}}</p>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        @if(array_key_exists('diveSitesWithinRadius', $diveSites) && count($diveSites['diveSitesWithinRadius']) > 0
            || array_key_exists('diveSitesNotInRadius', $diveSites) && count($diveSites['diveSitesNotInRadius']) > 0)
            @php
                if(count($diveSites['diveSitesWithinRadius'])) {
                    $random     =   array_random($diveSites['diveSitesWithinRadius']);
                    $DiveSites  =   $diveSites['diveSitesWithinRadius'];
                } elseif (count($diveSites['diveSitesNotInRadius'])) {
                    $random     =   array_random($diveSites['diveSitesNotInRadius']);
                    $DiveSites  =   $diveSites['diveSitesNotInRadius'];
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
                        @foreach($DiveSites as $site)
                            <div class="ui two column grid">
                                <div class="column">{{ ucwords($site['name']) }}</div>
                                <div class="column">{{ ucwords($site['country']) }}</div>
                            </div>
                            <hr>
                        @endforeach
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
    </section>

    @php
        $clientGeoInfo =   geoip($_SERVER['REMOTE_ADDR']);
    @endphp

    <script type="text/javascript">
        jQuery.fn.shorten = function(settings) {
            var config = {
                showChars : 400,
                ellipsesText : " ",
                moreText : "Read More <i class='fa fa-chevron-down' style='font-size:15px'></i>",
                lessText : "Read Less <i class='fa fa-chevron-up' style='font-size:15px'></i>"
            };

            if (settings) {
                jQuery.extend(config, settings);
            }

            jQuery('body').on('click','.morelink', function() {
                var his = jQuery(this);
                if (his.hasClass('less')) {
                    his.removeClass('less');
                    his.html(config.moreText);
                } else {
                    his.addClass('less');
                    his.html(config.lessText);
                }
                his.parent().prev().toggle();
                his.prev().toggle();

                return false;
            });

            return this.each(function() {
                var his = jQuery(this);

                var content = his.html();
                if (content.length > config.showChars) {
                    var c = content.substr(0, config.showChars);
                    var h = content.substr(config.showChars , content.length - config.showChars);
                    var html = c + '<span class="moreellipses">' + config.ellipsesText + '&nbsp;</span><span class="morecontent"><span>' + h + '</span>&nbsp;&nbsp;<a href="javascript://nop/" class="morelink">' + config.moreText + '</a></span>';
                    his.html(html);
                    jQuery(".morecontent span").hide();
                }
            });
        };

        jQuery('.description_less p').shorten();

        $(document).ready(function () {

            $('.sub-header').sticky({
                context: '#destination-detail-context'
            });

            $('.slider-main-image').slick({
                slidesToShow: 1,
                //slidesToScroll: 1,
                arrows: false,
                fade: true,
                autoplay: false,
            });

            var slider = $('#slide_index');

            $(".slider-navigation  a").click(function(e){
                e.preventDefault();
                slideIndex = $(this).index();
                slider.slick('slickGoTo', slideIndex);
            });

            diveSites();
        });

        function toTitleCase(str){
            return str.replace(/\w\S*/g, function(txt){return txt.charAt(0).toUpperCase() + txt.substr(1).toLowerCase();});
        }

        function convertToSlug(Text)
        {
            return Text
                .toLowerCase()
                .replace(/[^\w ]+/g,'')
                .replace(/ +/g,'-')
                ;
        }

        var diveSitesWithinRadius  =   JSON.parse('{!! json_encode($diveSites['diveSitesWithinRadius']) !!}');
        var diveSitesNotInRadius   =   JSON.parse('{!! json_encode($diveSites['diveSitesNotInRadius']) !!}');

        function diveSites() {
            var diveSites   =   '';

            if(diveSitesWithinRadius) {
                diveSites   =   diveSitesWithinRadius;
            }

            if(diveSitesNotInRadius) {
                diveSites   =   diveSitesNotInRadius;
            }

            var markers = {
                "lat": "{{ !empty($destinationInfo)  ? $destinationInfo->latitude  : $clientGeoInfo['lat'] }}",
                "lng": "{{ !empty($destinationInfo) ? $destinationInfo->longitude : $clientGeoInfo['lon'] }}"
            };

            var curLocation = [markers.lat, markers.lng];

            var map     = L.map('dive-sites').setView(curLocation, 4);

            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; <a href="https://www.scubaya.com">Scubaya.com</a>'
            }).addTo(map);

            map.on('click', function() {
                if (map.scrollWheelZoom.enabled()) {
                    map.scrollWheelZoom.disable();
                }
                else {
                    map.scrollWheelZoom.enable();
                }
            });

            $.each(diveSites, function(k, v){
                var markers  =    L.marker([v.lat, v.long]);
                markers.addTo(map);

                var src =   '{{ asset('assets/images/scubaya/dive_sites/--IMAGE--') }}';

                if(v.image) {
                    src     =   src.replace('--IMAGE--', v.key+'-'+v.image);
                } else {
                    src     =   '{{ asset('images/--IMAGE--') }}';
                    src     =   src.replace('--IMAGE--', 'dive-site.jpg');
                }

                markers.bindPopup(
                    '<div class="info-content">' +
                    '<img width="224" src="'+src+'">'+
                    '<div class="content">'+
                    '<a onclick="diveSite('+v.key+')">'+toTitleCase(v.name)+'</a>' +
                    '</div>'+
                    '</div>'
                );
            });

            map.on('zoomend', function(){
                if (map.getZoom() > 7) {
                    $.each(diveSites, function(k, v){
                        var markers  =    L.marker([v.lat, v.long]);
                        markers.addTo(map);

                        var src =   '{{ asset('assets/images/scubaya/dive_sites/--IMAGE--') }}';

                        if(v.image) {
                            src     =   src.replace('--IMAGE--', v.key+'-'+v.image);
                        } else {
                            src     =   '{{ asset('images/--IMAGE--') }}';
                            src     =   src.replace('--IMAGE--', 'dive-site.jpg');
                        }

                        markers.bindPopup(
                            '<div class="info-content">' +
                            '<img width="224" src="'+src+'">'+
                            '<div class="content">'+
                            '<a onclick="diveSite('+v.key+')">'+toTitleCase(v.name)+'</a>' +
                            '</div>'+
                            '</div>'
                        );
                    });
                }
            });
        }

        function diveSite(key) {
            var token  =   '{{ csrf_token() }}';

            $.ajax({
                url:  '{{ route('scubaya::diveSiteById') }}',
                method:'post',
                data: {key:key, _token:token},
                dataType:"JSON",
                success: function (response) {
                    var src =   '{{ asset('assets/images/scubaya/dive_sites/--IMAGE--') }}';

                    if(response.image) {
                        src     =   src.replace('--IMAGE--', response.id+'-'+response.image);
                    } else {
                        src     =   '{{ asset('images/--IMAGE--') }}';
                        src     =   src.replace('--IMAGE--', 'dive-site.jpg');
                    }

                    $('#dive-site-name').text(toTitleCase(response.name));
                    $('#dive-site-image').attr('src', src);
                    $('#dive-level').text(toTitleCase(response.diver_level));
                    $('#max-depth').text(response.max_depth+'m');
                    $('#avg-depth').text(response.avg_depth+'m');
                    $('#current').text(toTitleCase(response.current));
                    $('#max-visibility').text(response.max_visibility+'m');
                    $('#avg-visibility').text(response.avg_visibility+'m');
                },
                error : function (error) {
                    console.log(error);
                }
            });
        }
        </script>
@endsection

