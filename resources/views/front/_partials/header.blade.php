@php

use Jenssegers\Agent\Agent;

$agent = new Agent();

$is_user_logged_in =   Request::hasCookie('scubaya_dive_in');
if($is_user_logged_in){
    $user_id        =   \Illuminate\Support\Facades\Crypt::decrypt($_COOKIE['scubaya_dive_in']) ;
    $user           =   \App\Scubaya\model\User::where('id',$user_id)->first();
}

$cart_items = 0;

$course_count       =   !$is_user_logged_in?(Request::hasCookie('course')?count(unserialize(\Illuminate\Support\Facades\Crypt::decrypt($_COOKIE['course']))) :0 ): \App\Scubaya\model\Cart::where([['user_key',$user_id], ['item_type', 'course'], ['status',CHECKOUT_PENDING]])->count();
$product_count      =   !$is_user_logged_in?(Request::hasCookie('product')?count(unserialize(\Illuminate\Support\Facades\Crypt::decrypt($_COOKIE['product']))) :0 ): \App\Scubaya\model\Cart::where([['user_key',$user_id], ['item_type', 'product'], ['status',CHECKOUT_PENDING]])->count();
$hotel_count        =   !$is_user_logged_in?(Request::hasCookie('hotel')?count(unserialize(\Illuminate\Support\Facades\Crypt::decrypt($_COOKIE['hotel']))) :0 ): \App\Scubaya\model\Cart::where([['user_key', $user_id], ['item_type', 'hotel'], ['status',CHECKOUT_PENDING]])->count();

$cart_items         =   $course_count + $product_count + $hotel_count;
@endphp
@if($agent->isMobile())
    {{--<section>
        <div class="info-message">
            <p>We are not open for registration yet!</p>
        </div>
    </section>--}}
    <section class="sub-header">
        <div id="main" class="ui container">
            <div class="ui fluid menu scubaya-menu">
                <a class="item" href="{{ route('scubaya::home') }}">
                    <img class="logo" alt="Scubaya - your dive buddy" src="{{ asset('assets/images/logo/Scubaya-text-logo-original-white.png')}}"/>
                </a>
                <div class="floating ui green label beta-label">BETA</div>
                <a class="icon right item" id="menu">
                    <i class="big inverted content icon"></i>
                </a>
            </div>

            <div class="ui inverted icon right vertical sidebar menu">
                 <div class="item menu">
                    <a class="item" href="{{ route('scubaya::diveCenters')}}">DiveCenters</a>
                    <a class="item" href="{{ route('scubaya::hotel::hotels') }}">Hotels</a>
                    <a class="item" href="{{ route('scubaya::dive_resort::dive_resorts') }}">DiveResorts</a>
                    <a class="item" href="{{ route('scubaya::liveaboard::liveaboards') }}">LiveAboard</a>
                    <a class="item" href="{{ route('scubaya::destination::destinations')}}">Destinations</a>
                    @if(!$is_user_logged_in)
                        <div class="item"><a href="#" class="scu-signin-btn">Sign In</a></div>
                        <div class="item"><a href="#" class="scu-signup-btn">Sign Up</a></div>
                    @else
                        <div class="item"><a target="_blank" href="{{route('scubaya::user::dashboard')}}"><i class="user icon"></i>{{\Illuminate\Support\Facades\Crypt::decrypt($user->first_name)}}</a></div>
                    @endif
                </div>
            </div>
        </div>
    </section>
@else
    {{--<section>
        <div class="info-message">
            <p>We are not open for registration yet!</p>
        </div>
    </section>--}}
    <section class="ui sticky sub-header">
        <div class="ui container">
            <div class="ui large inverted secondary network menu">
                <div class="item">
                    <div class="scu-logo">
                        <a href="{{ route('scubaya::home') }}">
                            <img alt="Scubaya - your dive buddy" src="{{ asset('assets/images/logo/Scubaya-text-logo-original-white.png')}}" width="110"/>
                        </a>
                        <div class="floating ui green label beta-label">BETA</div>
                    </div>
                </div>

                <div class="item menu">
                    <a class="item" href="{{ route('scubaya::diveCenters') }}">
                        Dive Centers
                    </a>
                    <a class="item" href="{{ route('scubaya::hotel::hotels') }}">
                        Hotels
                    </a>
                    <a class="item" href="{{ route('scubaya::dive_resort::dive_resorts') }}">
                        Dive Resorts
                    </a>
                    <a class="item" href="{{ route('scubaya::liveaboard::liveaboards') }}">
                        LiveAboard
                    </a>
                    <a class="item" href="{{ route('scubaya::destination::destinations') }}">
                        Destinations
                    </a>
                </div>

                <div class="right menu">
                    <div class="item">
                        <a href="{{route('scubaya::checkout::cart')}}">
                            <button class="ui icon cart button">
                                <i class="icon blue shopping cart"></i>
                                <span class="floating ui green label cart-count">{{$cart_items}}</span>
                            </button>
                        </a>
                    </div>

                    @if(!$is_user_logged_in)
                        <div class="item">
                            <a href="#" class="scu-signin-btn">Sign In</a>
                        </div>
                        <div class="item">
                            <a href="#" class="scu-signup-btn">Sign Up</a>
                        </div>
                    @else
                        <div class="item">
                            <a target="_blank" href="{{route('scubaya::user::dashboard')}}">
                                <i class="user icon"></i>{{\Illuminate\Support\Facades\Crypt::decrypt($user->first_name)}}
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </section>
    <script type="text/javascript">
        $('.ui.dropdown.cart').dropdown();
        var count = parseInt('{{$cart_items}}');
        if (!count) {
            $('.cart-count').hide();
        }
    </script>
@endif