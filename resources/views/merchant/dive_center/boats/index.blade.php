@extends('merchant.layouts.app')
@section('title', 'Boats')
@section('breadcrumb')
    <li><a href="#">Dive Center</a></li>
    <li><a href="{{route('scubaya::merchant::dive_center::dive_centers',[Auth::id()])}}">Manage Dive Centers</a></li>
    <li class="active"><span>Boats</span></li>
@endsection
@section('content')
    @include('merchant.layouts.mainheader')
    <section id="boat_section" class="padding-20">
        <div>
            <a href="{{ route('scubaya::merchant::dive_center::create_boat', [Auth::id(), $diveCenterId]) }}">
                <button type="button" class="pull-right button-blue btn btn-primary">
                    + New
                </button>
            </a>
        </div>

        <div class="box box-primary margin-top-60">
            <div class="box-header with-border">
                <h3 class="box-title">Boats</h3>
            </div>
            <!-- /.box-header -->

            <div class="box-body table-responsive no-padding">
                <table class="table table-hover">
                    @if(count($boats) > 0)
                        <thead>
                            <tr>
                                <th>S.No.</th>
                                <th>Active</th>
                                <th>Image</th>
                                <th>Name</th>
                                <th>Engine</th>
                                <th>Type</th>
                                <th>Action</th>
                            </tr>
                        </thead>

                        <tbody>
                        @foreach($boats as $boat)
                            <tr>
                                <td>{{ $sno++ }}</td>
                                <td>
                                    <button type="button" id="is-boat-active{{$boat->id}}" onclick="changeStatus(this)" class="btn btn-toggle @if($boat->is_boat_active == 1) active @endif" data-toggle="button" aria-pressed="@if($boat->is_boat_active == 1) true @else false @endif">
                                        <div class="handle"></div>
                                    </button>
                                </td>
                                <td>
                                    <img src="{{ asset('assets/images/scubaya/boats/'.$boat->merchant_key.'/'.$boat->id.'-'.$boat->image) }}" width="100" height="50" alt="Boat Image">
                                </td>
                                <td>{{ $boat->name }}</td>
                                <td>{{ $boat->engine_power }}</td>
                                <td>{{ $boat->type }}</td>
                                <td>
                                    <div class="inline-flex">
                                        <a href="{{ route('scubaya::merchant::dive_center::edit_boat', [Auth::id(), $boat->dive_center_id, $boat->id]) }}">
                                            <button type="button" class="button-blue btn btn-primary">
                                                <i class="fa fa-pencil"></i>
                                            </button>
                                        </a>

                                        <form class="padding-left5" method="post" action="{{ route('scubaya::merchant::dive_center::delete_boat', [Auth::id(), $boat->dive_center_id, $boat->id]) }}">
                                            {{ csrf_field() }}
                                            <button type="button" class="btn btn-danger delete">
                                                <i class="fa fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <th class="text-center"> No Boat Available.</th>
                        </tr>
                    @endif
                </table>
            </div>
        </div>

        <div class="pagination">
            {{$boats->links()}}
        </div>
    </section>

    @include('merchant.layouts.delete_script')

    <script type="text/javascript">
        function changeStatus(data)
        {
            var id        = (data.id).replace ( /[^\d.]/g, '' );
            var boatId    = parseInt(id, 10);
            var isActive  = jQuery('#'+data.id).attr('aria-pressed');

            jQuery.ajax({
                url:"{{route('scubaya::merchant::dive_center::update_boat_active_status', [Auth::id()])}}",
                method:'get',
                data:{
                    boatId:boatId,
                    isActive:(isActive.trim() == 'true') ? 0 : 1
                }
            });
        }
    </script>

@endsection