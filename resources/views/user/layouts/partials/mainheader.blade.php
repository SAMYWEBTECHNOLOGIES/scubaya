<header class="main-header">
    <!-- Logo -->
    <a href="#" class="logo">
        <!-- mini logo for sidebar mini 50x50 pixels -->
        <span class="logo-mini"><b>S</b><span class="scubaya-blue">B</span></span>
        <!-- logo for regular state and mobile devices -->
        <span class="logo-lg"><b>SCUBA</b><span class="scubaya-blue">YA</span></span>
    </a>
    <!-- Header Navbar: style can be found in header.less -->
    <nav class="navbar navbar-static-top">
        <!-- Sidebar toggle button-->
        <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
        </a>

        <div class="navbar-custom-menu">
            <ul class="nav navbar-nav">
                <!-- Messages: style can be found in dropdown.less-->
                {{--<li class="dropdown messages-menu">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                        <i class="fa fa-envelope-o"></i>
                        <span class="label label-success">4</span>
                    </a>
                    <ul class="dropdown-menu">
                        <li class="header">You have 4 messages</li>
                        <li>
                            <!-- inner menu: contains the actual data -->
                            <ul class="menu">
                                <li><!-- start message -->
                                    <a href="#">
                                        <div class="pull-left">
                                            <img src="{{asset('/adminlte/img/user2-160x160.jpg')}}" class="img-circle" alt="User Image">
                                        </div>
                                        <h4>
                                            Support Team
                                            <small><i class="fa fa-clock-o"></i> 5 mins</small>
                                        </h4>
                                        <p>Why not buy a new awesome theme?</p>
                                    </a>
                                </li>
                                <!-- end message -->
                                <li>
                                    <a href="#">
                                        <div class="pull-left">
                                            <img src="{{asset('/adminlte/img/user3-128x128.jpg')}}" class="img-circle" alt="User Image">
                                        </div>
                                        <h4>
                                            AdminLTE Design Team
                                            <small><i class="fa fa-clock-o"></i> 2 hours</small>
                                        </h4>
                                        <p>Why not buy a new awesome theme?</p>
                                    </a>
                                </li>
                                <li>
                                    <a href="#">
                                        <div class="pull-left">
                                            <img src="{{asset('/adminlte/img/user4-128x128.jpg')}}" class="img-circle" alt="User Image">
                                        </div>
                                        <h4>
                                            Developers
                                            <small><i class="fa fa-clock-o"></i> Today</small>
                                        </h4>
                                        <p>Why not buy a new awesome theme?</p>
                                    </a>
                                </li>
                                <li>
                                    <a href="#">
                                        <div class="pull-left">
                                            <img src="{{asset('/adminlte/img/user3-128x128.jpg')}}" class="img-circle" alt="User Image">
                                        </div>
                                        <h4>
                                            Sales Department
                                            <small><i class="fa fa-clock-o"></i> Yesterday</small>
                                        </h4>
                                        <p>Why notdive_log_id buy a new awesome theme?</p>
                                    </a>
                                </li>
                                <li>
                                    <a href="#">
                                        <div class="pull-left">
                                            <img src="{{asset('/adminlte/img/user4-128x128.jpg')}}" class="img-circle" alt="User Image">
                                        </div>
                                        <h4>
                                            Reviewers
                                            <small><i class="fa fa-clock-o"></i> 2 days</small>
                                        </h4>
                                        <p>Why not buy a new awesome theme?</p>
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <li class="footer"><a href="#">See All Messages</a></li>
                    </ul>
                </li>--}}
                <!-- Notifications: style can be found in dropdown.less -->
                <?php
                    $notifications  = json_decode(\App\Scubaya\model\Notification::where('user_id',Auth::id())
                                                    ->orderBy('created_at','desc')
                                                    ->get()
                                      );

                    $count_unread   = 0;

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

                <!-- User Account: style can be found in dropdown.less -->
                <li class="dropdown user user-menu">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                        <img src="{{asset('assets/images/user2-160x160.jpg')}}" class="user-image" alt="User Image">
                        <span class="hidden-xs">{{\Illuminate\Support\Facades\Crypt::decrypt(Auth::user()->first_name)}}</span>
                    </a>
                    <ul class="dropdown-menu">
                        <!-- User image -->
                        <li class="user-header">
                            <img src="{{asset('assets/images/user2-160x160.jpg')}}" class="img-circle" alt="User Image">

                            <p>
                                {{\Illuminate\Support\Facades\Crypt::decrypt(Auth::user()->first_name)}} - Web Developer
                                <small>Member since Nov. 2012</small>
                            </p>
                        </li>
                        <!-- Menu Body -->
                        <li class="user-body">
                            <div class="row">
                                <div class="col-xs-4 text-center">
                                    <a href="#">Followers</a>
                                </div>
                                <div class="col-xs-4 text-center">
                                    <a href="#">Sales</a>
                                </div>
                                <div class="col-xs-4 text-center">
                                    <a href="#">Friends</a>
                                </div>
                            </div>
                            <!-- /.row -->
                        </li>
                        <!-- Menu Footer-->
                        <li class="user-footer">
                            <div class="pull-left">
                                <a href="{{--{{route('scubaya::user::userprofile_edit',[Auth::id()])}}--}}" class="btn btn-default btn-flat">Profile</a>
                            </div>
                            <div class="pull-right">
                                <a href="{{route('scubaya::user::logout')}}" class="btn btn-default btn-flat">Sign out</a>
                            </div>
                        </li>
                    </ul>
                </li>
                <!-- Control Sidebar Toggle Button -->
                <li>
                    <a href="#" data-toggle="control-sidebar"><i class="fa fa-gears"></i></a>
                </li>
            </ul>
        </div>
    </nav>
