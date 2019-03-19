@extends('merchant.layouts.app')
@section('title', 'Dashboard')


@section('content')
    @include('merchant.layouts.mainheader')
    @if (session()->get('popup_merchant'))
        {{--Trigger JS or pass some trigger variable to open popup window.--}}
        <script type="text/javascript">
            $(function() {
                $('#verification-modal').modal('show');
            });
        </script>
    @endif

    <section class="merchant_dashboard">
        <div class="container">
            <div class="panel padding-20 text-center">
                <i class="fa fa-dashboard text-center" style="font-size: 20px;color: #358eb3;"></i>
                <h3 class="blue">Something awesome is coming soon!!</h3>
            </div>
        </div>

        @if (session()->get('popup_merchant'))
            <div class="modal fade" id="verification-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-body">
                            <p class="text-center">You account is not validated. Please complete your sign up process by clicking on continue.</p>
                        </div>
                        <div class="modal-footer">
                            <a href="{{ route('scubaya::merchant::dashboard', [Auth::id()]) }}"><button type="button" class="btn btn-default" data-dismiss="modal">Skip</button></a>
                            <a href="{{ route('scubaya::merchant::settings::account_verification', [Auth::id()]) }}"><button type="button" class="btn btn-primary">Continue</button></a>
                        </div>
                    </div><!-- /.modal-content -->
                </div><!-- /.modal-dialog -->
            </div>
        @endif

    </section>
@endsection