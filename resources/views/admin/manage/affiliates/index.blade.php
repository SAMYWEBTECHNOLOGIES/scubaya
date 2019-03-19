@extends('admin.layouts.app')
@section('title','Affiliations')
@section('breadcrumb')
    <li><a href="{{route('scubaya::admin::dashboard')}}">Manage</a></li>
    <li class="active"><span>Affiliations</span></li>
@endsection
@section('content')
    <section>
        <div class="container screen-fit">

            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        <a href="#" class="close" data-dismiss="alert">&times;</a>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @if(Session::has('success'))
                <div class="alert alert-success">
                    <a href="#" class="close" data-dismiss="alert">&times;</a>
                    <p>{{ Session::get('success') }}</p>
                </div>
            @endif
            <div class="row">
                <div>
                    <a href="{{route('scubaya::admin::manage::add_affiliate')}}">
                        <button type="button" style="margin-right: 20px;" class="pull-right button-blue btn btn-primary">
                            Add Affiliate
                        </button>
                    </a>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div><h4>International Affiliates</h4></div>
                    @foreach($affiliations as $international_affiliation)
                        @if($international_affiliation->international)
                            <div style="width:15%;text-align:center;float:left;">
                                <button type="button" id="is-affiliate-active{{$international_affiliation->id}}" onclick="changeStatus(this)" class="btn btn-toggle @if($international_affiliation->active) active @endif" data-toggle="button" aria-pressed="@if($international_affiliation->active) true @else false @endif">
                                    <div class="handle"></div>
                                </button>
                            <img src="{{asset('/assets/images/scubaya/affiliations/'.$international_affiliation->id.'-'.$international_affiliation->image)}}" style="width:100%">
                            </div>
                        @endif
                    @endforeach
                </div>

                <div class="col-md-6">
                    <div><h4>National Affiliates</h4></div>
                    @foreach($affiliations as $international_affiliation)
                        @if(!$international_affiliation->international)
                            <div style="width:15%;text-align:center;float:left;">
                                <button type="button" id="is-affiliate-active{{$international_affiliation->id}}" onclick="changeStatus(this)" class="btn btn-toggle @if($international_affiliation->active) active @endif" data-toggle="button" aria-pressed="@if($international_affiliation->active) true @else false @endif">
                                    <div class="handle"></div>
                                </button>
                                <img src="{{asset('/assets/images/scubaya/affiliations/'.$international_affiliation->id.'-'.$international_affiliation->image)}}" style="width:100%">
                            </div>
                        @endif
                    @endforeach
                </div>
            </div>
        </div>
    </section>
    <script type="text/javascript">
        function changeStatus(data)
        {
            var id            =     (data.id).replace ( /[^\d.]/g, '' );
            var affiliate_id  =     parseInt(id, 10);
            var isActive      =     jQuery(data).attr('aria-pressed');
            var token         =     "{{ csrf_token() }}";
            var url           =     "{{route('scubaya::admin::manage::update_affiliate_status')}}";

            $.post( url,{affiliateId:affiliate_id,isActive:(isActive.trim() == 'true') ? 0 : 1,_token:token }, function( status )
            {});
        }


    </script>

@endsection