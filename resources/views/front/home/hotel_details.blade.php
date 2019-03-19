@extends('front.layouts.master')
@section('page-title')
    {{$hotelInfo->name}}
@endsection
@section('content')
    @include('front._partials.header')
    @php
         use Jenssegers\Agent\Agent as Agent;
         $Agent = new Agent();

    @endphp

    @if(!($Agent->isMobile()))
        <section class="hotel-image-slider">
            <div class="ui fluid container">
                <ul class="rslides">
                    @if(count(json_decode($hotelInfo->gallery)) > 0)
                        @foreach(json_decode($hotelInfo->gallery) as $gallery)
                            <li><img alt="{{$hotelInfo->name}}" src="{{ asset('assets/images/scubaya/hotel/gallery/'.$hotelInfo->merchant_primary_id.'/hotel-'.$hotelInfo->id.'/'.$gallery) }}" /></li>
                        @endforeach
                    @else
                        <li><img alt="{{$hotelInfo->name}}" src="{{ asset('assets/images/scubaya/hotel/'.$hotelInfo->merchant_primary_id.'/'.$hotelInfo->id.'-'.$hotelInfo->image) }}" /></li>
                    @endif
                </ul>
            </div>
        </section>
    @endif

    <div id="hotel-detail-context">
        <section class="hotel-detail">
            <div class="text-center ui container">
                <h1 class="hotel-name">{{ ucwords($hotelInfo->name) }}</h1>
                <h2>{{$hotelInfo->address}}</h2>
                <p>{!! $hotelInfo->hotel_desc !!}</p>
            </div>
        </section>

        <section class="hotel-rooms paddingTop20">
            <div class="ui container">
                <h2>Rooms</h2>
                @if(count($roomDetails) > 0)
                    @foreach($roomDetails as $detail)
                    <?php
                        $tariff   =   \App\Scubaya\model\RoomPricing::where('merchant_primary_id', $detail->merchant_primary_id)->where('room_id', $detail->id)->get();
                    ?>

                    <div class="ui two column stackable grid">
                        <div class="column">
                            <div class="ui image">
                                <img class="room-image img-responsive" alt="{{$detail->name}}" src="{{asset('assets/images/scubaya/rooms/'.$detail->id.'-'.$detail->room_image)}}"/><br/>
                            </div>
                        </div>


                        <div class="column">
                            <div class="ui segment room-desc">
                                <div class="row">
                                    <div class="row">
                                        <h2 class="ui dividing header">{{ $detail->name }}</h2>
                                        <div class="room-description">{!! $detail->description !!}</div>
                                    </div>

                                    <div class="row room-features">
                                        <div class="ui two column grid">
                                            <div class="column">
                                                <?php
                                                    $features   =   json_decode($detail->features);
                                                    $Features   =   array();

                                                    for($i=0; $i < count($features); $i++)
                                                    {
                                                        $Features[$i]  =   $features[$i];
                                                    }

                                                    for($i=0; $i < ceil(count($Features) / 2); $i++)
                                                    {
                                                ?>
                                                <div class="ui bulleted list">
                                                    <div class="item"><strong>{{$Features[$i]}}</strong></div>
                                                </div>
                                                <?php } ?>
                                            </div>

                                            <div class="column">
                                                <?php
                                                for($i=ceil(count($Features) / 2) ; $i < count($Features); $i++)
                                                {
                                                ?>
                                                <div class="ui bulleted list">
                                                    <div class="item"><strong>{{$Features[$i]}}</strong></div>
                                                </div>
                                                <?php } ?>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row room-tariffs">
                                        @if(count($tariff) > 0)
                                            <button class="tariff-btn ui blue basic right floated button " id="{{$detail->id}}" type="button" onclick="showTariffData(this)">TARIFF</button>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    @if(count($tariff) > 0)
                    <?php
                            $showTariff  =   false;
                            $maxPeople   =   0;
                            $minPeople   =   0;
                    ?>
                    <div class="scubaya-tariff-options" id="tariff{{$detail->id}}">
                        @foreach($tariff as $t)
                            <?php
                                $pricing    =   (array)json_decode($t->additional_tariff_data);

                                if(key($pricing) == 'micro'){
                                    $price_per_night_manually = (array)json_decode($pricing['micro']->price_per_night_manually);
                                    $min_nights_manually      = (array)json_decode($pricing['micro']->min_nights_manually);

                                    if(isset($_GET['checkin']) && isset($_GET['checkout'])) {
                                        if($pricing['micro']->min_people <= $_GET['guests']) {
                                            $maxPeople  =   $pricing['micro']->max_people;
                                            $minPeople  =   $pricing['micro']->min_people;
                                            $checkIn    =   strtotime($_GET['checkin']);
                                            $checkOut   =   strtotime($_GET['checkout']);

                                            $daysDifference  =   date_diff(date_create($_GET['checkin']), date_create($_GET['checkout']));
                                            $daysDifference  =   $daysDifference->format('%a') ;
                                            $roomPrices      =   array();
                                            $totalPrice      =   0;

                                            for($i = 0; $i <= $daysDifference; $i++) {

                                                $checkin =   strtotime('+'.$i.'days', $checkIn);

                                                foreach($price_per_night_manually as $key => $value)
                                                {
                                                    if ($key == $checkin)
                                                        $roomPrices[$i]     =   [
                                                            $key    =>  $value
                                                        ];
                                                }
                                            }

                                            foreach($min_nights_manually as $key => $value)
                                            {
                                                if ($key == $checkIn)
                                                    $minNights =  $value;
                                            }

                                            if($minNights == ( (int)$daysDifference) || $minNights > ( (int)$daysDifference)) {
                                                foreach ($roomPrices as $rprices) {
                                                    foreach ($rprices as $key => $value) {
                                                        if($key == $checkIn) {
                                                            $prices     =   ( (int)$daysDifference ) * $value;
                                                        }
                                                    }
                                                }
                                            }

                                            if(((int)$daysDifference ) > $minNights) {
                                                foreach ($roomPrices as $rprices) {
                                                    foreach ($rprices as $key => $value) {
                                                        if($key ==  $checkIn) {
                                                            $price =   $value;
                                                        }
                                                    }
                                                }

                                                $prices         =   $minNights * $price;
                                                $startDate      =   strtotime('+'.$minNights.'days', $checkIn);

                                                $remainingDays  =   ( (int)$daysDifference ) - $minNights;

                                                for($i = 0 ; $i < $remainingDays; $i++) {
                                                    $date   =   strtotime('+'.$i.'days', $startDate);

                                                    foreach ($roomPrices as $rprices) {
                                                        foreach ($rprices as $key => $value) {
                                                            if($key ==  $date) {
                                                                $prices +=   $value;
                                                            }
                                                        }
                                                    }
                                                }
                                            }

                                            if($pricing['micro']->ignore_pppn) {
                                                $totalPrice                     =   $prices;
                                                $tariffPricingLabel['micro']    =   'Per Night';
                                            } else {
                                                $totalPrice                     =   $prices;
                                                $tariffPricingLabel['micro']    =   'Per Person/Night';
                                            }

                                            $showTariff                     =   true;
                                        }
                                    } else {

                                        // calculate merchant request time
                                        $today  = date('Y-m-d', $_SERVER['REQUEST_TIME']);
                                        $today  = explode('-', $today);

                                        /* mktime(hour, minute, second, month, day, year) */
                                        $epoch  = mktime(0, 0, 0, $today[1], $today[2], $today[0]);

                                        foreach($price_per_night_manually as $key => $value)
                                        {
                                            if ($key == $epoch)
                                                $totalPrice =  $value;
                                        }

                                        $showTariff                     =   true;
                                    }
                                }

                                if(key($pricing) == 'normal'){

                                    /* To check global per person per night option */
                                    $pricingSetting  =   \App\Scubaya\model\RoomPricingSettings::where('merchant_primary_id', $detail->merchant_primary_id)
                                                                                                ->first(['currency']);
                                    $pricingSetting  =   json_decode($pricingSetting->currency);

                                    /* If Price per person per night option is set to yes globally
                                     * then include person and night in price calculation
                                     * else include nights only.
                                     */
                                    if(isset($_GET['checkin']) && isset($_GET['checkout']) && isset($_GET['guests'])) {
                                        if($pricing['normal']->min_people <= $_GET['guests']) {
                                            $maxPeople       =   $pricing['normal']->max_people;
                                            $minPeople       =   $pricing['normal']->min_people;
                                            $daysDifference  =   date_diff(date_create($_GET['checkin']), date_create($_GET['checkout']));
                                            $daysDifference  =   (int)($daysDifference->format('%a'));

                                            if($pricingSetting->prices_pppn) {
                                                $totalPrice                         =   $pricing['normal']->rate * $daysDifference;
                                                $tariffPricingLabel['normal']       =   'Per Person/Night';
                                            } else {
                                                $totalPrice                         =   $pricing['normal']->rate * $daysDifference;
                                                $tariffPricingLabel['normal']       =   'Per Night';
                                            }

                                            $showTariff  =   true;
                                        }
                                    }
                                }

                                if(key($pricing) == 'advance'){
                                    if(isset($_GET['checkin']) && isset($_GET['checkout']) && isset($_GET['guests'])) {
                                        $daysDifference  =   date_diff(date_create($_GET['checkin']), date_create($_GET['checkout']));
                                        $daysDifference  =   (int)($daysDifference->format('%a'));

                                        if($daysDifference >= $pricing['advance']->min_days && $daysDifference <= $pricing['advance']->max_days) {
                                            if($_GET['guests'] >= $pricing['advance']->min_people) {

                                                $maxPeople  =   $pricing['advance']->max_people;
                                                $minPeople  =   $pricing['advance']->min_people;

                                                $validFrom  =    strtotime(DateTime::createFromFormat('m-d-Y', $pricing['advance']->valid_from)->format('d-m-Y'));
                                                $validTo    =    strtotime(DateTime::createFromFormat('m-d-Y', $pricing['advance']->valid_to)->format('d-m-Y'));

                                                if(strtotime($_GET['checkin']) >= $validFrom && strtotime($_GET['checkout']) <= $validTo) {

                                                    if($pricing['advance']->ignore_pppn) {
                                                        $totalPrice                         =   $pricing['advance']->rate * $daysDifference;
                                                        $tariffPricingLabel['advance']      =   'Per Night';
                                                    } else {
                                                        $totalPrice                         =   $pricing['advance']->rate * $daysDifference;
                                                        $tariffPricingLabel['advance']      =   'Per Person/Night';
                                                    }

                                                    $showTariff  =   true;
                                                }
                                            }
                                        }
                                    }
                                }
                            ?>

                            @if($showTariff)
                                <div class="ui column tariff-description">
                                    <h2><strong>{{ ucfirst($t->tariff_title) }}</strong></h2>
                                    <p>{!!$t->tariff_description!!}</p>
                                </div>
                                <div class="ui two column grid room-price-section">
                                    <div class="column text-center right floated">
                                        <div class="ui celled grid">
                                            <div class="row">
                                                <div class="four wide column">
                                                    <i class="users icon"></i>
                                                    <select class="ui dropdown" name="no_of_persons">
                                                        @for($i = $minPeople; $i <= $maxPeople; $i++)
                                                            <option value="{{ $i }}">{{ $i }}</option>
                                                        @endfor
                                                    </select>
                                                </div>

                                                <div class="six wide column">
                                                    <h2 class="blue"><strong>{{@$exchangeRate[$detail->merchant_primary_id]['symbol']}}{{((int)$totalPrice) * $exchangeRate[$detail->merchant_primary_id]['rate']}}</strong></h2>
                                                    <span class="tariff-pricing-label">  {{ $tariffPricingLabel[key($pricing)] }} </span>
                                                </div>

                                                <div class="six wide column">
                                                    <button class="ui blue button add-to-trip" id={{$t->id}}
                                                            data-price="{{((int)$totalPrice)}}"
                                                            type="button">Add To Trip
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @if (!($loop->last))
                                    <hr/>
                                @endif
                            @endif
                            <?php $showTariff   =   false; ?>
                        @endforeach
                    </div>
                    @endif
                    @endforeach
                @else
                    <div class="ui raised segment">No Rooms Available</div>
                @endif
            </div>
        </section>

        <section class="hotel-policies">
            <div class="ui container">
                <h2 class="header">Policies</h2>
                <div class="ui raised segment">
                    <?php $policies =   (array)json_decode($hotelInfo->hotel_policies); ?>
                    @if(count($policies) > 0)
                        @foreach($policies as $key => $value)
                            @if(!empty($value))
                                <div class="ui column">
                                    <div class="ui form">
                                        <label>{{ucwords(str_replace('_',' ',$key))}} :</label>&nbsp;{{$value}}
                                    </div>
                                </div>
                            @endif
                        @endforeach
                    @else
                        <p>No Policies Available.</p>
                    @endif
                </div>
            </div>
        </section>
    </div>
