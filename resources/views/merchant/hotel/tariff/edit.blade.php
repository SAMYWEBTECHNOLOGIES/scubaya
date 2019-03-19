@extends('merchant.layouts.app')
@section('title', 'Edit '. ucwords($tariff_mode).' Tariff' )
@section('breadcrumb')
    <li><a href="#">Hotel</a></li>
    <li class="active"><a href="{{route('scubaya::merchant::hotels',[Auth::id()])}}">Manage Hotels</a></li>
    <li class="active"><a href="{{route('scubaya::merchant::all_rooms',[Auth::id(),$hotelId])}}">All Rooms</a></li>
    <li class="active"><span>{{ $tariffs['tariff_title'] }}</span></li>
@endsection

@section('content')
    @include('merchant.layouts.mainheader')
    @include('merchant.layouts.tariff_script')
    <section id="edit_room_tariff_section" class="padding-20">
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title">Edit {{ucwords($tariff_mode)}} Tariff</h3>
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

            <form role="form" method="post" action="{{ route('scubaya::merchant::update_tariff', [Auth::id(), $hotelId, $tariffs['id']]) }}" enctype="multipart/form-data">
                {{ csrf_field() }}
                <div class="box-body">
                    <?php
                    $additionalTariffData   =   (array)json_decode($tariffs['additional_tariff_data']);
                    ?>
                    @if($tariff_mode == 'micro')
                        @include('merchant.hotel.tariff.micromanage', $additionalTariffData)
                    @endif

                    @if($tariff_mode == 'normal')
                        @include('merchant.hotel.tariff.normal', $additionalTariffData)
                    @endif

                    @if($tariff_mode == 'advance')
                        @include('merchant.hotel.tariff.advance', $additionalTariffData)
                    @endif
                </div>
                <!-- /.box-body -->
                <div class="box-footer">
                    <a href="{{ route('scubaya::merchant::all_rooms', [Auth::id(), $hotelId]) }}"><button type="button" class="btn btn-default">Cancel</button></a>
                    <button type="submit" class="btn btn-info pull-right">Update</button>
                </div>
                <!-- /.box-footer -->
            </form>
        </div>
    </section>
@endsection
