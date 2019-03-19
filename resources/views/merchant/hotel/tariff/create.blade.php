@extends('merchant.layouts.app')
@section('title', ucwords(@$tariff_mode->tariff_mode).' Tariff' )
@section('breadcrumb')
    <li><a href="#">Hotel</a></li>
    <li class="active"><a href="{{route('scubaya::merchant::hotels',[Auth::id()])}}">Manage Hotel</a></li>
    <li class="active"><span>{{ucwords(@$tariff_mode->tariff_mode)}} Tariff</span></li>
@endsection
@section('content')
    @include('merchant.layouts.mainheader')
    @include('merchant.layouts.tariff_script')
    <section id="create_room_tariff_section" class="padding-20">
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title">{{ucwords(@$tariff_mode->tariff_mode)}} Tariff</h3>
            </div>
            <!-- /.box-header -->
            <!-- form start -->
            @if ($errors->any())
                <div class="row margin-top-10">
                    <div class="col-md-4 col-md-offset-4 alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            @endif

            @if(!empty($tariff_mode->tariff_mode))
            <form role="form" method="post" action="{{ route('scubaya::merchant::save_tariff', [Auth::id(), $hotelId]) }}" enctype="multipart/form-data">
                {{ csrf_field() }}
                <div class="box-body">
                    @if(@$tariff_mode->tariff_mode == 'micro')
                        @include('merchant.hotel.tariff.micromanage')
                    @endif

                    @if(@$tariff_mode->tariff_mode == 'normal')
                        @include('merchant.hotel.tariff.normal')
                    @endif

                    @if(@$tariff_mode->tariff_mode == 'advance')
                        @include('merchant.hotel.tariff.advance')
                    @endif
                </div>

                <!-- /.box-body -->
                <div class="box-footer">
                    <a href="{{ route('scubaya::merchant::hotels', [Auth::id()]) }}"><button type="button" class="btn btn-default">Cancel</button></a>
                    <button type="submit" class="btn btn-info pull-right">Create</button>
                </div>
                <!-- /.box-footer -->
            </form>
            @else
                <p class="text-center">Please select tariff mode first from pricing settings option to create tariff.</p>
            @endif
        </div>
    </section>
@endsection
