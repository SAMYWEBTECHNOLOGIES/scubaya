@extends('merchant.layouts.app')
@section('title', 'Boats')
@section('breadcrumb')
    <li><a href="#">Dive Center</a></li>
    <li class="active"><span>Add Location</span></li>
@endsection

@section('content')
    @include('merchant.layouts.mainheader')

    <section id="location_section" class="padding-20">

        @if(Session::has('success'))
            <div class="alert alert-success">
                <a href="#" class="close" data-dismiss="alert">&times;</a>
                <p>{{ Session::get('success') }}</p>
            </div>
        @endif

        <div>
            <a href="{{ route('scubaya::merchant::dive_center::add_location', [Auth::id()]) }}">
                <button type="button" class="pull-right button-blue btn btn-primary">
                    + New
                </button>
            </a>
        </div>

        <div class="box box-primary margin-top-60">
            <div class="box-header with-border">
                <h3 class="box-title">Locations</h3>
            </div>
            <!-- /.box-header -->

            <div class="box-body table-responsive no-padding">
                <table class="table table-hover">
                    @if( count($locations))
                        <thead>
                            <tr>
                                <th>S.No.</th>
                                <th>Active</th>
                                <th>Image</th>
                                <th>Name</th>
                                <th>Boat Needed</th>
                                <th>Type</th>
                                <th></th>
                                {{--<th></th>--}}
                            </tr>
                        </thead>

                        <tbody>
                        @foreach($locations as $location)
                            <tr>
                                <td>{{ $sno++ }}</td>
                                <td>
                                    <button type="button" data-location="{{$location->id}}" onclick="changeStatus(this)" class="btn btn-toggle @if($location->active) active @endif" data-toggle="button" aria-pressed="@if($location->active) true @else false @endif">
                                        <div class="handle"></div>
                                    </button>
                                </td>
                                <td>
                                    @if($location->image)
                                        <img src="{{ asset('assets/images/scubaya/locations/'.$location->id.'-'.$location->image) }}" width="100" height="50" alt="{{$location->name}}">
                                    @else
                                        -
                                    @endif
                                </td>
                                <td>{{ $location->name }}</td>
                                <td>{{ $location->need_a_boat ? 'YES' : 'NO' }}</td>
                                <td>{{ ucwords(str_replace('_',' ',$location->type)) }}</td>
                                {{--<td>
                                    <form method="post" action="#">
                                        {{ csrf_field() }}
                                        {{ method_field('DELETE') }}
                                        <button type="submit" class="btn btn-danger delete">
                                            <i class="fa fa-trash"></i>
                                        </button>
                                    </form>
                                </td>--}}
                                <td>
                                    <a href="{{route('scubaya::merchant::dive_center::edit_location',[Auth::id(),$location->id])}}">
                                        <button type="button" class="btn btn-primary">
                                            <i class="fa fa-pencil"></i>
                                        </button>
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                        @else
                            <tr>
                                <th class="text-center"> No Locations Available.</th>
                            </tr>
                    @endif
                </table>
            </div>
        </div>

        <div class="pagination">
            {{$locations->links()}}
        </div>
    </section>
    <script type="text/javascript">
        function changeStatus(data)
        {
            var id          =   jQuery(data).data('location');
            var isActive    =   jQuery(data).attr('aria-pressed');
            var url         =   '{{route('scubaya::merchant::dive_center::update_location_status',[Auth::id()])}}';
            var token       =   '{{csrf_token()}}';

            $.post( url,{id:id,status:(isActive.trim() == 'true') ? 0 : 1,_token:token }, function( status )
            {
                console.log(status.success);
            });
        }
    </script>
@endsection