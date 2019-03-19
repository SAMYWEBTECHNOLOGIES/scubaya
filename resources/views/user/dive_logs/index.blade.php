@extends('user.layouts.app')
@section('title','Log New Dive')
@section('contentheader')
    <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> User</a></li>
        <li class="active">Dive Logs</li>
    </ol>
@endsection
@section('content')
    <section class="content dive-logs">
        <div class="row margin-20">
            <div class="col-md-12">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title">My Dive Logs</h3>
                    </div>
                    <div class="box-body table-responsive no-padding">
                        <table class="table table-hover">
                            @if(count($diveLogs) > 0)
                                <thead>
                                    <tr>
                                        <th>S.No.</th>
                                        <th>Log Name</th>
                                        <th>Dive Number</th>
                                        <th>Date</th>
                                        <th>Dive Mode</th>
                                        <th>Training Dive</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                @foreach($diveLogs as $diveLog)
                                    <tr>
                                        <td>{{ $sno++ }}</td>
                                        <td>{{ ucwords($diveLog->log_name) }}</td>
                                        <td>{{ $diveLog->dive_number }}</td>
                                        <td>{{ $diveLog->log_date }}</td>
                                        <td>{{ $diveLog->dive_mode }}</td>
                                        <td>{{ $diveLog->training_dive }}</td>
                                        <td>
                                            <div class="inline-flex">
                                                <a href="{{ route('scubaya::user::dive_logs::update', [Auth::id(), $diveLog->id]) }}">
                                                    <button type="button" class="button-blue btn btn-primary">
                                                        <i class="fa fa-pencil"></i>
                                                    </button>
                                                </a>

                                                <form class="padding-left-5" method="post" action="{{ route('scubaya::user::dive_logs::delete', [Auth::id(), $diveLog->id]) }}">
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
                                {{$diveLogs->links()}}
                            @else
                                <tr>
                                    <th class="text-center"> No dive is logged here.</th>
                                </tr>
                            @endif
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </section>
    @include('user.layouts.delete_script')
@endsection