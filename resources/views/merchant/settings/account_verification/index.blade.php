@extends('merchant.layouts.app')
@section('title', 'Account Verification')
@section('breadcrumb')
    <li><a href="#">Settings</a></li>
    <li class="active"><span>Account Verification</span></li>
@endsection

@section('content')
    @include('merchant.layouts.mainheader')
    @include('merchant.layouts.setting_script')
    <?php
    $status = '';

    $labelStatus    =   [
        MERCHANT_STATUS_PENDING     =>  'label label-warning',
        MERCHANT_STATUS_IN_PROCESS  =>  'label label-info',
        MERCHANT_STATUS_APPROVED    =>  'label label-success',
        MERCHANT_STATUS_REJECTED    =>  'label label-danger'
    ];
    ?>

    <section id="account_verification" class="padding-20">
        <div class="nav-tabs-custom" id="tabs">
            <ul class="nav nav-tabs">
                <li class="active"><a href="#main_account" data-toggle="tab" aria-expanded="true">Main Account</a></li>
                {{--<li><a href="#sub_account" data-toggle="tab" aria-expanded="true">Sub Account</a></li>--}}
            </ul>

            <div class="tab-content">
                <div class="tab-pane active margin-bottom-10 padding-20" id="main_account">
                    @include('merchant.settings.account_verification.main_account.index', ['merchantDetails' => $merchantDetails])
                </div>
                {{--<div class="tab-pane margin-bottom-10 padding-20" id="sub_account">
                    @include('merchant.settings.account_verification.sub_account.index')
                </div>--}}
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
    </script>
@endsection