@extends('merchant.layouts.app')
@section('title', 'Dive Day Planning')
@section('content')
    @include('merchant.layouts.mainheader')
    <section class="padding-20">
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title">Dive Day Planning</h3>
            </div>
            <!-- /.box-header -->
            <!-- form start -->
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

            <form role="form" enctype="multipart/form-data" method="post" action="{{route('scubaya::merchant::dive_center::save_dive_day_planning',[Auth::id(), $diveCenterId])}}">
                {{ csrf_field() }}
                <div class="box-body">
                    <div class="row">
                        <div class="form-group col-md-3">
                            <label for="title" data-toggle="tooltip">Title</label>
                            <input type="text" class="form-control" id="title" name="title">
                        </div>
                        <div class="form-group col-md-3">
                            <div class="form-group">
                                <label for="night_dive" class="control-label" data-toggle="tooltip">Is Night Dive</label><br>
                                <div class="btn-group" data-toggle="buttons">
                                    <label class="btn btn-default btn-on btn-sm active">
                                        <input type="radio" value="1" name="night_dive" checked>YES</label>

                                    <label class="btn btn-default btn-off btn-sm @if(old('course_repeat') === '0') active @endif">
                                        <input type="radio" value="0" name="night_dive" @if(old('course_repeat') === '0') checked @endif>NO</label>
                                </div>
                            </div>
                        </div>
                        <div class="form-group col-md-3">
                            <label for="dive_number" data-toggle="tooltip">Dive Number</label>
                            <input type="text" class="form-control" name="dive_number">
                        </div>

                    </div>

                    <div class="row">
                        <div class="form-group col-md-3">
                            <label for="date" data-toggle="tooltip">Select Date</label>
                            <input type="text" data-date-format="yyyy/mm/dd" class="form-control datepicker" id="date" name="date">
                        </div>

                        <div class="form-group col-md-3">
                            <label for="start_time" data-toggle="tooltip">Start Time</label>
                            <input type="text" class="form-control  datetimepicker3 " id="start_time" name="start_time">
                        </div>

                        <div class="form-group col-md-3">
                            <label for="end_time" data-toggle="tooltip">End Time</label>
                            <input type="text" class="form-control  datetimepicker3 " id="end_time" name="end_time">
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="from-group combination" data-comb="1" style="width: 550px;height:100px;border: 2px solid;padding: 5px;margin: 25px;">

                            </div>
                            <button type="button" class="add-pool btn-sm btn btn-default pull-right">Add Pool</button>
                        </div>
                        <div class="col-md-6">

                            <label>Divers</label>
                            <div id="divers" class="from-group dive-day-planning-box">
                                @if($divers)
                                    @foreach($divers as $diver)
                                        <span id="{{$diver->id.'_'.'diver'}}" data-scope="divers" data-name="di" class="col-md-1" >
{{--                                             <span class="margin-bottom-5">{{$diver->first_name}}</span>--}}
                                             <img src="{{asset('assets/images/dive-icons/Diver-icon.png')}}" style="width: 40px;height: 40px" data-toggle="tooltip" title="{{$diver->email}}">
                                        </span>
                                    @endforeach
                                @endif
                            </div>

                            <label >Instructors</label>
                            <div id="instructors" class="from-group dive-day-planning-box" >
                                @if($instructors)
                                    @foreach($instructors as $instructor)
                                        <span id="{{$instructor->id.'_'.'instructor'}}" data-scope="instructors" data-name="in">
                                            {{--<span >{{$instructor->first_name}}</span>--}}
                                            <img src="{{asset('assets/images/dive-icons/Instructor-icon.png')}}" style="width: 40px;height: 40px" data-toggle="tooltip" title="Instructor - {{$instructor->email}}">
                                        </span>
                                    @endforeach
                                @endif
                            </div>

                            <label>Locations</label>
                            <div id="locations" class="from-group dive-day-planning-box">
                                @if($locations)
                                    @foreach($locations as $location)
                                        <span id="{{$location->id.'_'.'location'}}" data-scope="locations" data-name="lo">
                                            {{$location->name}}
                                            <img src="{{asset('assets/images/dive-icons/Location-icon.png')}}" style="width: 20px;height: 20px" data-toggle="tooltip" title="{{$location->name}}">
                                        </span>
                                    @endforeach
                                @endif
                            </div>

                            <label>Boats</label>
                            <div id="boats" class="from-group dive-day-planning-box" >
                                @if($boats)
                                    @foreach($boats as $boat)
                                        <span id="{{$boat->id.'_'.'boat'}}" data-scope="boats" data-name="bo" class="col-md-1">
                                           <img src="{{asset('assets/images/dive-icons/boat-icon.png')}}" style="width: 40px;height: 40px" data-toggle="tooltip" title="{{$boat->name}}">
                                        </span>
                                    @endforeach
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <div class="box-footer">
                    <a href="{{ route('scubaya::merchant::dashboard',[ Auth::id()])}}"><button type="button" class="btn btn-default">Cancel</button></a>
                    <button type="submit" class="btn btn-info pull-right">Create</button>
                </div>
            </form>

        </div>
    </section>
    {{--drag and drop script--}}
    @include('merchant.dive_center.dive_day_planning.planning_script')
@stop