@extends('instructor.layouts.app')
@section('content')
    @include('instructor.layouts.mainheader')

        @if(!(\App\Scubaya\model\Instructor::where('merchant_primary_id',Auth::guard('merchant')->user()->id)->exists()))
            <script type="text/javascript">
                $(function() {
                    $('#verification-modal-instructor').modal('show');
                });
            </script>
        @endif
        @if(Session::has('success'))
            <div class="alert alert-success">
                <a href="#" class="close" data-dismiss="alert">&times;</a>
                <p>{{ Session::get('success') }}</p>
            </div>
        @endif
   <section class="merchant_dashboard">
       <h2 class="text-center">Dashboard</h2>
   </section>

    {{--modal for instructor verification--}}
    <div class="modal fade" id="verification-modal-instructor" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-body">
                    <p class="text-center">Your profile is not completed.Click Proceed to complete your profile.</p>
                </div>
                <div class="modal-footer">
                    {{--TODO: change the routes according to instructor--}}
                    <a href="{{ route('scubaya::instructor::dashboard', [Auth::guard('merchant')->user()->id]) }}"><button type="button" class="btn btn-default" data-dismiss="modal">Skip</button></a>
                    <a href="{{ route('scubaya::instructor::profile', [Auth::guard('merchant')->user()->id]) }}"><button type="button" class="btn btn-primary">Proceed</button></a>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div>
@endsection