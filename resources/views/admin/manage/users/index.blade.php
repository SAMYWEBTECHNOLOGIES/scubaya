@extends('admin.layouts.app')
@section('title','Users')
@section('breadcrumb')
    <li><a href="{{route('scubaya::admin::dashboard')}}">Manage</a></li>
    <li class="active"><span>Users</span></li>
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
                    <a href="{{route('scubaya::admin::manage::add_user')}}">
                        <button type="button" style="margin-right: 20px;" class="pull-right button-blue btn btn-primary">
                            Add User
                        </button>
                    </a>
                </div>
            </div>
                <div class="box box-primary margin-top-60">
                    <div class="box-header">
                        <h3 class="box-title">Users</h3>
                    </div>
                    <div class="panel-body">
                        <input type="text" class="form-control" id="dev-table-filter" data-action="filter" data-filters="#dev-table" placeholder="Filter Users" />
                    </div>
                    <div class="box-body table-responsive">
                        <table class="table table-hover" id="dev-table">
                            @if(count($users))
                                <thead>
                                <tr>
                                    <th>S.no</th>
                                    {{--<th>Image</th>--}}
                                    <th>User Id</th>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th></th>
                                    <th></th>
                                </tr>
                                </thead>
                                <tbody>
                                    @foreach($users as $user)
                                        <tr>
                                            <td>{{$sno++}}</td>
                                            <td>{{$user->UID}}</td>

                                            <td>
                                            @if(!is_null($user->first_name) && !is_null($user->last_name))
                                                {{ App\Scubaya\model\User::decryptString($user->first_name).' '.App\Scubaya\model\User::decryptString($user->last_name) }}
                                            @else
                                                ---
                                            @endif
                                            </td>

                                            <td>
                                                @if(!empty($user->email))
                                                    {{ App\Scubaya\model\User::decryptString($user->email) }}
                                                @else
                                                    ---
                                                @endif
                                            </td>

                                            <td>
                                                <form method="post" action="{{route('scubaya::admin::manage::edit_user',[$user->id])}}">
                                                    {{ csrf_field() }}
                                                    {{ method_field('EDIT') }}
                                                    <button type="submit" class="btn btn-primary">
                                                        <i class="fa fa-pencil"></i>
                                                    </button>
                                                </form>
                                            </td>
                                            <td>
                                                <form method="post" action="{{route('scubaya::admin::manage::delete_user',[$user->id])}}">
                                                    {{ csrf_field() }}
                                                    {{ method_field('DELETE') }}
                                                    <button type="submit" class="btn btn-danger delete">
                                                        <i class="fa fa-trash"></i>
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <th class="text-center"> No User Available.</th>
                                    </tr>
                            @endif
                        </table>
                        {{$users->links()}}
                    </div>
                </div>
            </div>
    </section>
@stop