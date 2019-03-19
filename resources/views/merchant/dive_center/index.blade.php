@extends('merchant.layouts.app')
@section('title', 'Dive Center')
@section('breadcrumb')
    <li><a href="#">Dive Center</a></li>
    <li class="active"><span>Manage Dive Centers</span></li>
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


    <section id="dive_center_section" class="padding-20">
        <div>
            <a href="{{ route('scubaya::merchant::dive_center::create_dive_center', [Auth::id()]) }}">
                <button type="button" class="pull-right button-blue btn btn-primary">
                    + Add Dive Center
                </button>
            </a>
        </div>

        <div class="box-body margin-top-60">
            @if(count($diveCenters) > 0)
                @foreach($diveCenters as $diveCenter)
                    {{--@php $query   =   \App\Scubaya\model\WebsiteDetails::join('website_details_x_documents','website_details.id','website_details_x_documents.website_detail_id')
                                                            ->select('website_details_x_documents.status','website_details.id')
                                                            ->where('website_details.website_type', DIVE_CENTER)
                                                            ->where('website_details.website_id', $diveCenter->id)
                                                            ->where('website_details.merchant_key',$authId);

                    if($query->count() > 1){
                        $detail =   $query->where('is_active', 1)->first();
                    } else {
                        $detail =   $query->first();
                    }

                    @endphp--}}

                    <div class="panel panel-default {{--@if(@$detail->status == 'rejected') rejected @endif--}}">
                        <div class="panel-body" >
                            {{--@if(@$detail->status == 'rejected')
                                <div id="overlay">
                                    <div id="text">
                                        <h3>Your Documents are rejected.You need to make a new request.</h3>

                                        <button type="button" class="button-blue btn btn-primary" data-toggle="modal" data-target="#verification-form-modal{{$diveCenter->id}}">
                                            New Request
                                        </button>
                                    </div>
                                </div>
                            @endif--}}

                            <div class="row room-types-title">
                                <div class="col-md-6">
                                    <h3 class="panel-title">{{ ucwords($diveCenter->name) }}</h3>
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
                                <img src="{{asset('assets/images/scubaya/dive_center/'.$diveCenter->merchant_key.'/'.$diveCenter->id.'-'.$diveCenter->image)}}" class="img-responsive" alt="{{$diveCenter->name}}">
                            </div>

                            <div class="col-md-4">
                                <div>
                                    <span class="text-muted">Address: </span>{{$diveCenter->address}}
                                </div>

                                <div>
                                    <span class="text-muted">City: </span>{{$diveCenter->city or '-'}}
                                </div>

                                <div>
                                    <span class="text-muted">State: </span>{{$diveCenter->state or '-'}}
                                </div>

                                <div>
                                    <span class="text-muted">Country: </span>{{$diveCenter->country or '-'}}
                                </div>

                                <div>
                                    <span class="text-muted">Zipcode: </span>{{$diveCenter->zipcode or '-'}}
                                </div>
                            </div>

                            {{--@if(@$detail->status)--}}
                            <div class="col-md-2">
                                <div>
                                    <a href="{{ route('scubaya::merchant::instructor', [Auth::id(), $diveCenter->id]) }}">
                                        <button type="button" class="text-center button-blue btn btn-primary">
                                            Instructors
                                        </button>
                                    </a>
                                </div>

                                <div class="margin-top-10">
                                    <a href="{{ route('scubaya::merchant::dive_center::boats', [Auth::id(), $diveCenter->id]) }}">
                                        <button type="button" class="text-center button-blue btn btn-primary">
                                            Boats
                                        </button>
                                    </a>
                                </div>
                            </div>

                            <div class="col-md-2">
                                <div>
                                    <a href="{{ route('scubaya::merchant::dive_center::dive_day_planning', [Auth::id(), $diveCenter->id]) }}">
                                        <button type="button" class="text-center button-blue btn btn-primary">
                                            Dive Day Planning
                                        </button>
                                    </a>
                                </div>
                            </div>

                            <div class="col-md-1 col-md-offset-1">
                                <div>
                                    <a href="{{route('scubaya::merchant::dive_center::edit_dive_center', [Auth::id(), $diveCenter->id])}}" data-toggle="tooltip" title="Edit Hotel">
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
                                    <form method="post" action="{{route('scubaya::merchant::dive_center::delete_dive_center', [Auth::id(), $diveCenter->id])}}">
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
                                    <button type="button" class="button-blue btn btn-primary pull-right" data-toggle="modal" data-target="#verification-form-modal{{$diveCenter->id}}">
                                        Get Verified
                                    </button>
                                </div>

                                --}}{{-- verification model --}}{{--
                                @include('merchant.layouts.website_verification.verification_modal', ['route1' => 'scubaya::merchant::dive_center::verification',
                                'route2' => 'scubaya::merchant::dive_center::dive_centers','website' => $diveCenter])
                            @endif--}}
                        </div>
                    </div>

                    {{-- Only show this model when request status is rejected to make new verification request --}}
                    {{--@if(@$detail->status == 'rejected')
                        --}}{{-- verification model --}}{{--
                        @include('merchant.layouts.website_verification.verification_modal', ['route1' => 'scubaya::merchant::dive_center::verification',
                        'route2' => 'scubaya::merchant::dive_center::dive_centers','website' => $diveCenter])
                    @endif--}}
                @endforeach
            @else
                <div class="panel panel-default" >
                    <div class="panel-body">
                        <h4 class="text-center">No Dive Centers Available</h4>
                    </div>
                </div>
            @endif
        </div>

        <div class="pagination">
            {{ $diveCenters->links() }}
        </div>
    </section>

    @include('merchant.layouts.delete_script')
@endsection