@extends('merchant.layouts.app')
@section('title','Instructor')
@section('breadcrumb')
    <li><a href="#">Dive Center</a></li>
    <li><a href="{{route('scubaya::merchant::dive_center::dive_centers',[Auth::id()])}}">Manage Dive Centers</a></li>
    <li class="active"><span>Instructors</span></li>
@endsection

@section('content')
    @include('merchant.layouts.mainheader')

    <section id="create_room_section" class="padding-20">
        @if(Session::has('success'))
            <div class="alert alert-success">
                <a href="#" class="close" data-dismiss="alert">&times;</a>
                <p>{{ Session::get('success') }}</p>
            </div>
        @endif
        <div>
            <a href="{{ route('scubaya::merchant::create_instructor', [Auth::id(), $diveCenterId]) }}">
                <button type="button" class="pull-right button-blue btn btn-primary">
                    + New
                </button>
            </a>
        </div>

        <div class="box box-primary margin-top-60">
            <div class="box-header with-border">
                <h3 class="box-title">Instructors</h3>
            </div>
            <!-- /.box-header -->

            <div class="box-body table-responsive no-padding">
                <table class="table table-hover">
                    @if(count($instructors) > 0)
                        <thead>
                        <tr>
                            <th>S.No.</th>
                            <th>First Name</th>
                            <th>Last Name</th>
                            <th>Verified</th>
                            <th>Email</th>
                            <th>Nationality</th>
                            <th>Pricing</th>
                            <th>Action</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($instructors as $instructor)
                            <tr>
                                <td>{{ $sno++ }}</td>
                                <td>{{ ucfirst($instructor->first_name) }}</td>
                                <td>{{ ucfirst($instructor->last_name)}}</td>
                                <?php
                                $instructorRoles  = (array)json_decode($instructor->group_id);
                                ?>
                                @foreach($instructorRoles as $key => $value)
                                <td>{!! $value->confirmed ?'<span class="fa fa-check"></span>':'<span class="fa fa-remove"></span>'  !!}</td>
                                @endforeach
                                <td>{{ $instructor->email }}</td>
                                <td>{{ $instructor->nationality }}</td>
                                <td>{{ $instructor->pricing}}</td>
                                <td>
                                    <div class="inline-flex">
                                        <a href="{{ route('scubaya::merchant::edit_instructor', [Auth::id(), $instructor->dive_center_id, $instructor->id]) }}">
                                            <button type="button" class="button-blue btn btn-primary">
                                                <i class="fa fa-pencil"></i>
                                            </button>
                                        </a>

                                        <form class="padding-left5" method="post" action="{{ route('scubaya::merchant::delete_instructor', [Auth::id(), $instructor->dive_center_id, $instructor->id]) }}">
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
                        {{$instructors->links()}}
                    @else
                        <tr>
                            <th class="text-center"> No Instructor Available.</th>
                        </tr>
                    @endif
                </table>

            </div>
        </div>
    </section>

    @include('merchant.layouts.delete_script')
@endsection