@endsection

@section('script-extra')
    {{--semantic alert js and css--}}
    <link rel="stylesheet" type="text/css" href="{{asset('plugins/semanctic-alert-ui/Semantic-UI-Alert.css')}}">
    <script type="text/javascript" src="{{asset('plugins/semanctic-alert-ui/Semantic-UI-Alert.js')}}"></script>

    <script  type="text/javascript">
          @php
                  $is_user_logged_in  =   Request::hasCookie('scubaya_dive_in');
                  $cart_items         =   0;

                  if($is_user_logged_in) {
                     $user_id            =   \Illuminate\Support\Facades\Crypt::decrypt($_COOKIE['scubaya_dive_in']);
                  }

                  $course_count       =   !$is_user_logged_in?(Request::hasCookie('course')?count(unserialize(\Illuminate\Support\Facades\Crypt::decrypt($_COOKIE['course']))) :0 ): \App\Scubaya\model\Cart::where([['user_key', $user_id], ['item_type', 'course'], ['status',CHECKOUT_PENDING]])->count();
                  $product_count      =   !$is_user_logged_in?(Request::hasCookie('product')?count(unserialize(\Illuminate\Support\Facades\Crypt::decrypt($_COOKIE['product']))) :0 ): \App\Scubaya\model\Cart::where([['user_key', $user_id], ['item_type', 'product'],['status',CHECKOUT_PENDING]])->count();
                  $hotel_count        =   !$is_user_logged_in?(Request::hasCookie('hotel')?count(unserialize(\Illuminate\Support\Facades\Crypt::decrypt($_COOKIE['hotel']))) :0 ): \App\Scubaya\model\Cart::where([['user_key', $user_id], ['item_type', 'hotel'],['status',CHECKOUT_PENDING]])->count();
                  $cart_items         =   $course_count + $product_count + $hotel_count;

          @endphp

          function showTariffData(data)
          {
             $('#tariff'+ data.id).slideToggle("slow");
          }

          var total_items_in_cart = parseInt('{{$cart_items}}'); console.log(total_items_in_cart);

          function showNotification(data){
              $.uiAlert({
                  textHead: '',
                  text: data,
                  bgcolor: '#48bbd1',
                  textcolor: '#fff',
                  position: 'top-right',
                  icon: 'shopping cart',
                  time: 1
              });
          }

          $(document).ready(function(){
              $(".rslides").responsiveSlides();

              $(".room-features").mCustomScrollbar({
                  axis:"y",
                  theme:"rounded-dark"
              });

              $('.sub-header').sticky({
                  context: '#hotel-detail-context'
              });

              $('.ui.dropdown').dropdown();
          });

          $('.add-to-trip').click(function (e) {
              let checkIn         =   '{{ isset($_GET['checkin']) ? $_GET['checkin'] : '' }}';
              let checkOut        =   '{{ isset($_GET['checkout']) ? $_GET['checkout'] : '' }}';
              let no_of_persons   =   $(this).parent().parent().closest('div').find('select').val();

              let price           =   $(this).data('price');

              let url             =   '{{route('scubaya::checkout::cart::add_to_cart')}}';
              let _token          =   '{{csrf_token()}}';

              $.post(url, {type:'hotel', tariff_id: this.id, check_in: checkIn, check_out:checkOut, no_of_persons: no_of_persons, price:price, _token: _token}, function (status) {
                  if (!status.already) {
                      $('.cart-count').show();
                      $('.cart-count').html('' + ++total_items_in_cart + '');
                  }
                  showNotification(status.status)
              });
          });
    </script>
@endsection