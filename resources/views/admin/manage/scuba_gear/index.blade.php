@extends('admin.layouts.app')
@section('title','Gears')
@section('breadcrumb')
    <li><a href="{{route('scubaya::admin::dashboard')}}">Manage</a></li>
    <li class="active"><span>Gears</span></li>
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
                    <a href="{{route('scubaya::admin::manage::gear::create')}}">
                        <button type="button" style="margin-right: 20px;" class="pull-right button-blue btn btn-primary">
                            Add Gear
                        </button>
                    </a>
                </div>
            </div>

            <div class="box box-primary margin-top-60">
                <div class="box-header">
                    <h3 class="box-title">Gears</h3>
                </div>

                <div class="box-body table-responsive no-padding">
                    <table class="table table-hover" id="gear">
                        @if(count($gears))
                            <thead>
                                <tr>
                                    <th>S.no</th>
                                    <th>Icon</th>
                                    <th>Name</th>
                                    <th>Category</th>
                                    <th>Action</th>
                                </tr>
                            </thead>

                            <tbody>
                            @foreach($gears as $gear)
                                <tr>
                                    <td>{{ $sno++ }}</td>
                                    <td>
                                        <img width="60" height="60" src="{{ asset('assets/images/scubaya/gears/'.$gear->id.'-'.$gear->icon) }}" alt="Scubaya-{{ $gear->icon }}">
                                    </td>
                                    <td>{{ $gear->name }}</td>
                                    <td>{{ $gear->category }}</td>
                                    <td>
                                        <a href="{{route('scubaya::admin::manage::gear::update',[$gear->id])}}">
                                            <button type="button" class="btn btn-primary submit">
                                                <i class="fa fa-pencil"></i>
                                            </button>
                                        </a>

                                        <form class="inline-flex" method="post" action="{{route('scubaya::admin::manage::gear::delete',[$gear->id])}}">
                                            {{ csrf_field() }}
                                            <button type="submit" class="btn btn-danger submit delete">
                                                <i class="fa fa-trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        @else
                            <tr>
                                <th class="text-center"> No Gear Available.</th>
                            </tr>
                        @endif
                    </table>
                    <div class="text-center">{{$gears->links()}}</div>
                </div>
            </div>
        </div>
    </section>
@endsection

