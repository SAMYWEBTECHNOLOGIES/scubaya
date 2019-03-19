@extends('admin.layouts.app')
@section('title','Destinations')
@section('breadcrumb')
    <li><a href="{{route('scubaya::admin::dashboard')}}">Manage</a></li>
    <li class="active"><span>Destinations</span></li>
@endsection
@section('content')
    <section>
        <div class="container screen-fit">

            @if(Session::has('success'))
                <div class="alert alert-success">
                    <a href="#" class="close" data-dismiss="alert">&times;</a>
                    <p>{{ Session::get('success') }}</p>
                </div>
            @endif
            <div class="row">
                <div>
                    <a href="{{route('scubaya::admin::manage::add_destination')}}">
                        <button type="button" style="margin-right: 20px;" class="pull-right button-blue btn btn-primary">
                            Add Destination
                        </button>
                    </a>
                </div>
            </div>

                <div class="box box-primary margin-top-10">
                    <div class="box-header">
                        <h3 class="box-title">Destinations</h3>
                    </div>

                    <div class="panel-body">
                        <input type="text" class="form-control" id="dev-table-filter" data-action="filter" data-filters="#dev-table" placeholder="Filter Destination..." />
                    </div>
                    <div class="box-body table-responsive no-padding">
                        <table class="table table-hover " style="margin-top: 15px" id="dev-table">
                            @if(count($data))
                                <thead>
                                <tr>
                                    <th>S.no</th>
                                    <th>Active</th>
                                    <th>Image</th>
                                    <th>Destination</th>
                                    <th>Country</th>
                                    <th>Geographic Area</th>
                                    <th>Show Water Temperature</th>
                                    <th>Show Weather</th>
                                    <th>Actions</th>
                                </tr>
                                </thead>
                                <tbody>
                                @php
                                    $sno        =   1;
                                @endphp
                                @foreach($data as $destination)
                                    <tr>
                                        <td>{{$sno++}}</td>
                                        <td>{!!$destination->active?'<span class="fa fa-check"></span>':'<span class="fa fa-remove"></span>'!!}</td>
                                        <td style="width: 100px;"><img src="{{asset('/assets/images/scubaya/destination/'.$destination->id.'-'.$destination->image)}}" class="img-responsive" alt="{{$destination->destination_name}}"> </td>
                                        <td>{{$destination->name}}</td>
                                        <td>{{($destination->country) ? ucwords($destination->country) : '---'}}</td>
                                        <td>{{$destination->geographical_area}}</td>
                                        <td>{{$destination->water_temp ? 'Yes': 'No'}}</td>
                                        <td>{{$destination->weather ? 'Yes': 'No'}}</td>
                                        <td>
                                            <form method="post" action="{{route('scubaya::admin::manage::delete_destination',[$destination->id])}}">
                                                    {{ csrf_field() }}
                                                    {{ method_field('DELETE') }}
                                                <button type="submit" class="btn btn-danger delete">
                                                    <i class="fa fa-trash"></i>
                                                </button>
                                            </form>
                                        </td>
                                        <td>
                                            <a href="{{route('scubaya::admin::manage::edit_destination',[$destination->id])}}">
                                                <button type="button" class="btn btn-primary">
                                                    <i class="fa fa-pencil"></i>
                                                </button>
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <th class="text-center"> No Destinations Available.</th>
                                </tr>
                            @endif
                        </table>
                    </div>
                </div>
        </div>
    </section>
@endsection