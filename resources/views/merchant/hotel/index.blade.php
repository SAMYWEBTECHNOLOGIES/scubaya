@extends('merchant.layouts.app')
@section('title', 'Hotels')
@section('breadcrumb')
    <li><a href="#">Hotel</a></li>
    <li class="active"><span>Manage Hotel</span></li>
@endsection

@section('content')

    @include('merchant.layouts.mainheader')
    <?php
    $labelStatus    =   [
        MERCHANT_STATUS_PENDING     =>  'label label-warning',
        MERCHANT_STATUS_IN_PROCESS  =>  'label label-info',
        MERCHANT_STATUS_APPROVED    =>  'label label-success',
        MERCHANT_STATUS_REJECTED    =>  'label label-danger'
    ];
    ?>

    <section id="hotel_section" class="padding-20">
        <div>
            <a href="{{ route('scubaya::merchant::create_hotel', [Auth::id()]) }}">
                <button type="button" class="pull-right button-blue btn btn-primary add-new-button">
                    + Add Hotel
                </button>
            </a>
        </div>

        <div class="box-body margin-top-60">
            @if(count($hotelInfo) > 0)
                @foreach($hotelInfo as $info)
                    {{--@php $query   =   \App\Scubaya\model\WebsiteDetails::join('website_details_x_documents','website_details.id','website_details_x_documents.website_detail_id')
                                                            ->select('website_details_x_documents.status','website_details.id')
                                                            ->where('website_details.website_type', HOTEL)
                                                            ->where('website_details.website_id', $info->id)
                                                            ->where('website_details.merchant_key',$authId);

                    if($query->count() > 1){
                        $detail =   $query->where('is_active', 1)->first();
                    } else {
                        $detail =   $query->first();
                    }
                    @endphp--}}

                    <div class="panel panel-default {{--@if(@$detail->status == 'rejected') rejected @endif--}}">
                        <div class="panel-body">
                            {{--@if(@$detail->status == 'rejected')
                                <div id="overlay">
                                    <div id="text">
                                        <h3>Your Documents are rejected.You need to make a new request.</h3>

                                        <button type="button" class="button-blue btn btn-primary" data-toggle="modal" data-target="#verification-form-modal{{$info->id}}">
                                            New Request
                                        </button>
                                    </div>
                                </div>
                            @endif--}}

                            <div class="row room-types-title">
                                <div class="col-md-6">
                                    <h3 class="panel-title">{{ ucwords($info->name) }}</h3>
                                </div>

                                <div class="col-md-6">
                                    <p class="label label-success pull-right status">Approved</p>
                                    {{--@if(@$detail->status)
                                        <p class="{{$labelStatus[$detail->status]}} pull-right status">{{ ucwords(str_replace('_', ' ', $detail->status)) }}</p>
                                    @else
                                        <p class="label label-danger pull-right status">{{ 'Unverified' }}</p>
                                    @endif--}}
                                </div>
                            </div>

                            <div class="col-md-2">
                                <img src="{{asset('assets/images/scubaya/hotel/'.$info->merchant_primary_id.'/'.$info->id.'-'.$info->image)}}" class="img-responsive" alt="{{$info->name}}">
                            </div>

                            <div class="col-md-4">
                                <div>
                                    <span class="text-muted">Address: </span>{{$info->address}}
                                </div>

                                <div>
                                    <span class="text-muted">City: </span>{{$info->city or '-'}}
                                </div>

                                <div>
                                    <span class="text-muted">State: </span>{{$info->state or '-'}}
                                </div>

                                <div>
                                    <span class="text-muted">Country: </span>{{$info->country or '-'}}
                                </div>

                                <div>
                                    <span class="text-muted">Zipcode: </span>{{$info->zipcode or '-'}}
                                </div>
                            </div>

                            {{--@if(@$detail->status)--}}
                            <div class="col-md-3 col-md-offset-1">
                                <div>
                                    <a href="{{ route('scubaya::merchant::all_rooms', [Auth::id(), $info->id]) }}">
                                        <button type="button" class="text-center button-blue btn btn-primary">
                                            All Rooms
                                        </button>
                                    </a>
                                </div>

                                <div class="margin-top-10">
                                    <a href="{{ route('scubaya::merchant::create_room', [Auth::id(), $info->id]) }}">
                                        <button type="button" class="text-center button-blue btn btn-primary">
                                            Create New Room
                                        </button>
                                    </a>
                                </div>

                                <div class="margin-top-10">
                                    <a href="{{ route('scubaya::merchant::create_tariff', [Auth::id(), $info->id]) }}">
                                        <button type="button" class="text-center button-blue btn btn-primary">
                                            Create Tariff
                                        </button>
                                    </a>
                                </div>

                                {{--<div class="margin-top-10">
                                    <a href=@if(@$detail->status == 'rejected') {{'#'}} @else "{{ route('scubaya::merchant::mark_bookings', [Auth::id(), $info->id]) }}" @endif>
                                        <button type="button" class="text-center button-blue btn btn-primary">
                                            Mark Bookings
                                        </button>
                                    </a>
                                </div>--}}
                            </div>

                            <div class="col-md-2">
                                <div>
                                    <a href="{{route('scubaya::merchant::edit_hotel', [Auth::id(), $info->id])}}" data-toggle="tooltip" title="Edit Hotel">
                                        <button type="button" class="button-blue btn btn-primary">
                                            <i class="fa fa-pencil"></i>
                                        </button>
                                    </a>
                                </div>

                                <div class="margin-top-10">
                                    {{--@if(@$detail->status == 'rejected')
                                        <button type="button" class="btn btn-danger">
                                            <i class="fa fa-trash"></i>
                                        </button>
                                    @else--}}
                                    <form method="post" action="{{route('scubaya::merchant::delete_hotel', [Auth::id(), $info->id])}}" id="delete-form">
                                        {{ csrf_field() }}
                                        <button type="button" class="btn btn-danger delete">
                                            <i class="fa fa-trash"></i>
                                        </button>
                                    </form>
                                    {{--@endif--}}
                                </div>
                            </div>
                            {{--@else
                                <div class="col-md-6">
                                    <button type="button" class="button-blue btn btn-primary pull-right" data-toggle="modal" data-target="#verification-form-modal{{$info->id}}">
                                        Get Verified
                                    </button>
                                </div>

                                --}}{{-- verification model --}}{{--
                                @include('merchant.layouts.website_verification.verification_modal', ['route1' => 'scubaya::merchant::hotel::verification',
                                'route2' => 'scubaya::merchant::hotels','website' => $info])
                            @endif--}}
                        </div>
                    </div>

                    {{-- Only show this model when request status is rejected to make new verification request --}}
                    {{--@if(@$detail->status == 'rejected')
                        --}}{{-- verification model --}}{{--
                        @include('merchant.layouts.website_verification.verification_modal', ['route1' => 'scubaya::merchant::hotel::verification',
                               'route2' => 'scubaya::merchant::hotels','website' => $info])
                    @endif--}}
                @endforeach
            @else
                <div class="panel panel-default" >
                    <div class="panel-body">
                        <h4 class="text-center">No Hotels Available</h4>
                    </div>
                </div>
            @endif
        </div>

        <div class="pagination">
            {{ $hotelInfo->links() }}
        </div>
    </section>
    @include('merchant.layouts.delete_script')
@endsection