@extends('merchant.layouts.app')
@section('title', 'Boats')
@section('breadcrumb')
    <li><a href="#">Bookings</a></li>
    <li class="active"><span>All Bookings</span></li>
@endsection

@section('content')
    @include('merchant.layouts.mainheader')

    <section id="all_bookings" class="padding-20">
        <div class="nav-tabs-custom">
            <ul class="nav nav-tabs">
                <li class="active"><a href="#courses" data-toggle="tab" aria-expanded="true">Courses</a></li>
                <li ><a href="#products" data-toggle="tab" aria-expanded="true">Products</a></li>
                <li ><a href="#hotels" data-toggle="tab" aria-expanded="true">Hotels</a></li>
            </ul>

            <div class="tab-content">
                <div class="tab-pane active margin-bottom-10" id="courses">
                    @include('merchant.bookings.course_booking', [ 'courseBookings' => $courseBookings, 'sno' => 1])
                </div>

                <div class="tab-pane margin-bottom-10" id="products">
                    @include('merchant.bookings.product_booking', [ 'productBookings' => $productBookings, 'sno' => 1])
                </div>

                <div class="tab-pane margin-bottom-10" id="hotels">
                    @include('merchant.bookings.hotel_booking', [ 'hotelBookings' => $hotelBookings, 'sno' => 1])
                </div>
            </div>
        </div>
    </section>

    {{-- script to active tab after redirecting page --}}
    <script type="text/javascript">
        jQuery('a[data-toggle="tab"]').on('show.bs.tab', function(e) {
            localStorage.setItem('activeTab', jQuery(e.target).attr('href'));
        });

        var activeTab = localStorage.getItem('activeTab');

        if (activeTab) {
            jQuery('a[href="' + activeTab + '"]').tab('show');
        }

        jQuery(document).ready(function ($) {
            $('.course_booking_status').change(function (e) {
                var url     =   "{{ route('scubaya::merchant::course_booking_status') }}";
                var token   =   "{{ csrf_token() }}";

                $.post(url, { id:this.id, status:$(this).val(), _token:token}, function (status) {
                    if(status) {
                        console.log('success!!');
                    }
                });
            });

            $('.product_booking_status').change(function (e) {
                var url     =   "{{ route('scubaya::merchant::product_booking_status') }}";
                var token   =   "{{ csrf_token() }}";

                $.post(url, { id:this.id, status:$(this).val(), _token:token}, function (status) {
                    if(status) {
                        console.log('success!!');
                    }
                });
            });

            $('.hotel_booking_status').change(function (e) {
                var url     =   "{{ route('scubaya::merchant::hotel_booking_status') }}";
                var token   =   "{{ csrf_token() }}";

                $.post(url, { id:this.id, status:$(this).val(), _token:token}, function (status) {
                    if(status) {
                        console.log('success!!');
                    }
                });
            });
        });

        @if(session()->has('status'))
            var message =   '{{ session('status') }}';

            $.notify({
                message:message
            },{
                type: 'pastel-warning',
                delay: 5000,
                template: '<div data-notify="container" class="col-xs-11 col-sm-3 alert alert-{0}" role="alert">' +
                '<span data-notify="title">{1}</span>' +
                '<span data-notify="message">{2}</span>' +
                '</div>'
            });

        @endif

    </script>
@endsection