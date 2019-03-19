@extends('merchant.layouts.app')
@section('title', 'Rooms')
@section('breadcrumb')
    <li><a href="#">Hotel</a></li>
    <li ><a href="{{route('scubaya::merchant::hotels',[Auth::id()])}}">Manage Hotel</a></li>
    <li class="active"><span>All Rooms</span></li>
@endsection

@section('content')
    @include('merchant.layouts.mainheader')

    <section id="rooms_section" class="padding-20">
        <div class="box-body">
            <div class="panel panel-default">
                <div class="panel-body">
                    <h3 class="text-center">{{ $hotelName->name }}</h3>
                </div>
            </div>

            @if(count($rooms) > 0)
                @foreach($rooms as $room)
                    <?php
                    $sno     =   1;
                    $tariffs =   \App\Scubaya\model\RoomPricing::where('room_id', $room->id)->get();
                    ?>
                    <div class="panel panel-default">
                        <div class="panel-body">
                            <h3 class="panel-title room-types-title">{{ $room->name }}</h3>

                            <div class="col-md-2">
                                <img src="{{asset('assets/images/scubaya/rooms/'.$room->id.'-'.$room->room_image)}}" class="img-responsive" alt="{{$room->name}}">
                            </div>

                            <div class="col-md-3">
                                <div>
                                    <span class="text-muted">Room Type: </span>{{$room->type}}
                                </div>

                                <div>
                                    <span class="text-muted">Room Number: </span>{{$room->number or '-'}}
                                </div>

                                <div>
                                    <span class="text-muted">Floor: </span>{{$room->floor or '-'}}
                                </div>

                                <div>
                                    <span class="text-muted">Max People: </span>{{$room->max_people or '-'}}
                                </div>

                                {{--<div>
                                    <span class="text-muted">Quantity: </span>{{$room->quantity or '-'}}
                                </div>--}}
                            </div>

                            <div class="col-md-4">
                                 <div>
                                     <span class="text-muted">Features: </span>
                                 </div>
                                <?php
                                    $count    =   0;
                                    $features =   json_decode($room->features);
                                ?>
                                @if(count($features) > 0)
                                    <ul class="ul-padding-start15">
                                        @foreach($features as $feature)
                                            @if($count < 5)
                                                <li>{{ $feature }}</li>
                                            @endif
                                            <?php $count++; ?>
                                        @endforeach
                                    </ul>
                                @else
                                    {{ '-' }}
                                @endif
                            </div>

                            <div class="col-md-1">
                                <div>
                                    <a href="{{route('scubaya::merchant::edit_room', [Auth::id(), $room->hotel_id, $room->id])}}" data-toggle="tooltip" title="Edit Hotel">
                                        <button type="button" class="button-blue btn btn-primary">
                                            <i class="fa fa-pencil"></i>
                                        </button>
                                    </a>
                                </div>

                                <div class="margin-top-10">
                                    <form method="post" action="{{route('scubaya::merchant::delete_room', [Auth::id(), $room->hotel_id, $room->id])}}">
                                        {{ csrf_field() }}
                                        <button type="button" class="btn btn-danger delete">
                                            <i class="fa fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </div>

                            @if(count($tariffs) > 0)
                            <div class="col-md-2">
                                <button type="button" id="show_tariffs" class="button-blue btn btn-primary" onclick="show_extra_options(this)" data-mname={{$room->id}}>
                                    Tariff Options <i class="fa fa-caret-down" aria-hidden="true"></i>
                                </button>
                            </div>
                            @endif
                        </div>
                    </div>

                    @if(count($tariffs) > 0)
                    <div class="panel panel-default tariff-panel scubaya-tariff-options" id="tariff{{$room->id}}">
                        <div class="panel-body">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="col-xs-3 col-md-3 col-sm-3">
                                        <p class="text-center"><b>S.No.</b></p>
                                    </div>
                                    <div class="col-xs-6 col-md-6 col-sm-6">
                                        <p class="text-center"><b>Title</b></p>
                                    </div>
                                    <div class="col-xs-3 col-md-3 col-sm-3">
                                        <p class="text-center"><b>Action</b></p>
                                    </div>
                                </div>
                            </div>
                            @foreach($tariffs as $tariff)
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="col-xs-3 col-md-3 col-sm-3">
                                            <p class="text-center">{{ $sno++ }}</p>
                                        </div>
                                        <div class="col-xs-6 col-md-6 col-sm-6">
                                            <p class="text-center">{{ $tariff->tariff_title }}</p>
                                        </div>
                                        <div class="col-xs-3 col-md-3 col-sm-3 text-center">
                                            <div class="inline-flex">
                                                <a href="{{ route('scubaya::merchant::edit_tariff', [Auth::id(), $room->hotel_id, $tariff->id]) }}">
                                                    <button type="button" class="button-blue padding3-8 btn btn-primary">
                                                        <i class="fa fa-pencil"></i>
                                                    </button>
                                                </a>

                                                <form class="padding-left5" method="post" action="{{ route('scubaya::merchant::delete_tariff', [Auth::id(), $room->hotel_id, $tariff->id]) }}">
                                                    {{ csrf_field() }}
                                                    <button type="button" class="padding3-8 btn btn-danger delete">
                                                        <i class="fa fa-trash"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                    @endif
                @endforeach
                <div class="pagination">
                    {{ $rooms->links() }}
                </div>
            @else
                <div class="panel panel-default" >
                    <div class="panel-body">
                        <h4 class="text-center">No Rooms Available</h4>
                    </div>
                </div>
            @endif
        </div>
    </section>

    @include('merchant.layouts.delete_script')

    <script type="text/javascript">
        function show_extra_options(elem){
            var mname = jQuery(elem).data('mname');
            jQuery('#tariff'+mname).slideToggle("slow");
        }
    </script>
@endsection