</header>

<div class="verify-dive">
    <div class="modal fade" id="myModal" role="dialog">
        <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content " id="#sd">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">DIVE DETAILS</h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6">
                        <div>
                            <strong><p>Dive date: </p></strong>
                        </div>
                        <div>
                            <strong><p>At Dive Center:</p> </strong>
                        </div>
                        <div>
                            <strong><p>Dive Site: </p></strong>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div>
                            <p id="dive-date"></p>
                        </div>
                        <div>
                            <p id="dive-center"></p>
                        </div>
                        <div>
                            <p id="dive-site"></p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
    </div>
</div>

<script src="{{asset('js/app.js')}}"></script>
<script src="{{ asset('plugins/bootstrap-notify/bootstrap-notify.min.js') }}" type="text/javascript"></script>
<script>
    var count_unread    = '{{$count_unread}}';
    Pusher.logToConsole = false;
    var pusher = new Pusher('bc8f4d3368fbc0055935', {
        cluster: 'mt1',
        encrypted: true
    });

    var channel = pusher.subscribe('private-App.User.'+'{{Auth::user()->UID}}');

    channel.bind('App\\Events\\VerifyDiveEvent', function(data) {
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

    //As soon as anyone clicks on any LIST ITEM the ajax request is hit towards the back-end
    $(document).on('click','.notification',function(){
        var notification_id = $(this).prop('id');
        if(notification_id && $(this).hasClass('unread')){
            $(".new_notification_count").html(--count_unread > 0 ? count_unread : '');
            var token           =   "{{ csrf_token() }}";
            var url             =   "{{route('scubaya::user::read_notification')}}";

            $.post(url,{notification_id:notification_id,_token:token }, function( status ){
                if((status.notification_status)=='updated'){
                    if(count_unread == 0){
                        ++count_unread;
                    }

                    $("#"+notification_id).parent().css("font-weight","unset");
                    //$("#"+notification_id).removeClass('unread');
                }
            });
        }
    });

    $('.unverify-button').click(function () {
        alert('are you sure that you dont know him');
    });

    $(document).on('click','.verify-dive-button',function(){
        var log_dive     = $(this).data('@#74');
        var message      = $(this).data('@#23');

        var url       = "{{route('scubaya::user::verify_the_dive')}}";
        var token     = "{{csrf_token()}}";

        $.post(url,{log_dive_id:log_dive, _token:token},
            function(status){ console.log(status);
                if(status.log_dive_id[0]){
                    $(".modal-body #message").html(message);
                    $(".modal-body #dive-date").html(status.log_dive_id[0].log_date);
                    $(".modal-body #dive-center").html(status.log_dive_id[0].dive_center);
                    $(".modal-body #dive-site").html(status.log_dive_id[0].dive_site);
                }else{
                    $(".modal-body #dive-date").html('NA');
                    $(".modal-body #dive-center").html('NA');
                    $(".modal-body #dive-site").html('NA');
                }
            });
    });
</script>
