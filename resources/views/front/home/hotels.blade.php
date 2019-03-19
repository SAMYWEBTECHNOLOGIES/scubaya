@extends('front.layouts.master')
@section('page-title')
    Hotels
@endsection
@section('content')
    @include('front._partials.header')

    @php
        use Jenssegers\Agent\Agent as Agent;
        $Agent = new Agent();
    @endphp

    <div id="hotels-context">
        <section id="scu-hotel-search">
            <form type="get" action="{{ route('scubaya::hotel::hotels', ['search']) }}">
                <div class="ui container stackable grid">
                    <div class="four column row">
                        <div class="column">
                            {{--<label>Check In</label>--}}
                            <div class="ui calendar" id="check_in">
                                <div class="ui input left icon">
                                    <i class="calendar icon"></i>
                                    <?php
                                    if(!isset($checkin)) {
                                        $todaysDate =   Carbon\Carbon::now();
                                    }
                                    ?>
                                    <input type="text" required placeholder="Check In" name="checkin" value="@if(isset($checkin)) {{Carbon\Carbon::createFromFormat('d-m-Y', $checkin)}} @else {{ $todaysDate }}@endif">
                                </div>
                            </div>
                        </div>

                        <div class="column">
                            {{--<label>Check Out</label>--}}
                            <div class="ui calendar" id="check_out">
                                <div class="ui input left icon">
                                    <i class="calendar icon"></i>
                                    <?php
                                    if(!isset($checkout)) {
                                        $nextDate   =   Carbon\Carbon::now();
                                        $nextDate   =   $nextDate->addDays(2);
                                    }
                                    ?>
                                    <input type="text" required placeholder="Check Out" name="checkout" value="@if(isset($checkout)) {{$checkout}} @else {{$nextDate}} @endif">
                                </div>
                            </div>
                        </div>

                        <div class="column">
                            {{--<label>Guests</label><br>--}}
                            <select class="ui dropdown" id="no_of_guests" name="guests" required>
                                <option value="">Guests</option>
                                @for($i = 1; $i <= 20; $i++)
                                    <option value="{{ $i }}" @if(!empty($guests) && $guests ==  $i) selected @elseif($i == 1) selected @endif>{{ $i }}</option>
                                @endfor
                            </select>
                        </div>
                        <div class="column">
                            <button type="submit" class="ui primary button" id="hotel_search">Search</button>
                        </div>
                    </div>
                </div>
            </form>
        </section>

        <section id="hotel-section">
            <div id="app">
                <div class="ui container" v-if="items.length">
                    <div class="ui link three stackable cards">
                        <div class=" card" v-for="item in namefilter">
                            <div class="image" v-if="item.image">
                                <image v-bind:src="image_path + '/' + item.merchant_primary_id + '/' + item.id + '-' + item.image" v-bind:alt="item.name"/>
                            </div>

                            <div class="image" v-else>
                                <image src="{{ asset('assets/images/default.png') }}" alt="Scubaya - Your diving buddy"/>
                            </div>

                            <div  class="content" v-bind:data-info="item.name" >
                                <div class="header">
                                    @{{ (item.name).replace(/\w\S*/g, function(txt){return txt.charAt(0).toUpperCase() + txt.substr(1).toLowerCase();})
                                    }}
                                </div>
                                <div class="meta">
                                    @{{ item.location_address }}
                                </div>
                            </div>

                            <div class="extra content">
                                <a class="ui blue button right floated" v-bind:href="link.replace('__hotel_id__/__hotel_name__',item.id+'/'+item.name)">View Details</a>
                                <div class="outer_if_check1" v-if="price.hasOwnProperty(item.merchant_primary_id)">
                                    <div class="inner_if_check2" v-if="price[item.merchant_primary_id].hasOwnProperty(item.id)">
                                        <span> @{{ scubayaSynatx(exchangeRate[item.merchant_primary_id]['symbol'])}}    @{{ (price[item.merchant_primary_id][item.id]) * exchangeRate[item.merchant_primary_id]['rate'] }} Per Night </span>
                                    </div>
                                    <div class="inner_else_check2" v-else>
                                        <span> @{{ scubayaSynatx(exchangeRate[item.merchant_primary_id]['symbol'])}} 0 Per Night</span>
                                    </div>
                                </div>
                                <div class="outer_else_check1" v-else>
                                    <span> @{{ scubayaSynatx(exchangeRate[item.merchant_primary_id]['symbol'])}}  0 Per Night</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="ui container" v-else>
                    <h4 class="text-center">No hotel found !</h4>
                </div>
            </div>
        </section>
    </div>
