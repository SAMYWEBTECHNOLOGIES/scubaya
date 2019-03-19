@extends('admin.layouts.app')
@section('title','Instructor')
@section('content')
    <section id="create_room_section" class="padding-20">
        @if(Session::has('success'))
            <div class="alert alert-success">
                <a href="#" class="close" data-dismiss="alert">&times;</a>
                <p>{{ Session::get('success') }}</p>
            </div>
        @endif
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title">Instructors</h3>
            </div>
            <!-- /.box-header -->

            <div class="box-body table-responsive no-padding">
                <table class="table table-hover">
                    @if(count($instructors) > 0)
                        <thead>
                        <tr>
                            <th></th>
                            <th></th>
                            <th>S.No.</th>
                            <th>First Name</th>
                            <th>Last Name</th>
                            <th>Verified</th>
                            <th>Email</th>
                            <th></th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($instructors as $instructor)
                            <tr>
                                <td></td>
                                <td></td>
                                <td>{{ $sno++ }}</td>
                                <td>{{ ucfirst($instructor->first_name) }}</td>
                                <td>{{ ucfirst($instructor->last_name)}}</td>
                                <td>{!! $instructor->confirmed ?'<span class="fa fa-check"></span>':'<span class="fa fa-remove"></span>'  !!}</td>
                                <td>{{ $instructor->email }}</td>
                                <td>
                                    <form method="post" action="{{route('scubaya::admin::delete_instructor',[$instructor->id])}}">
                                        {{csrf_field()}}
                                        <button type="button" class="btn btn-danger">
                                            <i class="fa fa-trash"></i>
                                        </button>
                                    </form>
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
@endsection
