@extends('merchant.layouts.app')
@section('title', 'Hotel General Information')
@section('breadcrumb')
    <li><a href="#">Hotel</a></li>
    <li class="active"><a href="{{route('scubaya::merchant::hotels',[Auth::id()])}}">Manage Hotel</a></li>
    <li class="active"><span>Add Hotel</span></li>
@endsection

@section('content')
    @include('merchant.layouts.mainheader')

    <section id="create_hotel_information_section" class="padding-20">
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title">Hotel General Information</h3>
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

            <div class="box-body">
                <form name="hotel_information_form" method="post" action="{{ route('scubaya::merchant::save_hotel', [Auth::id()]) }}" enctype="multipart/form-data">
                    {{csrf_field()}}
                    <?php $hotel =   session()->get('hotel'); ?>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="hotel_name">Name</label>
                                <input type="text" class="form-control" id="hotel_name" placeholder="Enter Name" name="hotel_name" value="{{ old('hotel_name') ? old('hotel_name'): @$hotel->name}}">
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="image">Upload Image</label>
                                <input type="file" class="form-control" name="image">
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="images_for_gallery" data-toggle="tooltip" title="Upload images of the room gallery">@lang('merchant_hotel.upload_images_gallery')</label>
                                <input type="file" class="form-control" id="images_for_gallery" name="images_for_gallery[]" multiple="true">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="hotel_address">Address</label>
                                <input type="text" class="form-control" id="hotel_address" placeholder="Enter Address" name="hotel_address" value="{{ old('hotel_address') ? old('hotel_address'): @$hotel->address }}">
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="hotel_city">City</label>
                                <input type="text" class="form-control" id="locality" placeholder="Enter City" name="hotel_city" value="{{ old('hotel_city') ? old('hotel_city'): @$hotel->city }}">
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="hotel_state">State</label>
                                <input type="text" class="form-control" id="administrative_area_level_1" placeholder="Enter State" name="hotel_state" value="{{ old('hotel_state') ? old('hotel_state'): @$hotel->state }}">
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="hotel_country">Country</label>
                                <input type="text" class="form-control" id="country" placeholder="Enter Country" name="hotel_country" value="{{ old('hotel_country') ? old('hotel_country'): @$hotel->country }}">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="hotel_zip_code">Zip Code</label>
                                <input type="text" class="form-control" id="postal_code" placeholder="Enter Zipcode" name="hotel_zip_code" value="{{ old('hotel_zip_code') ? old('hotel_zip_code'): @$hotel->zipcode }}">
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="hotel_longitude">Longitude</label>
                                <input type="text" class="form-control" id="hotel_longitude"  name="hotel_longitude" value="{{ old('hotel_longitude') ? old('hotel_longitude'): @$hotel->longitude }}">
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="hotel_latitude">Latitude</label>
                                <input type="text" class="form-control" id="hotel_latitude"  name="hotel_latitude" value="{{ old('hotel_latitude') ? old('hotel_latitude'): @$hotel->latitude }}">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <h4>Hotel Description</h4>
                        </div>
                    </div>

                    <div class="row margin-bottom-10">
                        <div class="col-md-12">
                            <div class="form-group">
                                <textarea class="form-control" placeholder="Enter Hotel Description" id="hotel_description" name="hotel_description">{{ old('hotel_desc') ? old('hotel_desc'): @$hotel->hotel_desc }}</textarea>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <h4>Hotel Policies</h4>
                        </div>
                    </div>

                    <div class="row">
                        @if(count($hotelPolicies))
                            @foreach($hotelPolicies as $policy)
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <div class="form-group">
                                            <label for="{{str_replace(' ','_', $policy->name)}}">{{ ucwords($policy->name) }}</label>
                                            <input type="text" class="form-control @if($policy->name == 'check in' || $policy->name == 'check out') datetimepicker3 @endif" id="{{str_replace(' ','_', $policy->name)}}" name="hotel_policies[{{str_replace(' ','_', $policy->name)}}]">
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <div class="col-md-4">
                                <p>Please contact your administrator to add policy fields.</p>
                            </div>
                        @endif
                    </div>

                    <div class="box-footer">
                        <a href="{{ route('scubaya::merchant::hotels', [Auth::id()]) }}"><button type="button" class="btn btn-default">Cancel</button></a>
                        <button type="submit" class="btn btn-info pull-right" id="submit" data-toggle="modal" data-target="#verification-form-modal{{@$hotel->id}}">Save</button>
                    </div>
                </form>

                {{-- include verification model --}}
                {{--@if(session()->get('show_popup') == 'true' || $errors->verificationError->any())
                    @include('merchant.layouts.website_verification.verification_modal', ['route1' => 'scubaya::merchant::hotel::verification',
                    'route2' => 'scubaya::merchant::hotels','website' => session()->get('hotel')])
                @endif--}}
            </div>
        </div>
    </section>
    {{--<script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAm_-PodAPns0u0-bvF3qHHV3G_sLe0gdI&libraries=places"></script>--}}
    <script type="text/javascript">
        @if(session()->get('show_popup') == 'true' || $errors->verificationError->any())
        jQuery('#submit').attr('type', 'button');
        var modelId =   jQuery('#submit').attr('data-target');
        jQuery(modelId).modal('show');
        @endif

        jQuery(document).ready(function($){
            //initialize();

            $('.datetimepicker3').datetimepicker({
                format: 'LT'
            });

            $('#hotel_description').summernote({
                height: 300,
            });
        });
    </script>
@endsection