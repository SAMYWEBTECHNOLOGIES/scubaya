@extends('merchant.layouts.app')
@section('content')
    @include('merchant.layouts.mainheader')
    @include('merchant.layouts.setting_script')
    {{-- <h2 class="text-center">Account Verification</h2>--}}
    <?php
    $status = '';

    $labelStatus    =   [
            MERCHANT_STATUS_PENDING     =>  'label label-warning',
            MERCHANT_STATUS_IN_PROCESS  =>  'lable label-info',
            MERCHANT_STATUS_APPROVED    =>  'label label-success',
            MERCHANT_STATUS_REJECTED    =>  'label label-danger'
    ];
    ?>
    <section id="account_verification" class="padding-20">
        @if(session('message'))
            <div class="alert alert-success">
                <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                {{ session('message')}}
            </div>
        @endif

        <div>
            <button type="button" id="new_request" class=" pull-right button-blue btn btn-primary" data-toggle="modal" data-target="#verification-form-modal">
                New Request
            </button>
        </div>

        <div class="box box-primary margin-top-60">
            <div class="box-header">
                <h3 class="box-title">Account Verification</h3>
            </div>
            <!-- / box-header -->
            <div class="box-body table-responsive no-padding">
                <table class="table table-hover">


                </table>
            </div>
            <!-- / box-body -->
        </div>
    </section>

    {{--<?php--}}
    {{--if($status == MERCHANT_STATUS_IN_PROCESS || $status == MERCHANT_STATUS_APPROVED || $status == MERCHANT_STATUS_REJECTED){--}}
    {{--?>--}}
    {{--<script type="text/javascript">--}}
        {{--jQuery(document).ready(function($){--}}
            {{--$('#new_request').prop('disabled', true);--}}
        {{--});--}}
    {{--</script>--}}
    {{--<?php--}}
    {{--}--}}
    {{--?>--}}

    <!-- / new request model -->
    @include('merchant.settings.account_verification_instructor.form')

    <!-- / edit request model -->
    {{--@if(!empty($merchantDetails))--}}
        {{--@foreach($merchantDetails as $m)--}}
            {{--@if($m->request_status == MERCHANT_STATUS_PENDING)--}}
                {{--@include('merchant.settings.account_verification.edit_request')--}}
            {{--@endif--}}
        {{--@endforeach--}}
    {{--@endif--}}

@endsection