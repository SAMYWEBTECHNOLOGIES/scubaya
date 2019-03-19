@extends('admin.layouts.app')
@section('title','Merchant-'.$account_details->email)
@section('breadcrumb')
    <li><a href="{{route('scubaya::admin::merchants_accounts')}}">Accounts</a></li>
    <li class="active"><span>{{$account_details->email}}</span></li>
@endsection
@section('content')
    <section class="padding-20" id="merchant-info">
      <div class="box box-primary">
            <div class="box-body">
                <div class="row margin-bottom-10">
                    <div class="col-md-10">
                        <label>Merchant:</label>
                        <span class="blue">{{ \App\Scubaya\model\User::getUID($account_details->merchant_primary_id) }}</span>

                        <label class="margin-left-15">Account Manager:</label>
                        <span>Dirk Meij</span>

                        @php
                            $rating_status  =   [
                                    MERCHANT_RATING_BAD     =>  'label label-danger',
                                    MERCHANT_RATING_GOOD    =>  'label label-success',
                                    MERCHANT_RATING_UNKNOWN =>  'label label-warning',
                            ];
                        @endphp
                        <label class="margin-left-15">Rating:</label>
                        <span class="status {{$rating_status[$account_details->rating] or 'label label-info'}}">{{ $account_details->rating or 'No rating'}}</span>

                        @php
                            $screening_status  =    [
                                    MERCHANT_SCREENING_COMPLETED    =>  'label label-success',
                                    MERCHANT_SCREENING_PENDING      =>  'label label-warning',
                            ];
                        @endphp
                        <label class="margin-left-15">Screening:</label>
                        <span class="status {{$screening_status[$account_details->screening] or 'label label-info'}}">{{ $account_details->screening or 'Not Screened'}}</span>

                        @php
                            $status  =   [
                                        MERCHANT_STATUS_PENDING         =>  'label label-warning',
                                        MERCHANT_STATUS_APPROVED        =>  'label label-success',
                                        MERCHANT_STATUS_REJECTED        =>  'label label-danger',
                                        MERCHANT_STATUS_DISABLED        =>  'label label-default',
                            ];
                        @endphp
                        <label class="margin-left-15">Status:</label>
                        <span class="status {{$status[$account_details->status] or 'label label-info'}}">{{ucwords(str_replace('_', ' ',$account_details->status))}}</span>
                    </div>

                    {{--Login as merchant button--}}
                    <div class="col-md-2">
                        <form target="_blank" action="{{route('scubaya::merchant::login_merchant')}}" method="post">
                            {{ csrf_field() }}
                            {{ method_field('LOGIN_MERCHANT')}}
                            <input type="hidden" value="{{$account_details->merchant_primary_id}}" name="merchant_key">
                            <button type="submit" class="btn btn-primary pull-right">Login as Merchant</button>
                        </form>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                            <ul class="nav nav-tabs" role="tablist">
                                <li role="presentation" class="active"><a href="#account_details" aria-controls="account_details" role="tab" data-toggle="tab">Account Details</a></li>
                                <li role="presentation"><a href="#documents" aria-controls="documents" role="tab" data-toggle="tab">Documents</a></li>
                                <li role="presentation"><a href="#websites" aria-controls="websites" role="tab" data-toggle="tab">Websites</a></li>
                                <li role="presentation"><a href="#payments" aria-controls="payments" role="tab" data-toggle="tab">Payments</a></li>
                                <li role="presentation"><a href="#pricing" aria-controls="pricing" role="tab" data-toggle="tab">Pricing</a></li>
                                <li role="presentation"><a href="#balancing" aria-controls="balancing" role="tab" data-toggle="tab">Balancing</a></li>
                                <li role="presentation"><a href="#room_types" aria-controls="room_types" role="tab" data-toggle="tab">Room Types</a></li>
                                <li role="presentation"><a href="#equipment" aria-controls="equipment" role="tab" data-toggle="tab">Equipment</a></li>
                                <li role="presentation"><a href="#users" aria-controls="users" role="tab" data-toggle="tab">Users</a></li>
                                <li role="presentation"><a href="#cleaning_schedule" aria-controls="cleaning_schedule" role="tab" data-toggle="tab">Cleaning Schedule</a></li>
                                <li role="presentation"><a href="#custom_fields" aria-controls="custom_fields" role="tab" data-toggle="tab">Custom Fields</a></li>
                                <li role="presentation"><a href="#guest_types" aria-controls="guest_types" role="tab" data-toggle="tab">Guest Types</a></li>
                            </ul>

                            <!-- Tab panes -->
                            <div class="tab-content" style="min-height: 600px">
                                <div role="tabpanel" class="tab-pane active" id="account_details">
                                    @include('admin.merchants.partials.account_details')
                                </div>

                                <div role="tabpanel" class="tab-pane" id="documents">
                                    @include('admin.merchants.partials.documents')
                                </div>

                                <div role="tabpanel" class="tab-pane" id="websites">
                                    @include('admin.merchants.partials.websites')
                                </div>

                                <div role="tabpanel" class="tab-pane" id="payments">

                                </div>

                                <div role="tabpanel" class="tab-pane" id="pricing">
                                    @include('admin.merchants.partials.pricing')
                                </div>

                                <div role="tabpanel" class="tab-pane" id="balancing">

                                </div>

                                <div role="tabpanel" class="tab-pane" id="room_types">

                                </div>

                                <div role="tabpanel" class="tab-pane" id="equipment">

                                </div>

                                <div role="tabpanel" class="tab-pane" id="users">

                                </div>

                                <div role="tabpanel" class="tab-pane" id="cleaning_schedule">

                                </div>

                                <div role="tabpanel" class="tab-pane" id="custom_fields">

                                </div>

                                <div role="tabpanel" class="tab-pane" id="guest_types">

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
       </div>
    </section>
    <script type="text/javascript">
        {{-- script to active tab after redirecting page --}}
        jQuery('a[data-toggle="tab"]').on('show.bs.tab', function(e) {
                localStorage.setItem('activeTab', jQuery(e.target).attr('href'));
            });

        var activeTab = localStorage.getItem('activeTab');

        if (activeTab) {
            jQuery('a[href="' + activeTab + '"]').tab('show');
        }
    </script>
@stop


