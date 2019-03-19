@extends('merchant.layouts.app')
@section('title', 'New Course')
@section('breadcrumb')
    <li><a href="#">Shop</a></li>
    <li><a href="{{route('scubaya::merchant::shop::shops',[Auth::id()])}}">Manage Shop</a></li>
    <li><a href="{{route('scubaya::merchant::shop::courses',[Auth::id(),$shopId])}}">Courses</a></li>
    <li class="active"><span>Add Course</span></li>
@endsection

@section('content')
    @include('merchant.layouts.mainheader')
    <?php
    use Jenssegers\Agent\Agent;
    $agent  =   new Agent();

    $weekDays   =   [
            0   =>  'Sunday',
            1   =>  'Monday',
            2   =>  'Tuesday',
            3   =>  'Wednesday',
            4   =>  'Thursday',
            5   =>  'Friday',
            6   =>  'Saturday',
    ];

    $productTypes   =   [
        RENTAL_PRODUCT  =>  'Rental',
        SELL_PRODUCT    =>  'Sell'
    ];

    $productBadgesColor =   [
        RENTAL_PRODUCT  =>  'badge-success',
        SELL_PRODUCT    =>  'badge-warning'
    ];

    $currencySymbol   =   config('currency-symbols.symbols');
    ?>

    <section id="create_course_section" class="padding-20">
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title">New Course</h3>
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

            <form role="form" method="post" action="{{ route('scubaya::merchant::shop::create_course', [Auth::id(), $shopId]) }}" enctype="multipart/form-data">
                {{ csrf_field() }}
                <div class="box-body">
                    <div class="row">
                         <div class="col-md-4">
                             <div class="form-group">
                                 <label for="course_name" class="control-label" data-toggle="tooltip" title="Name of course">Course Name</label>
                                 <input type="text" name="course_name" class="form-control" value="{{ old('course_name') }}" placeholder="Enter course name">
                             </div>
                         </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="course_image" class="control-label" data-toggle="tooltip" title="Image of course">Course Image</label>
                                <input type="file" name="course_image" class="form-control">
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="gallery">Upload Gallery (Maximum 20 images) </label>
                                <input type="file" class="form-control"  name="gallery[]" multiple="true">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="course_affiliates" class="control-label" data-toggle="tooltip" title="Specify affiliates">Affiliates</label></br>
                                {{-- TODO : fetch form database --}}
                                @if(count($courseAffiliations) > 0)
                                    @foreach($courseAffiliations as $affiliate)
                                        <input type="checkbox" name="course_affiliates[]" value="{{ $affiliate->id }}" @if(is_array(old('course_affiliates')) && in_array($affiliate->id, old('course_affiliates'))) checked @endif> {{$affiliate->name}} &nbsp;
                                    @endforeach
                                @else
                                    <p>Please contact your administrator to add affiliations.</p>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="dive_center" class="control-label" data-toggle="tooltip" title="Select Dive Center">Dive Center</label>
                                <select name="dive_centers[]" class="form-control selectpicker dive-centers" multiple data-live-search="true">
                                    @if(count($diveCenters) > 0)
                                        @foreach($diveCenters as $diveCenter)
                                            <option value="{{$diveCenter->id}}" @if(is_array(old('dive_centers')) && in_array($diveCenter->id, old('dive_centers'))) selected @endif>{{ ucwords($diveCenter->name) }}</option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="instructors" class="control-label" data-toggle="tooltip" title="Add Instructor">Add Instructor</label>
                                <select name="instructors[]" id="instructors" class="form-control selectpicker" multiple data-live-search="true">
                                </select>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="boats" class="control-label" data-toggle="tooltip" title="Add Boats">Add Boats</label>
                                <select name="boats[]" id="boats" class="form-control selectpicker" multiple data-live-search="true">
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="row margin-bottom-10">
                        <div class="col-md-4">
                            <h4 class="blue">Course Timing</h4>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="course_start_date" class="control-label" data-toggle="tooltip" title="Start date of course">Start Date</label>
                                <input type="text" name="course_start_date" class="datepicker form-control" value="{{ old('course_start_date') }}">
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="course_end_date" class="control-label" data-toggle="tooltip" title="End date of course">End Date</label>
                                <input type="text" name="course_end_date" class="datepicker form-control" value="{{ old('course_end_date') }}">
                            </div>
                        </div>
                    </div>

                    <div class="row margin-bottom-10">
                        <div class="col-md-4">
                            <h5 class="">Set Days</h5>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="course_no_of_days" class="control-label" data-toggle="tooltip" title="Number of days">Number Of Days</label>
                                <input type="text" name="course_no_of_days" class="form-control" value="{{ old('course_no_of_days') }}" placeholder="Enter number of days">
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="course_repeat" class="control-label" data-toggle="tooltip" title="If it is set to yes it will repeat course weekly">Repeat Weekly</label></br>
                                <div class="btn-group" id="status" data-toggle="buttons">
                                    <label class="btn btn-default btn-on btn-sm @if(old('course_repeat') === '1') active @elseif(is_null(old('course_repeat'))) active @endif">
                                    <input type="radio" value="1" name="course_repeat" @if(old('course_repeat') === '1') checked @elseif(is_null(old('course_repeat'))) checked @endif>ON</label>

                                    <label class="btn btn-default btn-off btn-sm @if(old('course_repeat') === '0') active @endif">
                                    <input type="radio" value="0" name="course_repeat" @if(old('course_repeat') === '0') checked @endif>OFF</label>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="course_start_day" class="control-label" data-toggle="tooltip" title="Start day of course">Start Day</label></br>
                                @foreach($weekDays as $key => $value)
                                    <input type="checkbox" name="course_start_day[]" value="{{$value}}" @if(is_array(old('course_start_day')) && in_array($value, old('course_start_day'))) checked @endif> {{ $value }} &nbsp;
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <div class="row margin-bottom-10">
                        <div class="col-md-4">
                            <h4 class="blue">Course Pricing</h4>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="min_people_for_course" class="control-label" data-toggle="tooltip" title="Minimum People">Minimum People</label>
                                <select name="min_people_for_course" class="form-control">
                                    <?php for($i = 1; $i <= config('scubaya.min_people_for_course'); $i++){ ?>
                                    <option value="{{ $i }}" @if($i == old('min_people_for_course')) selected @endif>{{ $i }}</option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="max_people_for_course" class="control-label" data-toggle="tooltip" title="Maximum People">Maximum People</label>
                                <select name="max_people_for_course" class="form-control">
                                    <?php for($i = 1; $i <= config('scubaya.max_people_for_course'); $i++){ ?>
                                    <option value="{{ $i }}" @if(!empty(old('max_people_for_course')) && old('max_people_for_course') == $i) selected @endif>{{ $i }}</option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="course_min_age" class="control-label" data-toggle="tooltip" title="Minimum Age">Minimum Age</label>
                                <input type="text" name="course_min_age" class="form-control" value="{{ old('course_min_age') }}" placeholder="Enter Minimum Age">
                            </div>
                        </div>
                    </div>

                    <div class="row margin-bottom-10">
                        <div class="col-md-4">
                             <label for="course_price" class="control-label" data-toggle="tooltip" title="Course Price">Price</label>
                             <input type="text" name="course_price" class="form-control" value="{{ old('course_price') }}" placeholder="Enter Price">

                            {{--<label for="course_price" class="control-label" data-toggle="tooltip" title="Set Price For Courses">Set Price</label>
                            <table width="100%" class="table table-striped table-responsive course_pricing">
                                <thead>
                                <tr>
                                    <td>&nbsp;</td>
                                    <td>&nbsp;</td>
                                    <td>
                                        <div class="btn btn-sm btn-default">Mo</div>
                                    </td>
                                    <td>
                                        <div class="btn btn-sm btn-default">Tu</div>
                                    </td>
                                    <td>
                                        <div class="btn btn-sm btn-default">We</div>
                                    </td>
                                    <td>
                                        <div class="btn btn-sm btn-default">Th</div>
                                    </td>
                                    <td>
                                        <div class="btn btn-sm btn-default">Fr</div>
                                    </td>
                                    <td>
                                        <div class="btn btn-sm btn-default">Sa</div>
                                    </td>
                                    <td>
                                        <div class="btn btn-sm btn-default">Su</div>
                                    </td>
                                </tr>
                                </thead>
                                <tbody>
                                <tr>
                                    <td>
                                    </td><td>
                                        <small class="muted price_title">per person</small>
                                    </td>
                                    <td>
                                        <input type="number" class="course_mo_price width-100 input-rate" name="course_price[mo]" value="@if(old('course_price')['mo']){{old('course_price')['mo']}}@else{{100}}@endif">
                                    </td>
                                    <td>
                                        <input type="number" class="course_tu_price width-100 input-rate" name="course_price[tu]" value="@if(old('course_price')['tu']){{old('course_price')['tu']}}@else{{100}}@endif">
                                    </td>
                                    <td>
                                        <input type="number" class="course_we_price width-100 input-rate"  name="course_price[we]" value="@if(old('course_price')['we']){{old('course_price')['we']}}@else{{100}}@endif">
                                    </td>
                                    <td>
                                        <input type="number" class="course_th_price width-100 input-rate" name="course_price[th]" value="@if(old('course_price')['th']){{old('course_price')['th']}}@else{{100}}@endif">
                                    </td>
                                    <td>
                                        <input type="number" class="course_fr_price width-100 input-rate" name="course_price[fr]" value="@if(old('course_price')['fr']){{old('course_price')['fr']}}@else{{100}}@endif">
                                    </td>
                                    <td>
                                        <input type="number" class="course_sa_price width-100 input-rate" name="course_price[sa]" value="@if(old('course_price')['sa']){{old('course_price')['sa']}}@else{{100}}@endif">
                                    </td>
                                    <td>
                                        <input type="number" class="course_su_price width-100 input-rate" name="course_price[su]" value="@if(old('course_price')['su']){{old('course_price')['su']}}@else{{100}}@endif">
                                    </td>
                                </tr>
                                </tbody>
                            </table>--}}
                        </div>
                    </div>

                    <div class="row margin-bottom-10">
                        <div class="col-md-4">
                            <h4 class="blue">Add Location</h4>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="address" class="control-label" data-toggle="tooltip" title="Specify Address">Address</label>
                                <input type="text" name="address" class="form-control" value="{{ old('address') }}" id="address">
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="latitude" class="control-label" data-toggle="tooltip" title="Specify Latitude">Latitude</label>
                                <input type="text" name="latitude" class="form-control" value="{{ old('latitude') }}" id="latitude">
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="longitude" class="control-label" data-toggle="tooltip" title="Specify Longitude">Longitude</label>
                                <input type="text" name="longitude" class="form-control" value="{{ old('longitude') }}" id="longitude">
                            </div>
                        </div>
                    </div>

                    <div class="row margin-bottom-10">
                        <div class="col-md-12">
                            <div id="location" style="width: 100%; height: 500px"></div>
                        </div>
                    </div>

                    <div class="row margin-bottom-10">
                        <div class="col-md-4">
                            <h4 class="blue">Add Description</h4>
                        </div>
                    </div>

                    <div class="row margin-bottom-10">
                        <div class="col-md-12">
                            <div class="form-group">
                                <textarea class="form-control" id="course_description" placeholder="Enter Course Description" name="course_description">{{ old('course_description') }}</textarea>
                            </div>
                        </div>
                    </div>
                    <div class="row margin-bottom-10">
                        <div class="col-md-4">
                            <h4 class="blue">Add Cancellation Detail</h4>
                        </div>
                    </div>

                    <div class="row margin-bottom-10">
                        <div class="col-md-12">
                            <div class="form-group">
                                <textarea class="form-control" id="cancellation_detail" placeholder="Enter cancellation detail" name="cancellation_detail">{{ old('cancellation_detail') }}</textarea>
                            </div>
                        </div>
                    </div>

                    <div class="row margin-bottom-10">
                        <div class="col-md-4">
                            <h4 class="blue">Add Products</h4>
                        </div>
                    </div>

                    <div class="products-section">
                        @if(count($products) > 0)
                            @foreach($products as $product)
                                <div class="panel panel-default product-item @if($product->product_type == 1) aliceblue @else floralwhite @endif">
                                    <span class="product-badge {{$productBadgesColor[$product->product_type]}}">{{ ucwords($productTypes[$product->product_type]) }}</span>
                                    <div class="panel-body">
                                        <div class="col-md-2 col-md-offset-1">
                                            <img src="{{asset('assets/images/scubaya/shop/products/'.$product->merchant_key.'/'.$product->id.'-'.$product->product_image)}}" class="img-responsive @if($agent->isMobile()) margin-auto @endif" alt="{{$product->title}}">
                                        </div>

                                        <div class="col-md-3 text-center">
                                            <h5><strong>{{ $product->title }}</strong></h5>
                                            @if($merchantCurrency && $merchantCurrency->mcurrency)
                                                <p class="blue"><span>{{$currencySymbol[$merchantCurrency->mcurrency]}}{{ $product->price }}</span></p>
                                            @else
                                                <p class="blue"><span>{{$currencySymbol['EUR']}}{{ $product->price }}</span></p>
                                            @endif
                                        </div>

                                        <div class="col-md-3">
                                            <div class="form-group">
                                                 <label class="control-label col-md-4">Required</label>
                                                 <div class="col-md-6">
                                                     <select name="product_in_course[{{$product->id}}][required]" class="form-control product_required" id="{{$product->id}}" onclick="showExcludedIncluded(this)">
                                                         <option value="1">Yes</option>
                                                         <option value="0" selected>No</option>
                                                     </select>
                                                 </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        @else
                            Click Here To: <a href="{{ route('scubaya::merchant::shop::shops', Auth::id()) }}">Add Product</a>
                        @endif
                    </div>

                    <div class="box-footer">
                        <a href="{{ route('scubaya::merchant::shop::courses', [Auth::id(), $shopId]) }}"><button type="button" class="btn btn-default">Cancel</button></a>
                        <button type="submit" class="btn btn-info pull-right">Create</button>
                    </div>
                </div>
            </form>
        </div>
    </section>
    <?php
        $clientGeoInfo =   geoip($_SERVER['REMOTE_ADDR']);
    ?>
    @include('merchant.layouts.course_script', [
        'clientGeoInfo' =>  $clientGeoInfo,
        'key'           =>  Auth::id(),
        'boats'         =>  old('boats'),
        'instructors'   =>  old('instructors')
    ])
@endsection