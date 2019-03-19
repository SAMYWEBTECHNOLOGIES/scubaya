@extends('merchant.layouts.app')
@section('title', 'Room Types')
@section('breadcrumb')
    <li><a href="#">Hotel</a></li>
    <li class="active"><span>Room Types</span></li>
@endsection


@section('content')
    @include('merchant.layouts.mainheader')

    <section id="room_types_section" class="padding-20">
        <div>
            <a href="{{ route('scubaya::merchant::create_room_type', [Auth::id()]) }}">
                <button type="button" class="pull-right button-blue btn btn-primary">
                    + New
                </button>
            </a>
        </div>

        <div class="box box-primary margin-top-60">
            <div class="box-header with-border">
                <h3 class="box-title">Room Types</h3>
            </div>
            <!-- /.box-header -->

            <div class="box-body table-responsive no-padding">
                <table class="table table-hover">
                    @if(count($roomTypes) > 0)
                        <thead>
                        <tr>
                            <th>S.No.</th>
                            <th>Room Type</th>
                            <th>Icon</th>
                            <th>Action</th>
                        </tr>
                        </thead>

                        <tbody>
                        @foreach($roomTypes as $type)
                            <tr>
                                <td>{{ $sno++ }}</td>
                                <td>{{ $type->room_type }}</td>
                                <td>
                                    @if($type->icon)
                                        <img src="{{ asset('assets/images/scubaya/room_types/'.$type->id.'-'.$type->icon) }}" width="100" height="50" alt="Room Type Icon">
                                    @else
                                        {{ '-' }}
                                    @endif
                                </td>
                                <td>
                                    <div class="inline-flex">
                                        <a href="{{ route('scubaya::merchant::edit_room_type', [Auth::id(), $type->id]) }}">
                                            <button type="button" class="button-blue btn btn-primary">
                                                <i class="fa fa-pencil"></i>
                                            </button>
                                        </a>

                                        <form class="padding-left5" method="post" action="{{ route('scubaya::merchant::delete_room_type', [Auth::id(), $type->id]) }}">
                                            {{ csrf_field() }}
                                            <button type="button" class="btn btn-danger delete">
                                                <i class="fa fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    @else
                        <tr>
                            <th class="text-center"> No Room Types Available.</th>
                        </tr>
                    @endif
                </table>
            </div>
        </div>

        <div class="pagination">
            {{ $roomTypes->links() }}
        </div>
    </section>

    @include('merchant.layouts.delete_script')
@endsection
