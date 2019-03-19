@extends('merchant.layouts.app')
@section('title', 'Room Features')@section('breadcrumb')
    <li><a href="#">Hotel</a></li>
    <li class="active"><span>Room Features</span></li>
@endsection

@section('content')
    @include('merchant.layouts.mainheader')

    <section id="room_features_section" class="padding-20">
        <div>
            <a href="{{ route('scubaya::merchant::create_room_feature', [Auth::id()]) }}">
                <button type="button" class="pull-right button-blue btn btn-primary">
                    + New
                </button>
            </a>
        </div>

        <div class="box box-primary margin-top-60">
            <div class="box-header with-border">
                <h3 class="box-title">Room Features</h3>
            </div>
            <!-- /.box-header -->

            <div class="box-body table-responsive no-padding">
                <table class="table table-hover">
                    @if(count($roomFeatures) > 0)
                        <thead>
                        <tr>
                            <th>S.No.</th>
                            <th>Room Feature</th>
                            <th>Icon</th>
                            <th>Action</th>
                        </tr>
                        </thead>

                        <tbody>
                            @foreach($roomFeatures as $feature)
                            <tr>
                                <td>{{ $sno++ }}</td>
                                <td>{{ $feature->feature_description }}</td>
                                <td>
                                    @if($feature->icon)
                                        <img src="{{ asset('assets/images/scubaya/room_features/'.$feature->id.'-'.$feature->icon) }}" width="100" height="50" alt="Room Feature Icon">
                                    @else
                                        -
                                    @endif
                                </td>
                                <td>
                                    <div class="inline-flex">
                                        <a href="{{ route('scubaya::merchant::edit_room_feature', [Auth::id(), $feature->id]) }}">
                                            <button type="button" class="button-blue btn btn-primary">
                                                <i class="fa fa-pencil"></i>
                                            </button>
                                        </a>

                                        <form class="padding-left5" method="post" action="{{ route('scubaya::merchant::delete_room_feature', [Auth::id(), $feature->id]) }}">
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
                            <th class="text-center"> No Features Available.</th>
                        </tr>
                    @endif
                </table>
            </div>
        </div>

        <div class="pagination">
            {{$roomFeatures->links()}}
        </div>
    </section>

    @include('merchant.layouts.delete_script')
@endsection
