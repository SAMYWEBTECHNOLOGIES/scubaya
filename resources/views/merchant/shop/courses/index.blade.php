@extends('merchant.layouts.app')
@section('title', 'Courses')
@section('breadcrumb')
    <li><a href="#">Shop</a></li>
    <li><a href="{{route('scubaya::merchant::shop::shops',[Auth::id()])}}">Manage Shop</a></li>
    <li class="active"><span>Courses</span></li>
@endsection

@section('content')
    @include('merchant.layouts.mainheader')

    <section id="courses_section" class="padding-20">
        <div>
            <a href="{{ route('scubaya::merchant::shop::create_course', [Auth::id(), $shopId]) }}">
                <button type="button" class="pull-right button-blue btn btn-primary">
                    + New
                </button>
            </a>
        </div>

        <div class="box box-primary margin-top-60">
            <div class="box-header with-border">
                <h3 class="box-title">Courses</h3>
            </div>

            <div class="box-body table-responsive no-padding">
                <table class="table table-hover">
                    @if(count($courses) > 0)
                        <thead>
                        <tr>
                            <th>S.No.</th>
                            <th>Name</th>
                            <th>Start Date</th>
                            <th>End Date</th>
                            <th>Min People</th>
                            <th>Max People</th>
                            <th>Min Age</th>
                            <th>Action</th>
                        </tr>
                        </thead>

                        <tbody>
                        @foreach($courses as $course)
                            <tr>
                                <td>{{ $sno++ }}</td>
                                <td>{{ isset($course->course_name) ? ucwords($course->course_name) : '---' }}</td>
                                <td>{{ isset($course->course_start_date) ? $course->course_start_date : '---' }}</td>
                                <td>{{ isset($course->course_end_date) ? $course->course_end_date : '---' }}</td>

                                <?php
                                    $coursePricing  =   json_decode($course->course_pricing);
                                ?>

                                <td>{{ isset($coursePricing->min_people) ? $coursePricing->min_people : '---'}}</td>
                                <td>{{ isset($coursePricing->max_people) ? $coursePricing->max_people : '---' }}</td>
                                <td>{{ isset($coursePricing->min_age) ? $coursePricing->min_age : '---'}}</td>
                                <td>
                                    <div class="inline-flex">
                                        <a href="{{ route('scubaya::merchant::shop::edit_course', [Auth::id(), $shopId, $course->id]) }}">
                                            <button type="button" class="button-blue btn btn-primary">
                                                <i class="fa fa-pencil"></i>
                                            </button>
                                        </a>

                                        <form class="padding-left5" method="post" action="{{ route('scubaya::merchant::shop::delete_course', [Auth::id(), $shopId, $course->id]) }}">
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
                            <th class="text-center"> No Courses Available.</th>
                        </tr>
                    @endif
                </table>
            </div>
        </div>

        <div class="pagination">
            {{$courses->links()}}
        </div>
    </section>

    @include('merchant.layouts.delete_script')
@endsection