@extends('admin.layouts.app')
@section('title','Marine Life')
@section('breadcrumb')
    <li><a href="{{route('scubaya::admin::dashboard')}}">Manage</a></li>
    <li class="active"><span>Marine Life</span></li>
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
                    <a href="{{route('scubaya::admin::manage::add_marine_life')}}">
                        <button type="button" style="margin-right: 20px;" class="pull-right button-blue btn btn-primary">
                            Add Marine Life
                        </button>
                    </a>
                </div>
            </div>
            <div class="box box-primary margin-top-60">
                <div class="box-header">
                    <h3 class="box-title">Marine Lifes</h3>
                </div>
                <div class="panel-body">
                    <input type="text" class="form-control" id="dev-table-filter" data-action="filter" data-filters="#marine-life-table" placeholder="Filter by Names" />
                </div>

                <div class="box-body table-responsive no-padding">
                    <table class="table table-hover" id="marine-life-table">
                        @if(count($data))
                            <thead>
                            <tr>
                                <th>S.no</th>
                                <th>Active</th>
                                <th>Image</th>
                                <th>Common Name</th>
                                <th>Scientific Name</th>
                                <th></th>
                            </tr>
                            </thead>
                            <tbody>

                            @foreach($data as $marine_life)
                                <tr>
                                    <td>{{$sno++}}</td>
                                    <td>{!!$marine_life->active?'<span class="fa fa-check"></span>':'<span class="fa fa-remove"></span>'!!}</td>
                                    <td style="width: 100px;">
                                        @if($marine_life->main_image)
                                            <img src="{{asset('/assets/images/scubaya/marine_life/'.$marine_life->id.'-'.$marine_life->main_image)}}" class="img-responsive" alt="{{$marine_life->common_name}}" >
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td>{{$marine_life->common_name}}</td>
                                    <td>{{$marine_life->scientific_name}}</td>
                                    <td>
                                        <a href="{{route('scubaya::admin::manage::edit_marine_life',[$marine_life->id])}}">
                                            <button type="button" class="btn btn-primary">
                                                <i class="fa fa-pencil"></i>
                                            </button>
                                        </a>
                                    </td>
                                </tr>
                            @endforeach

                            @else
                                <tr>
                                    <th class="text-center"> No Marine Life Available.</th>
                                </tr>
                        @endif
                    </table>
                    <div class="text-center">{{$data->links()}}</div>
                </div>
            </div>
        </div>
    </section>
@endsection