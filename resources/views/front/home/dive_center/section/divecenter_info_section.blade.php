<hr class="type hrblue">
<section class="dive-info-section">
    <div class = "ui stackable grid">
        <div class="eleven wide column">
            <h1>Info</h1>
            <div class ="ui stackable two column grid">
                <div class="column">
                    <div id="dive_center_map" style="height: 187px"></div>
                </div>
                <div class="column">
                    @if($consumerAddress)
                    <p>{{ $consumerAddress }}</p>
                    @endif

					<h3 style= "margin:0px !important">Languages Spoken</h3>
						
                    @if($language)
                        @foreach($language as $language_spoken)
                            @if($language_spoken)
                                <i class="{{$language_spoken->country_code}} flag"></i>
                            @endif
                        @endforeach
                    @else
                    @endif

                    <div class ="social-icon_info">
                        @if($diveCentersObject->twitter_url)
                        <a href="{{ $diveCentersObject->twitter_url }}"><i class="fa fa-twitter fontsize_socialicon"></i></a>
                        @endif

                        @if($diveCentersObject->facebook_url)
                        <a href="{{ $diveCentersObject->facebook_url }}"><i class="fa fa-facebook fontsize_socialicon"></i></a>
                        @endif

                        @if($diveCentersObject->instagram_url)
                        <a href="{{ $diveCentersObject->instagram_url }}"><i class="fa fa-instagram fontsize_socialicon"></i></a>
                        @endif
                    </div>
                </div>
            </div>

            @php
                $activities =   json_decode($diveCentersObject->activities);
            @endphp

            @if($activities)
                <h2>Activities</h2>
                @foreach($activities as $activity)
                    @php
                        $activityInfo  =   \App\Scubaya\model\Activity::where('id', $activity)->first();
                    @endphp
                    <div class="activity-icons" data-inverted=""
                         data-tooltip="{{ ucwords($activityInfo->name) }}"
                         data-position="top center">
                        <img width="25"
                             src="{{ asset('assets/images/scubaya/activities/'.$activityInfo->id.'-'.$activityInfo->icon) }}"
                             alt="{{ $activityInfo->name }}"
                        >
                    </div>
                @endforeach
            @endif

            @php
                $nonDivingActivities =   json_decode($diveCentersObject->non_diving_activities);
            @endphp

            @if($nonDivingActivities)
                <h2>Non Diving Activities</h2>
                @foreach($nonDivingActivities as $activity)
                    @php
                        $activityInfo  =   \App\Scubaya\model\Activity::where('id', $activity)->first();
                    @endphp
                    <div class="activity-icons" data-inverted=""
                         data-tooltip="{{ ucwords($activityInfo->name) }}"
                         data-position="top center">
                        <img width="25"
                             src="{{ asset('assets/images/scubaya/activities/'.$activityInfo->id.'-'.$activityInfo->icon) }}"
                             alt="{{ $activityInfo->name }}"
                        >
                    </div>
                @endforeach
            @endif

            @if(count($instructorInfo))
                <hr>
                <div class = "ui grid">
                    <div class="ui eight wide column">Instructors & dive masters</div>
                    <div class="ui eight wide column">{{ $instructorInfo['isInstructorDiveMaster'] ? 'Both' : 'Instructor only' }}</div>
                </div>

                @if($instructorInfo['noOfDiveGuides'])
                <hr>
                <div class = "ui grid">
                    <div class="ui eight wide column">Number of dive guides</div>
                    <div class="ui eight wide column">{{ $instructorInfo['noOfDiveGuides'] }}</div>
                </div>
                @endif
            @endif

            @php
                $infrastructure =   json_decode($diveCentersObject->infrastructure);
            @endphp

            @if($infrastructure)
                <hr>
                <div class = "ui grid">
                    <div class="ui eight wide column">Infrastructure</div>
                    <div class="ui eight wide column">
                    @foreach($infrastructure as $structure)
                        @php
                            $infrastructureInfo =   \App\Scubaya\model\Infrastructure::where('id', $structure)->first();
                        @endphp
                        <div class="infrastructure-icons" data-inverted=""
                             data-tooltip="{{ ucwords($infrastructureInfo->name) }}"
                             data-position="top center">
                            <img width="25"
                                 src="{{ asset('assets/images/scubaya/infrastructure/'.$infrastructureInfo->id.'-'.$infrastructureInfo->icon) }}"
                                 alt="{{ $infrastructureInfo->name }}"
                            >
                        </div>
                    @endforeach
                    </div>
                </div>
            @endif
            <hr>

            @if($distanceToDecoChamber)
            <div class = "ui grid">
                <div class="ui eight wide column">Distance to nearest deco chamber</div>
                <div class="ui eight wide column">{{ $distanceToDecoChamber }} Km</div>
            </div>
            <hr>
            @endif
        </div>
        <div class = "five wide column">
            <div class = "ui raised segments side-segment">
                @if($member_affiliations)
                    <h4>Active member of:</h4>
                    <div class="ui four column grid">
                        @foreach($member_affiliations as $affiliation)
                            @php
                                if(isset($affiliation->aid))
                                $affiliationInfo    =   \App\Scubaya\model\Affiliations::where('id', $affiliation->aid)->first();
                            @endphp

                            @if(isset($affiliationInfo))
                                <div class="column">
                                    <div data-inverted=""
                                         data-tooltip="{{ ucwords($affiliationInfo->name) }}"
                                         data-position="top center">
                                         <img src="{{asset('assets/images/scubaya/affiliations/'.$affiliationInfo->id.'-'.$affiliationInfo->image)}}"
                                         class="ui borderRadius tiny image affiliation-logos"/>
                                    </div>
                                </div>
                            @endif
                        @endforeach
                    </div>
                @endif

                @if($affiliations)
                    <h4>Affiliations</h4>
                    <div class="ui four column grid">
                        @foreach($affiliations as $affiliation)
                            @php
                                $affiliationInfo    =   \App\Scubaya\model\Affiliations::where('id', $affiliation)->first();
                            @endphp
                            <div class="column">
                                <div data-inverted=""
                                     data-tooltip="{{ ucwords($affiliationInfo->name) }}"
                                     data-position="top center">
                                     <img src="{{asset('assets/images/scubaya/affiliations/'.$affiliationInfo->id.'-'.$affiliationInfo->image)}}"
                                          class="ui borderRadius tiny image affiliation-logos"/>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif

                @php
                    $time           =   [];
                    $days           =   [
                        'su'    =>  'Sunday',
                        'mo'    =>  'Monday',
                        'tu'    =>  'Tuesday',
                        'we'    =>  'Wednesday',
                        'th'    =>  'Thursday',
                        'fr'    =>  'Friday',
                        'sa'    =>  'Saturday',
                    ];

                    $openingDays    =   (array)json_decode($diveCentersObject->opening_days);

                    if(array_key_exists('day', $openingDays) && array_key_exists('time', $openingDays)) {
                        $time       =   (array)$openingDays['time'];
                    }
                @endphp
                <div class="ui stackable grid">
                    <div class="sixteen wide column">
                        <h4>Opening Days</h4>
                        @if(count($time))
                            <div class="ui list">
                                @if($time['all'] &&  !is_null($time['all']->from) && !is_null($time['all']->to))
                                    <div class="item">
                                        <img src="{{asset('assets/images/web-application-icons/checked.png')}}"
                                             style="width:15px;height:15px"/> All Days From {{ $time['all']->from }} to {{ $time['all']->to }}
                                    </div>
                                @else
                                    @foreach($time as $key => $value)
                                        @if($key != "all" && !is_null($value->from) && !is_null($value->to))
                                            <div class="item"><img
                                                        src="{{asset('assets/images/web-application-icons/checked.png')}}"
                                                        style="width:15px;height:15px"/> {{ $days[$key].' '.'From '.$value->from.' to '.$value->to }}
                                            </div>
                                        @endif
                                    @endforeach
                                @endif
                            </div>
                        @endif
                    </div>

                    @php
                        $specialities   =   json_decode($diveCentersObject->specialities);
                    @endphp

                    @if($specialities)
                        <div class="sixteen wide column">
                            <h4>Specialties</h4>
                            @foreach($specialities as $speciality)
                                @php
                                    $specialityInfo =   \App\Scubaya\model\Speciality::where('id', $speciality)->first();
                                @endphp

                                @if($specialityInfo)
                                    <div class="item">
                                        <img src="{{asset('assets/images/web-application-icons/checked.png')}}"
                                             style="width:15px;height:15px"/> {{ ucwords($specialityInfo->name) }}
                                    </div>
                                @endif
                            @endforeach
                        </div>
                    @endif

                    @php
                        $facilities =   json_decode($diveCentersObject->facilities);
                    @endphp

                    @if($facilities)
                        <div class="sixteen wide column dive-center-facilities">
                            <h4>Dive center facilities</h4>
                            @foreach($facilities as $facility)
                                @php
                                    $facilityInfo   =   \App\Scubaya\model\Facility::where('id', $facility)->first();
                                @endphp

                                @if($facilityInfo)
                                    <div data-inverted=""
                                         data-tooltip="{{ ucwords($facilityInfo->name) }}"
                                         data-position="top center">
                                        <img width="40"
                                             src="{{ asset('assets/images/scubaya/dive_center_facility/'.$facilityInfo->id.'-'.$facilityInfo->icon) }}"
                                             alt="{{ $facilityInfo->name }}"
                                        >
                                    </div>
                                @endif
                            @endforeach
                        </div>
                    @endif

                    @php
                        $groups =   json_decode($diveCentersObject->groups);
                    @endphp

                    @if(isset($groups) && !is_null($groups->explanation))
                        <div class="sixteen wide column">
                            <h4>Groups</h4>
                            <p>{{ $groups->explanation }}</p>
                        </div>
                    @endif

                    @if($diveCentersObject->distance_from_sea)
                        <div class="sixteen wide column">
                            <h4>Distance from the sea</h4>
                            <p>{{ $diveCentersObject->distance_from_sea }}</p>
                        </div>
                    @endif

                    @if($payment_methods)
                    <div class="sixteen wide column payment_methods">
                        <h4>Payment methods</h4>
                        @foreach($payment_methods as $method)
                            @php
                                $methodInfo =   \App\Scubaya\model\PaymentMethod::where('id', $method)->first();
                            @endphp

                            <div data-inverted=""
                                 data-tooltip="{{ ucwords($methodInfo->name) }}"
                                 data-position="top center">
                                <img width="40"
                                     src="{{ asset('assets/images/scubaya/payment_methods/'.$methodInfo->id.'-'.$methodInfo->icon) }}"
                                     alt="{{ $methodInfo->name }}"
                                >
                            </div>
                        @endforeach
                        {{--<p>
                            <i class="fa fa-cc-visa" style="font-size:24px"></i>
                            <i class="fa fa-cc-paypal" style="font-size:24px"></i>
                            <i class="fa fa-cc-stripe" style="font-size:24px"></i>
                            <i class="fa fa-cc-jcb" style="font-size:24px"></i>
                            <i class="fa fa-cc-discover" style="font-size:24px"></i>
                            <i class="fa fa-cc-diners-club" style="font-size:24px"></i>
                            <i class="fa fa-cc-mastercard" style="font-size:24px"></i>
                            <i class="fa fa-credit-card" style="font-size:24px"></i>
                        </p>--}}
                    </div>
                    @endif

                    @if($diveCentersObject->cancellation_policy)
                        <div class="sixteen wide column">
                            <h4>Cancellation policy</h4>
                            <a href="#" class="cancellation-policy">Click Here</a>
                        </div>

                        <div class="ui small modal cancellation-policy-modal">
                            <div class="header">Cancellation Policy</div>
                            <div class="content">
                                <p>{{ $diveCentersObject->cancellation_policy }}</p>
                            </div>
                            <div class="actions">
                                <div class="ui cancel button">Close</div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</section>