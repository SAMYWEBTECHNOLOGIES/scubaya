@extends('merchant.layouts.app')
@section('title', 'Manage Bookings')
@section('breadcrumb')
    <li><a href="#">Hotel</a></li>
    <li><a href="{{route('scubaya::merchant::hotels',[Auth::id()])}}">Manage Hotel</a></li>
    <li class="active"><span>Manage Bookings</span></li>
@endsection

@section('content')
    @include('merchant.layouts.mainheader')

    <section id="rooms_section" class="padding-20">
        <button type="button" class="pull-right button-blue btn btn-primary add-new-button" data-toggle="modal" data-target="#bookingModal">
            + Mark Booking
        </button>

        <!-- Modal -->
        <div class="modal fade" id="bookingModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title" id="myModalLabel">Mark Booking</h4>
                    </div>
                    <div class="modal-body">
                        @if ($errors->any())
                            <div class="row margin-top-10">
                                <div class="col-md-8 col-md-offset-2 alert alert-danger">
                                    <ul>
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        @endif

                        <form name="booking_form" action="{{ route('scubaya::merchant::save_mark_bookings', [Auth::id(), $hotel_id]) }}" method="post">
                            {{ csrf_field() }}
                             <div class="row">
                                 <div class="col-md-8 col-md-offset-2">
                                     <div class="form-group">
                                         <label>Start Date</label>
                                         <input class="form-control datepicker" name="start_date" placeholder="Select Start Date">
                                     </div>

                                     <div class="form-group">
                                         <label>End Date</label>
                                         <input class="form-control datepicker" name="end_date" placeholder="Select End Date">
                                     </div>

                                     <div class="form-group">
                                         <label>Room</label>
                                         <select class="form-control selectpicker" name="room[]" multiple>
                                             @if(count($rooms))
                                                 @foreach($rooms as $room)
                                                     <option value="{{ $room->id }}">{{ $room->name }}</option>
                                                 @endforeach
                                             @endif
                                         </select>
                                     </div>

                                     <div class="form-group">
                                         <label>Booked</label><br>
                                         <div class="btn-group" id="status" data-toggle="buttons">
                                             <label class="btn btn-default btn-on btn-sm {{--@if(@$settings->tariff_model === '1') active @elseif(is_null(@$settings->tariff_model)) active @endif--}}">
                                                 <input type="radio" value="1"  name="status" {{--@if(@$settings->tariff_model === '1') checked @elseif(is_null(@$settings->tariff_model)) checked @endif--}}>Yes</label>

                                             <label class="btn btn-default btn-off btn-sm {{--@if(@$settings->tariff_model === '0') active @endif--}}">
                                                 <input type="radio" value="0" name="status" {{--@if(@$settings->tariff_model === '0') checked @endif--}}>No</label>
                                         </div>
                                     </div>
                                 </div>
                             </div>

                            <div class="row modal-footer">
                                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                <button type="submit" class="btn btn-primary">Save</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        {{-- represent bookings --}}
        <div class="box box-primary margin-top-60">
            <div class="box-header with-border">
                <h3 class="box-title">Mark Bookings</h3>
            </div>
            <!-- /.box-header -->

            <div class="box-body table-responsive no-padding">
                <table class="table table-hover">
                    {{--@if(count($roomTypes) > 0)--}}
                        <thead>
                        <tr>
                            <th>S.No.</th>
                            <th>Start Date</th>
                            <th>End Date</th>
                            <th>Rooms</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                        </thead>

                        <tbody>
                        </tbody>
                    {{--@endif--}}
                </table>
            </div>
        </div>
    </section>

    <script type="text/javascript">
        jQuery(document).ready(function(){
            jQuery('.datepicker').datepicker({
                format: 'mm-dd-yyyy'
            });

            @if ($errors->any())
            jQuery('#bookingModal').modal('show');
            @endif
        });
    </script>
@endsection