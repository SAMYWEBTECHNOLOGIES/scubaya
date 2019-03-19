<?php
$count_unread   = 0;

if(Auth::check()){

    if( Auth::user()->is_merchant) {
        $group_ids      =  json_decode(\App\Scubaya\model\Merchant::where('merchant_key',Auth::id())->pluck('group_id')->first());
    }

    if( Auth::user()->is_merchant_user) {
        $group_ids      =  (array)json_decode(\App\Scubaya\model\MerchantUsersRoles::where('user_id',Auth::id())->pluck('group_id')->first());

        $groups         =   array();
        foreach($group_ids as $key => $value) {
            if( $value->confirmed && $value->is_user_active) {
                array_push($groups, $key);
            }
        }

        $group_ids  =   $groups;
    }

    $all_ids        =   [];
    if(!is_null($group_ids) && count($group_ids)){

        $menu_ids       =  \App\Scubaya\model\Group::whereIn('id',$group_ids)->pluck('menu_ids')->toArray();

        $menu_ids       =   array_filter($menu_ids);

        foreach($menu_ids as $menu_id){
            $menu  =   json_decode($menu_id);
            foreach($menu as $id){
                array_push($all_ids,$id);
            }
        }

        foreach($group_ids as $group_id){
            $id     =   (array)$group_id;
            $check  =   \App\Scubaya\model\Group::whereIn('parent_id',$id)->pluck('id');
            $count  =   1;
            while(count($check)){
                $count++;
                $check      =   \App\Scubaya\model\Group::whereIn('parent_id',$check)->pluck('id');
            }
            for($i = 0;$i<$count;$i++){
                $children  =  \App\Scubaya\model\Group::whereIn('parent_id',$id);
                foreach($children->pluck('menu_ids')->toArray() as $menu_id)
                {
                    $menu_id    =   json_decode($menu_id);
                    //$menu_id    =   array_filter($menu_id);
                    if(count($menu_id)){
                        foreach($menu as $id){
                            array_push($all_ids,$id);
                        }
                    }
                }
                $id   =   $children->pluck('id');
            }
        }
    }

    $menus   =   \Illuminate\Support\Facades\DB::table('merchant_menus')->where('parent_id',0)->get();
}
?>