@endsection

@section('script-extra')
    <script src="{{ asset('assets/front/js/vue.js') }}" type="text/javascript"></script>
    <script type="text/javascript">

        $(document).ready(function () {
            $('.sub-header').sticky({
                context: '#hotels-context'
            });
            var today = new Date();

            $('#check_in').calendar({
                type: 'date',
                minDate: new Date(today.getFullYear(), today.getMonth(), today.getDate() - 0),
                maxDate: new Date(today.getFullYear(), today.getMonth(), today.getDate() + 365),
                formatter: {
                    date: function (date, settings) {
                        if (!date) return '';
                        var day     = date.getDate();
                        var month   = date.getMonth() + 1;
                        var year    = date.getFullYear();
                        return day + '-' + month + '-' + year;
                    }
                }
            });

            $('#check_out').calendar({
                type: 'date',
                minDate: new Date(today.getFullYear(), today.getMonth(), today.getDate() - 0),
                maxDate: new Date(today.getFullYear(), today.getMonth(), today.getDate() + 365),
                formatter: {
                    date: function (date, settings) {
                        if (!date) return '';
                        var day     = date.getDate();
                        var month   = date.getMonth() + 1;
                        var year    = date.getFullYear();
                        return day + '-' + month + '-' + year;
                    }
                }
            });
            $('#no_of_guests').dropdown();
        });

        var app = new Vue({
            el: '#app',
            data: function () {
                return {
                    name_search             :   '{{isset($_GET['name'])?$_GET['name']:''}}',
                    country_search          :   '{{isset($_GET['country'])?$_GET['country']:''}}',
                    items                   :    JSON.parse('{!! $hotelInfo !!}'),
                    image_path              :   '{{asset('assets/images/scubaya/hotel/')}}',
                    price                   :    JSON.parse('{!! $minPrices !!}'),
                    exchangeRate            :    JSON.parse('{!! $exchangeRate !!}'),
                    link                    :   '{!!  route('scubaya::hotel::hotel_details',['__hotel_id__','__hotel_name__']).'?'.http_build_query([
                     'checkin'      =>  isset($_GET['checkin']) ? $_GET['checkin'] : $todaysDate->format('d-m-Y'),
                     'checkout'     =>  isset($_GET['checkout']) ? $_GET['checkout'] : $nextDate->format('d-m-Y'),
                     'guests'       =>  isset($_GET['guests']) ? $_GET['guests'] : 1
                ]) !!}'
                }
            },
            methods: {
                scubayaSynatx: function (html) {
                    var txt = document.createElement("textarea");
                    txt.innerHTML = html;
                    return txt.value;
                },
                convertHashTags: function(str) {
                    return str.replace(/\s+/g, '-').toLowerCase();
                }
            },
            computed: {
                namefilter: function () {
                    var self = this;
                    return this.items.filter(function (hotelInfo) {
                        var a    =   hotelInfo.name.toLowerCase().indexOf(self.name_search.toLowerCase()) >= 0;
                        var b    =   hotelInfo.country.toLowerCase().indexOf(self.country_search.toLowerCase()) >= 0;
                        if(a==1 && b==1){
                            return true;
                        } else {
                            return false;
                        }
                    });
                }
            }
        });
    </script>
@endsection