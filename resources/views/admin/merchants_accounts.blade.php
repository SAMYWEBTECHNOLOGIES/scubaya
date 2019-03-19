@extends('admin.layouts.app')
@section('title','Merchant Accounts')

@section('breadcrumb')
    <li><a href="{{route('scubaya::admin::dashboard')}}">Merchants</a></li>
    <li class="active"><span>Accounts</span></li>
@endsection

@section('content')
    <section class="container screen-fit">
        <div class="screen-fit">
            <div class="row">
                @if(Session::has('success'))
                    <div class="alert alert-success">
                        <a href="#" class="close" data-dismiss="alert">&times;</a>
                        <p>{{ Session::get('success') }}</p>
                    </div>
                @endif
                <div class="panel panel-primary filterable">
                    <div class="row padding-20">
                        <div class="col-md-10">
                            <form action="{{route('scubaya::admin::merchants_accounts')}}" method="get">
                                <div class="row">
                                    <div class="form-group col-md-3" >
                                        <div class="icon-addon addon-md">
                                            <select class="form-control selectpicker show-tick rating" name="rating" title="Filter by Rating">
                                                <option value="{{MERCHANT_RATING_BAD}}"> {{ucfirst(MERCHANT_RATING_BAD)}}</option>
                                                <option value="{{MERCHANT_RATING_GOOD}}">{{ucfirst(MERCHANT_RATING_GOOD)}}</option>
                                                <option value="{{MERCHANT_RATING_UNKNOWN}}">{{ucfirst(MERCHANT_RATING_UNKNOWN)}}</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="form-group col-md-3">
                                        <div class="icon-addon addon-md">
                                            <select class="form-control selectpicker show-tick screening" name="screening" title="Filter by Screening">
                                                <option value="{{MERCHANT_SCREENING_COMPLETED}}">{{ucfirst(MERCHANT_SCREENING_COMPLETED)}}</option>
                                                <option value="{{MERCHANT_SCREENING_PENDING}}">{{ucfirst(MERCHANT_SCREENING_PENDING)}}  </option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="form-group col-md-3">
                                        <div class="icon-addon addon-md">
                                            <select class="form-control selectpicker show-tick status" name="status" title="Filter by Status">
                                                <option value="{{MERCHANT_STATUS_PENDING}}">{{ucfirst(MERCHANT_STATUS_PENDING )  }}</option>
                                                <option value="{{MERCHANT_STATUS_APPROVED }}">{{ucfirst(MERCHANT_STATUS_APPROVED ) }}</option>
                                                <option value="{{MERCHANT_STATUS_REJECTED}}">{{ucfirst(MERCHANT_STATUS_REJECTED)}}  </option>
                                                <option value="{{MERCHANT_STATUS_DISABLED}}">{{ucfirst(MERCHANT_STATUS_DISABLED)}}</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-md-3 "><button style="background-color: #ddd1b9" type="submit" class="form-control btn btn-default"><i class="fa fa-search">&nbsp Search</i></button></div>
                                </div>
                            </form>
                        </div>

                        <div class="col-md-2">
                            <form action="{{route('scubaya::admin::merchants_accounts')}}" method="post">
                                {{csrf_field()}}
                                <button name="reset" style="background-color: #cfcedd" type="submit" class="form-control btn btn-default">Reset</button>
                            </form>
                        </div>
                    </div>
                    @php
                        $rating_status      =   [
                                MERCHANT_RATING_BAD     =>  'label label-danger',
                                MERCHANT_RATING_GOOD    =>  'label label-success',
                                MERCHANT_RATING_UNKNOWN =>  'label label-warning',
                        ];

                        $screening_status   =   [
                                MERCHANT_SCREENING_COMPLETED    =>  'label label-success',
                                MERCHANT_SCREENING_PENDING      =>  'label label-warning',
                        ];

                        $status     =   [
                                MERCHANT_STATUS_PENDING         =>  'label label-warning',
                                MERCHANT_STATUS_APPROVED        =>  'label label-success',
                                MERCHANT_STATUS_REJECTED        =>  'label label-danger',
                                MERCHANT_STATUS_DISABLED        =>  'label label-default',
                        ];
                    @endphp
                    @if(count($data))
                        <table class="table table-striped table-hover table-responsive">
                            <thead>
                            <tr class="filters black-white">
                                <th>Created</th>
                                <th>Account</th>
                                <th>Company Name</th>
                                <th>Address</th>
                                <th>Country</th>
                                <th>Phone</th>
                                <th>Email</th>
                                <th>Chamber of Commerce</th>
                                <th>Rating</th>
                                <th>Screening</th>
                                <th>Status</th>
                                <th></th>
                                <th></th>
                            </tr>
                            </thead>

                            <tbody>
                            @foreach($data as $merchant)
                                <tr>
                                    <td>{{$merchant->created_at ? $merchant->created_at->toDateString() : '-'}}</td>
                                    <td>@if($merchant->merchant_primary_id) <a href="{{route('scubaya::admin::merchants::merchant',[$merchant->merchant_primary_id, $merchant->id])}}">{{ \App\Scubaya\model\User::getUID($merchant->merchant_primary_id) }}</a>@else - @endif</td>
                                    <td>{{$merchant->company_type or  '-'}}</td>
                                    <td>{{$merchant->address or '-'}}</td>
                                    <td>{{$merchant->city or '-'}}</td>
                                    <td>0123456789</td>
                                    <td>{{$merchant->email}}</td>
                                    <td>00000011122222</td>
                                    <td>
                                        @if($merchant->rating)<span class="{{$rating_status[$merchant->rating] or 'label label-info'}}">{{ $merchant->rating or 'No rating'}}</span>@else - @endif
                                    </td>

                                    <td>
                                        @if($merchant->screening)<span class="status {{$screening_status[$merchant->screening] or 'label label-info'}}">{{ $merchant->screening or 'Not Screened'}}</span> @else - @endif
                                    </td>
                                    <td>
                                        @if($merchant->status) <span class="status {{$status[$merchant->status]}}">{{ ucwords(str_replace('_', ' ',$merchant->status))}}</span> @else - @endif
                                    </td>
                                    <td>
                                        @if(\App\Scubaya\model\User::getUID($merchant->merchant_primary_id))
                                            <form method="post" action="{{route('scubaya::admin::delete_merchant',[$merchant->merchant_primary_id])}}">
                                                {{ csrf_field() }}
                                                {{ method_field('DELETE') }}
                                                <button type="submit" class="btn btn-danger delete">
                                                    <i class="fa fa-trash"></i>
                                                </button>
                                            </form>
                                        @endif
                                    </td>
                                </tr>

                            @endforeach
                            </tbody>
                        </table>
                        {{$data->links()}}
                </div>
            </div>
        </div>
        @else
            <section class="merchant_dashboard">
                <h2 class="text-center">No Data</h2>
            </section>
        @endif
    </section>

    <script type="text/javascript">
        $(document).ready(function(){
            $('.merchant-select').on('change',function(e){
                var selectedClass = $.grep($(this).find('option:selected').attr("class").split(" "), function(v, i){
                    return v.indexOf('btn-') === 0;
                }).join();

                $(this).closest('select').removeClass().addClass(selectedClass);

            });
        });

        @if($errors->add_merchant->any())
        $(function() {
            $('#create-merchant-form-model').modal('show');
        });
        @endif

        $( "#merchant_sign_up_form" ).validate({
            rules: {
                merchant_email:{
                    required: true,
                    email: true
                }
            }
        });
    </script>
@endsection