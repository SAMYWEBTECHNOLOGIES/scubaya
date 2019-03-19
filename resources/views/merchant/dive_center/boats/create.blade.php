@extends('merchant.layouts.app')
@section('title', 'New Boat')
@section('breadcrumb')
    <li><a href="#">Dive Center</a></li>
    <li><a href="{{route('scubaya::merchant::dive_center::dive_centers',[Auth::id()])}}">Manage Dive Centers</a></li>
    <li><a href="{{route('scubaya::merchant::dive_center::boats',[Auth::id(),$diveCenterId])}}">Boats</a></li>
    <li class="active"><span>Add Boat</span></li>
@endsection

@section('content')
    @include('merchant.layouts.mainheader')

    <section id="create_boat_section" class="padding-20">
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title">New Boat</h3>
            </div>
            <!-- /.box-header -->
            <!-- form start -->
            @if ($errors->any())
                <div class="row margin-top-10">
                    <div class="col-md-4 col-md-offset-4 alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            @endif

            <form role="form" enctype="multipart/form-data" method="post" action="{{ route('scubaya::merchant::dive_center::create_boat', [Auth::id(), $diveCenterId]) }}">
                {{ csrf_field() }}
                <div class="box-body">
                    <div class="row">
                        <div class="col-md-4 col-md-offset-1">
                            <div class="form-group">
                                <label for="is_boat_active" class="control-label" data-toggle="tooltip" title="Specify Boat Is Active Or Not">Active</label></br>
                                <div class="btn-group" id="status" data-toggle="buttons">
                                    <label class="btn btn-default btn-on btn-sm @if(old('is_boat_active') === '1') active @elseif(is_null(old('is_boat_active'))) active @endif">
                                        <input type="radio" value="1" name="is_boat_active" @if(old('is_boat_active') === '1') checked @elseif(is_null(old('is_boat_active'))) checked @endif>Yes</label>

                                    <label class="btn btn-default btn-off btn-sm @if(old('is_boat_active') === '0') active @endif">
                                        <input type="radio" value="0" name="is_boat_active" @if(old('is_boat_active') === '0') checked @endif>No</label>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="boat_name" class="control-label" data-toggle="tooltip" title="Name Of Boat">Name*</label>
                                <input type="text" name="boat_name" class="form-control" value="{{ old('boat_name') }}" placeholder="Enter name">
                            </div>

                            <div class="form-group">
                                <label for="max_passengers" class="control-label" data-toggle="tooltip" title="Specify Maximum Number Of Passengers In Boat">Maximum Passengers</label>
                                <select name="max_passengers" class="form-control">
                                    <?php for($i = 1; $i <= config('scubaya.max_passengers_in_boat'); $i++){ ?>
                                    <option value="{{ $i }}" @if($i == old('max_passengers')) selected @endif>{{ $i }}</option>
                                    <?php } ?>
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="boat_engine_power" class="control-label" data-toggle="tooltip" title="Name Of Boat">Engine Power*</label>
                                <input type="text" name="boat_engine_power" class="form-control" value="{{ old('boat_engine_power') }}" placeholder="Enter Engine Power">
                            </div>

                            <div class="form-group">
                                <label for="boat_type" class="control-label" data-toggle="tooltip" title="Specify Type Of Boat">Type*</label>
                                <select name="boat_type" class="form-control">
                                    {{-- TODO: fetch this from admin --}}
                                    <option value="">-- Select Type --</option>
                                    @foreach($boat_types as $boatTypesOptions)
                                    <option value="{{$boatTypesOptions->name}}">{{$boatTypesOptions->name}}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="boat_driver" class="control-label" data-toggle="tooltip" title="Driver Of Boat">Driver*</label>
                                <select name="boat_driver" class="form-control">
                                    <option value="" selected disabled>-- Select Driver --</option>
                                    @if(count($drivers) > 0)
                                        @foreach($drivers as $driver)
                                            <option value="{{ $driver->id }}" @if(old('boat_driver') == $driver->id) selected @endif>{{ $driver->first_name.' '.$driver->last_name.' ('.$driver->email.')' }}</option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                        </div>

                        <div class="col-md-4 col-md-offset-1">
                            <div class="form-group">
                                <label for="boat_image" class="control-label" data-toggle="tooltip" title="Upload Image Of Boat"><i class="fa fa-upload" aria-hidden="true"></i> Upload Image</label>
                                <input type="file" name="boat_image" class="form-control" onchange="readURL(this);">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="box-footer">
                    <a href="{{ route('scubaya::merchant::dive_center::boats', [Auth::id(), $diveCenterId]) }}"><button type="button" class="btn btn-default">Cancel</button></a>
                    <button type="submit" class="btn btn-info pull-right">Create</button>
                </div>
            </form>
        </div>
    </section>

    {{--<script type="text/javascript">
        function readURL(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();

                reader.onload = function (e) {
                    jQuery(input).after('<div><img class="boat-image text-center padding-20"  src="'+e.target.result+'" width="200px" height="200px"></div>');
                };

                reader.readAsDataURL(input.files[0]);
            }
        }
    </script>--}}
@endsection