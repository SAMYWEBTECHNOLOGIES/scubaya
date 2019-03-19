@extends('admin.layouts.app')
@section('title','Dynamic Pages')
@section('breadcrumb')
    <li><a href="{{route('scubaya::admin::dashboard')}}">Manage</a></li>
    <li class="active"><span>Dynamic Pages</span></li>
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
                    <a href="{{route('scubaya::admin::manage::add_page')}}">
                        <button type="button" style="margin-right: 20px;" class="pull-right button-blue btn btn-primary">
                            Add Page
                        </button>
                    </a>
                </div>
            </div>

            <div class="box box-primary margin-top-10">
                <div class="box-header">
                    <h3 class="box-title">Dynamic Pages</h3>
                </div>

                <div class="panel-body">
                    <input type="text" class="form-control" id="dev-table-filter" data-action="filter" data-filters="#dev-table" placeholder="Filter pages" />
                </div>
                <div class="box-body table-responsive no-padding">
                    <table class="table table-hover "style="margin-top: 15px" id="dev-table">
                        @if(count($dynamic_pages))
                            <thead>
                            <tr>
                                <th>S.no</th>
                                <th>Active</th>
                                <th>Name</th>
                                <th>Slug</th>
                                <th></th>
                                <th></th>
                            </tr>
                            </thead>
                            <tbody>
                            @php
                                $sno        =   1;
                            @endphp
                            @foreach($dynamic_pages as $dynamic_page)
                                <tr>
                                    <td>{{$sno++}}</td>
                                    <td>{!!$dynamic_page->active?'<span class="fa fa-check"></span>':'<span class="fa fa-remove"></span>'!!}</td>
                                    <td>{{$dynamic_page->name}}</td>
                                    <td>{{$dynamic_page->slug}}</td>
                                    <td>
                                        <form method="post" action="{{route('scubaya::admin::manage::delete_page',[$dynamic_page->id])}}">
                                            {{ csrf_field() }}
                                            {{ method_field('DELETE') }}
                                            <button type="submit" class="btn btn-danger delete">
                                                <i class="fa fa-trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                    <td>
                                        <a href="{{route('scubaya::admin::manage::edit_page',[$dynamic_page->id])}}">
                                            <button type="button" class="btn btn-primary">
                                                <i class="fa fa-pencil"></i>
                                            </button>
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                            @else
                                <tr>
                                    <th class="text-center"> No Dynamic Pages Available.</th>
                                </tr>
                        @endif
                    </table>
                </div>
            </div>
        </div>
    </section>
@endsection