<section id="head-section">
    <div id="merchant_navigation" class="navbar navbar-default navbar-fixed-top">
        @if(Auth::check())
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="#">
                    <img src="{{asset('assets/images/logo/Scubaya-text-logo-original-white.png')}}" width="110px" alt="Welcome to Scubaya" class="image">
                </a>
            </div>
            <div class="navbar-collapse collapse">
                <ul class="nav navbar-nav">
                    @if(!is_null($group_ids) && count($group_ids))
                        @if(count($all_ids))
                            @foreach($menus as $menu)
                                @php $submenus  =   \Illuminate\Support\Facades\DB::table('merchant_menus')->where('parent_id',$menu->id);@endphp
                                @if(array_intersect($all_ids, $submenus->pluck('id')->toArray()))
                                    <li class="dropdown">
                                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">{{$menu->title}} <span class="caret"></span></a>
                                        <ul class="dropdown-menu">
                                            @foreach($submenus->get() as $submenu)
                                                @if(in_array($submenu->id,$all_ids))
                                                    @if($submenu->as == '#')
                                                        <li><a href="{{$submenu->as }}">{{$submenu->title}}</a></li>
                                                    @else
                                                        <li><a href="{{route('scubaya::merchant::'.$submenu->as,[Auth::id()]) }}">{{$submenu->title}}</a></li>
                                                    @endif
                                                @endif
                                            @endforeach
                                        </ul>
                                    </li>
                                @endif
                            @endforeach
                        @endif
                    @else
                        @foreach($menus as $menu)
                            @php $submenus  =   \Illuminate\Support\Facades\DB::table('merchant_menus')->where('parent_id',$menu->id);@endphp
                            <li>
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">{{$menu->title}} <span class="caret"></span></a>
                                <ul class="dropdown-menu">
                                    @foreach($submenus->get() as $submenu)
                                        @php $sub_sub_menus  =   \Illuminate\Support\Facades\DB::table('merchant_menus')->where('parent_id',$submenu->id); @endphp

                                        @if(count($sub_sub_menus->get()))
                                            <li class="dropdown dropdown-submenu"><a href="#" class="dropdown-toggle" data-toggle="dropdown">{{ $submenu->title }}</a>
                                                <ul class="dropdown-menu">
                                                    @foreach($sub_sub_menus->get() as $sub_sub_menu)
                                                        <li><a href="{{$sub_sub_menu->as }}">{{$sub_sub_menu->title}}</a></li>
                                                    @endforeach
                                                </ul>
                                            </li>
                                        @else
                                            @if(\App\Scubaya\model\MerchantDetails::isMerchantActive())
                                                @if($submenu->as == '#')
                                                    <li><a href="{{$submenu->as }}">{{$submenu->title}}</a></li>
                                                @else
                                                    <li><a href="{{route('scubaya::merchant::'.$submenu->as,[Auth::id()]) }}">{{$submenu->title}}</a></li>
                                                @endif
                                            @else
                                                @if(in_array($submenu->as, config('scubaya.merchant_default_menus')))
                                                    <li><a href="{{route('scubaya::merchant::'.$submenu->as,[Auth::id()]) }}">{{$submenu->title}}</a></li>
                                                @else
                                                    <li><a href="#">{{$submenu->title}}</a></li>
                                                @endif
                                            @endif
                                        @endif
                                    @endforeach
                                </ul>
                            </li>

                        @endforeach
                    @endif
                </ul>
                <ul class="nav navbar-nav navbar-right">
                    <?php
                    $notifications  = json_decode(\App\Scubaya\model\Notification::where('user_id',Auth::id())
                                                                                ->orderBy('created_at','desc')
                                                                                ->get()
                    );

                    foreach($notifications as $notification){
                        if(! ($notification->status)){
                            $count_unread++;
                        }
                    }
                    ?>
                    <li class="dropdown notifications-menu">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                            <i class="fa fa-bell-o"></i>
                            <span class="label label-warning new_notification_count">@if($count_unread > 0){{$count_unread}}@endif</span>
                        </a>
                        <ul class="dropdown-menu">
                            <li class="header notification_for_header">You have {{count($notifications)}} notifications</li>
                            <li>
                                <!-- inner menu: contains the actual data -->
                                <ul class="menu" id="notification_menu" style="padding:10px;">
                                    @if($notifications)
                                        @foreach($notifications as $notification)
                                            @php
                                                $params =   json_decode($notification->params);
                                            @endphp
                                            <li style="@if(!$notification->status)font-weight:800;@endif margin-top:5px; border-bottom:2px solid #f4f4f4">
                                                <div style="margin-bottom:10px;border-bottom:none !important;" id="{{$notification->id}}" class="notification @if(!$notification->status) unread @endif">
                                                    <i class="fa fa-users text-aqua"></i> {!! $notification->message !!}
                                                </div>
                                            </li>
                                        @endforeach
                                    @endif
                                </ul>
                            </li>
                            <li class="footer"><a href="#">View all</a></li>
                        </ul>
                    </li>

                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><span class="fa fa-user"></span> {{ ucfirst(\App\Scubaya\model\User::decryptString(Auth::user()->first_name)) }} <span class="caret"></span></a>
                        <ul class="dropdown-menu">
                            <li><a href="{{ route('scubaya::merchant::logout') }}">@lang('merchant_header.logout')</a></li>
                        </ul>
                    </li>
                </ul>
            </div><!--/.nav-collapse -->

        @endif
    </div>
</section>

@if(Auth::check())
<script src="{{asset('js/app.js')}}"></script>
<script src="{{ asset('plugins/bootstrap-notify/bootstrap-notify.min.js') }}" type="text/javascript"></script>
<script>
    $(document).ready(function(){
        $('ul.dropdown-menu [data-toggle=dropdown]').on('click', function(event) {
            event.preventDefault();
            event.stopPropagation();
            $(this).parent().siblings().removeClass('open');
            $(this).parent().toggleClass('open');
        });
    });

    var count_unread    = '{{$count_unread}}';
    Pusher.logToConsole = false;
    var pusher = new Pusher('bc8f4d3368fbc0055935', {
        cluster: 'mt1',
        encrypted: true
    });

    var channel = pusher.subscribe('user-contact-request');

    channel.bind('App\\Events\\UserContactRequest', function(data) {
        alert(data);
        $.notify(data.message, {
            placement: {
                from: 'bottom',
                align: 'right'
            }
        });

        //prepending the list item to notification panel as soon as the notification receieved
        $('#notification_menu').prepend('<li style="font-weight:800; border-bottom:2px solid #f4f4f4; margin-top:5px;">' +
            '                                        <div style="border-bottom:none !important;" id="'+ data.notificationId+'"  class="notification unread">' +
            '                                            <i class="fa fa-users text-aqua"></i>'+ data.message +
            '                                        </div>' +
            '                                        <button class="btn btn-primary verify-dive-button" data-@#74="'+data.log_dive_id+'" data-toggle="modal" data-target="#myModal">Verify</button>' +
            '                                        <button class="btn unverify-button">No Idea!</button>' +
            '                            </li>');

        //changing the notification count as soon as the notification is received
        $('.new_notification_count').html(++count_unread);

        //changing the header title as well
        $('.header.notification_for_header').html('<li class="header notification">You have '+ data.count_of_notification +' notifications</li>'+'<li>');
    });
</script>
@endif