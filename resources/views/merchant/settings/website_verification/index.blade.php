@extends('merchant.layouts.app')
@section('title', 'Website Verification')
@section('content')
    @include('merchant.layouts.mainheader')
    <?php
    $status = '';

    $labelStatus    =   [
            MERCHANT_STATUS_PENDING     =>  'label label-warning',
            MERCHANT_STATUS_IN_PROCESS  =>  'lable label-info',
            MERCHANT_STATUS_APPROVED    =>  'label label-success',
            MERCHANT_STATUS_REJECTED    =>  'label label-danger'
    ];
    ?>
    <section id="website_verification" class="padding-20">
        <div>
            <a href="{{ route('scubaya::merchant::settings::create_website_verification_request', [Auth::id()]) }}">
                <button type="button" id="new_request" class=" pull-right button-blue btn btn-primary" data-toggle="modal" data-target="#verification-form-modal">
                    New Request
                </button>
            </a>
        </div>

        <div class="box box-primary margin-top-60">
            <div class="box-header">
                <h3 class="box-title">Website Verification</h3>
            </div>
            <!-- / box-header -->
            <div class="box-body table-responsive no-padding">
                <table class="table table-hover">
                </table>
            </div>
        </div>
    </section>
@endsection