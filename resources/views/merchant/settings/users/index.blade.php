@extends('merchant.layouts.app')
@section('title', 'Users')
@section('breadcrumb')
    <li><a href="#">Settings</a></li>
    <li class="active"><span>Users</span></li>
@endsection

@section('content')
    @include('merchant.layouts.mainheader')
    <?php
    $label    =   [
        1     =>  'label label-success',
        0     =>  'label label-danger'
    ];
    ?>

    <section id="users_section" class="padding-20">
        <div>
            <a href="{{ route('scubaya::merchant::settings::create_user_by_id', [Auth::id()]) }}">
                <button type="button" class="pull-right button-blue btn btn-primary" style="margin-left: 10px">
                    Add User By ID
                </button>
            </a>

            <a href="{{ route('scubaya::merchant::settings::create_user', [Auth::id()]) }}">
                <button type="button" class="pull-right button-blue btn btn-primary">
                    Add User
                </button>
            </a>
        </div>
        <div class="box box-primary margin-top-60">
            <div class="box-header with-border">
                <h3 class="box-title">Users</h3>
            </div>
            <!-- /.box-header -->

            <div class="box-body table-responsive no-padding">
                <table class="table table-hover">
                    @if(count($users) > 0)
                    <thead>
                        <tr>
                            <th>S.No.</th>
                            <th>First Name</th>
                            <th>Last Name</th>
                            <th>Email</th>
                            <th>Access Rights / Active / Role Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>

                    <tbody>
                    @foreach($users as $user)
                        <tr>
                            <td>{{ $sno++ }}</td>
                            <td>{{ App\Scubaya\model\User::decryptString($user->first_name) }}</td>
                            <td>{{ App\Scubaya\model\User::decryptString($user->last_name) }}</td>
                            <td>{{ App\Scubaya\model\User::decryptString($user->email) }}</td>
                            <td>
                            <?php
                            $userRoles  = (array)json_decode($user->group_id);
                            $extraRole  = [];
                            ?>
                            @foreach($userRoles as $key => $value)
                                <?php $role = \App\Scubaya\model\Group::where('id', $key)->value('name'); ?>
                                @if(strtolower($role) == 'instructor' && isset($value->extra_role))
                                    @foreach($value->extra_role as $key1 => $value1)
                                        @if($value1 == DIVE_MASTER)
                                            <?php $extraRole[] = 'Dive Master'; ?>
                                        @endif

                                        @if($value1 == DIVE_GUIDE)
                                            <?php $extraRole[] = 'Dive Guide'; ?>
                                        @endif
                                    @endforeach
                                @endif
                                <p>
                                    {{ $role }} {!!$value->is_user_active?'<span class="fa fa-check"></span>':'<span class="fa fa-remove"></span>'!!} <span class="{{$label[$value->confirmed]}}">@if($value->confirmed) {{"verified"}} @else {{"unverified"}} @endif</span>
                                </p>
                                @if($extraRole)
                                    @foreach($extraRole as $role)
                                        <p>{{ $role }} {!!$value->is_user_active?'<span class="fa fa-check"></span>':'<span class="fa fa-remove"></span>'!!} <span class="{{$label[$value->confirmed]}}">@if($value->confirmed) {{"verified"}} @else {{"unverified"}} @endif</span></p>
                                    @endforeach
                                @endif
                            @endforeach
                            </td>
                            <td>
                                <div class="inline-flex">
                                    <a href="{{ route('scubaya::merchant::settings::edit_user', [Auth::id(), $user->user_id]) }}">
                                        <button type="button" class="button-blue btn btn-primary">
                                            <i class="fa fa-pencil"></i>
                                        </button>
                                    </a>

                                    <form class="padding-left5" method="post" action="{{ route('scubaya::merchant::settings::delete_user', [Auth::id(), $user->user_id]) }}">
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
                        <th class="text-center">No User Available.</th>
                    </tr>
                    @endif
                </table>
            </div>
        </div>
    </section>
    @include('merchant.layouts.delete_script')
@endsection