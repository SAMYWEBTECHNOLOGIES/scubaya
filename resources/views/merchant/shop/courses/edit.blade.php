@extends('merchant.layouts.app')
@section('title', 'Edit Course')
@section('breadcrumb')
    <li><a href="#">Shop</a></li>
    <li><a href="{{route('scubaya::merchant::shop::shops',[Auth::id()])}}">Manage Shop</a></li>
    <li><a href="{{route('scubaya::merchant::shop::courses',[Auth::id(),$shopId])}}">Courses</a></li>
    <li class="active"><span>{{ $course->course_name }}</span></li>
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
            '1' =>  'rental',
            '2' =>  'sell'
    ];

    $productBadgesColor =   [
            '1' =>  'badge-success',
            '2' =>  'badge-warning'
    ];

    $currencySymbol   =   config('currency-symbols.symbols');
    ?>

    <section id="edit_course_section" class="padding-20">
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title">Edit Course</h3>
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

            <form role="form" method="post" action="{{ route('scubaya::merchant::shop::edit_course', [Auth::id(), $shopId, $course->id]) }}" enctype="multipart/form-data">
                {{ csrf_field() }}
                <div class="box-body">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="course_name" class="control-label" data-toggle="tooltip" title="Name of course">Course Name</label>
                                <input type="text" name="course_name" class="form-control" value="{{ $course->course_name }}" placeholder="Enter course name">
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
                                @if($courseAffiliations)
                                    @foreach($courseAffiliations as $affiliate)
                                        <input type="checkbox" name="course_affiliates[]" value="{{ $affiliate->id }}" @if(in_array($affiliate->id, (array)json_decode($course->affiliates))) checked @endif> {{$affiliate->name}} &nbsp;
                                    @endforeach
                                @else
                                    <p>Please contact your administrator to add affiliations.</p>
                                @endif
                            </div>
                        </div>
                    </div>

                    @php
                        $centers    =   (array)json_decode($course->dive_center);
                    @endphp
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="dive_center" class="control-label" data-toggle="tooltip" title="Select Dive Center">Dive Center</label>
                                <select name="dive_centers[]" class="form-control selectpicker dive-centers" multiple data-live-search="true">
                                    @if($diveCenters)
                                        @foreach($diveCenters as $diveCenter)
                                            <option value="{{$diveCenter->id}}" @if(in_array($diveCenter->id, $centers)) selected @endif>{{ ucwords($diveCenter->name) }}</option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="instructors" class="control-label" data-toggle="tooltip" title="Add Instructor">Add Instructor</label>
                                <select name="instructors[]" id="instructors" class="form-control selectpicker" multiple data-live-search="true">
                                    {{--@if(count($instructors) > 0)
                                        @foreach($instructors as $instructor)
                                            <option value="{{$instructor->id}}" @if(in_array($instructor->id, (array)json_decode($course->instructors))) selected @endif>{{ ucwords($instructor->first_name.' '.$instructor->last_name) }}</option>
                                        @endforeach
                                    @endif--}}
                                </select>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="boats" class="control-label" data-toggle="tooltip" title="Add Boats">Add Boats</label>
                                <select name="boats[]" id="boats" class="form-control selectpicker" multiple data-live-search="true">
                                    {{--@if(count($boats) > 0)
                                        @foreach($boats as $boat)
                                            <option value="{{$boat->id}}" @if(in_array($boat->id, (array)json_decode($course->boats))) selected @endif>{{ ucwords($boat->name) }}</option>
                                        @endforeach
                                    @endif--}}
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
                                <input type="text" name="course_start_date" class="datepicker form-control" value="{{ $course->course_start_date }}">
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="course_end_date" class="control-label" data-toggle="tooltip" title="End date of course">End Date</label>
                                <input type="text" name="course_end_date" class="datepicker form-control" value="{{ $course->course_end_date }}">
                            </div>
                        </div>
                    </div>

                    <div class="row margin-bottom-10">
                        <div class="col-md-4">
                            <h5 class="">Set Days</h5>
                        </div>
                    </div>

                    <?php $courseDays   =   (array)json_decode($course->course_days); ?>

                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="course_no_of_days" class="control-label" data-toggle="tooltip" title="Number of days">Number Of Days</label>
                                <input type="text" name="course_no_of_days" class="form-control" value="{{ $courseDays['no_of_days'] }}" placeholder="Enter number of days">
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="course_repeat" class="control-label" data-toggle="tooltip" title="If it is set to yes it will repeat course weekly">Repeat Weekly</label></br>
                                <div class="btn-group" id="status" data-toggle="buttons">
                                    <label class="btn btn-default btn-on btn-sm @if($courseDays['course_repeat'] == 1) active @endif">
                                        <input type="radio" value="1" name="course_repeat" @if($courseDays['course_repeat'] == 1) checked @endif>ON</label>

                                    <label class="btn btn-default btn-off btn-sm @if($courseDays['course_repeat'] == 0) active @endif">
                                        <input type="radio" value="0" name="course_repeat" @if($courseDays['course_repeat'] == 0) checked @endif>OFF</label>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="course_start_day" class="control-label" data-toggle="tooltip" title="Start day of course">Start Day</label></br>
                                @foreach($weekDays as $key => $value)
                                    <input type="checkbox" name="course_start_day[]" value="{{$value}}" @if(in_array($value, (array)json_decode($courseDays['course_start_day']))) checked @endif> {{ $value }} &nbsp;
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <div class="row margin-bottom-10">
                        <div class="col-md-4">
                            <h4 class="blue">Course Pricing</h4>
                        </div>
                    </div>

                    <?php $coursePricing   =   (array)json_decode($course->course_pricing); ?>

                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="min_people_for_course" class="control-label" data-toggle="tooltip" title="Minimum People">Minimum People</label>
                                <select name="min_people_for_course" class="form-control">
                                    <?php for($i = 1; $i <= config('scubaya.min_people_for_course'); $i++){ ?>
                                        <option value="{{ $i }}" @if($i == $coursePricing['min_people']) selected @endif>{{ $i }}</option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="max_people_for_course" class="control-label" data-toggle="tooltip" title="Maximum People">Maximum People</label>
                                <select name="max_people_for_course" class="form-control">
                                    <?php for($i = 1; $i <= config('scubaya.max_people_for_course'); $i++){ ?>
                                    <option value="{{ $i }}" @if($i == $coursePricing['max_people']) selected @endif>{{ $i }}</option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="course_min_age" class="control-label" data-toggle="tooltip" title="Minimum Age">Minimum Age</label>
                                <input type="text" name="course_min_age" class="form-control" value="{{ $coursePricing['min_age'] }}" placeholder="Enter Minimum Age">
                            </div>
                        </div>
                    </div>

                    <div class="row margin-bottom-10">
                        <div class="col-md-4">
                            <label for="course_price" class="control-label" data-toggle="tooltip" title="Course Price">Price</label>
                            <input type="text" name="course_price" class="form-control" value="{{ $coursePricing['price'] }}" placeholder="Enter Price">
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
                                        <input type="number" class="course_mo_price width-100 input-rate" name="course_price[mo]" value="{{ $prices['mo'] }}">
                                    </td>
                                    <td>
                                        <input type="number" class="course_tu_price width-100 input-rate" name="course_price[tu]" value="{{ $prices['tu'] }}">
                                    </td>
                                    <td>
                                        <input type="number" class="course_we_price width-100 input-rate"  name="course_price[we]" value="{{ $prices['we'] }}">
                                    </td>
                                    <td>
                                        <input type="number" class="course_th_price width-100 input-rate" name="course_price[th]" value="{{ $prices['th'] }}">
                                    </td>
                                    <td>
                                        <input type="number" class="course_fr_price width-100 input-rate" name="course_price[fr]" value="{{ $prices['fr'] }}">
                                    </td>
                                    <td>
                                        <input type="number" class="course_sa_price width-100 input-rate" name="course_price[sa]" value="{{ $prices['sa'] }}">
                                    </td>
                                    <td>
                                        <input type="number" class="course_su_price width-100 input-rate" name="course_price[su]" value="{{ $prices['su'] }}">
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

                    <?php $location   =   (array)json_decode($course->location); ?>

                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="address" class="control-label" data-toggle="tooltip" title="Specify Address">Address</label>
                                <input type="text" name="address" class="form-control" value="{{ $location['address'] }}" id="address">
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="latitude" class="control-label" data-toggle="tooltip" title="Specify Latitude">Latitude</label>
                                <input type="text" name="latitude" class="form-control" value="{{ $location['lat'] }}" id="latitude">
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="longitude" class="control-label" data-toggle="tooltip" title="Specify Longitude">Longitude</label>
                                <input type="text" name="longitude" class="form-control" value="{{ $location['long'] }}" id="longitude">
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
                                <textarea class="form-control" id="course_description" placeholder="Enter Course Description" name="course_description">{!! $course->description !!}</textarea>
                            </div>
                        </div>
                    </div>

                    <div class="row margin-bottom-10">
                        <div class="col-md-4">
                            <h4 class="blue">Cancellation detail</h4>
                        </div>
                    </div>

                    <div class="row margin-bottom-10">
                        <div class="col-md-12">
                            <div class="form-group">
                                <textarea class="form-control" id="cancellation_detail" placeholder="Enter cancellation detail" name="cancellation_detail">{!! $course->cancellation_detail !!}</textarea>
                            </div>
                        </div>
                    </div>

                    <div class="row margin-bottom-10">
                        <div class="col-md-4">
                            <h4 class="blue">Add Products</h4>
                        </div>
                    </div>

                    <?php
                        $courseProducts   =   (array)json_decode($course->products);
                        $Products         =    array();

                        if($courseProducts){
                            foreach($courseProducts as $key1 => $value1){
                                $courseProduct  =   (json_decode(json_encode((array)$value1)));

                                foreach($courseProduct as $key2 => $value2){
                                    $Products[$key1][$key2] =   $value2;
                                }
                            }
                        }
                    ?>

                    <div class="products-section">
                        @if(count($products))
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
                                                <label class="control-label col-md-3">Required</label>
                                                <div class="col-md-6">
                                                    <select name="product_in_course[{{$product->id}}][required]" class="form-control product_required" id="{{$product->id}}" onclick="showExcludedIncluded(this)">
                                                        <option value="1" @if(@$Products[$product->id]['required'] == 1) selected @endif>Yes</option>
                                                        <option value="0" @if(@$Products[$product->id]['required'] == 0) selected @endif>No</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>

                                        @if(@$Products[$product->id]['required'] == 1)
                                        <div class="col-md-2 product_included_excluded{{$product->id}}">
                                            <input type="radio" name="product_in_course[{{$product->id}}][IE]" value="1" id="incl{{$product->id}}" onclick="hidePrice(this)" @if(@$Products[$product->id]['IE'] == 1) checked @endif> Included
                                            <input type="radio" name="product_in_course[{{$product->id}}][IE]" value="0" id="excl{{$product->id}}" onclick="showPrice(this)" @if(@$Products[$product->id]['IE'] == 0) checked @endif> Excluded
                                        </div>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        @else
                            Click Here To: <a href="{{ route('scubaya::merchant::shop::shops', Auth::id()) }}">Add Product</a>
                        @endif
                    </div>

                    <div class="box-footer">
                        <a href="{{ route('scubaya::merchant::shop::courses', [Auth::id(), $shopId]) }}"><button type="button" class="btn btn-default">Cancel</button></a>
                        <button type="submit" class="btn btn-info pull-right">Update</button>
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
        'boats'         =>  json_decode($course->boats),
        'location'      =>  $location,
        'instructors'   =>  json_decode($course->instructors)
    ])
@endsection