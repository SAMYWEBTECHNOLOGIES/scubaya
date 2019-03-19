@extends('front.layouts.master')
@section('page-title')
    Liveaboards
@endsection
@section('content')
    @include('front._partials.header')

    <div id="liveaboard-context">
        <section class="coming-soon">
            <div class="scby-logo text-center">
                <img alt="Scubaya Logo" width="200" src="{{ asset('assets/images/logo/Scubaya-text-logo-original-color.png') }}">
            </div>
            <h1 class="text-center">Coming Soon!!</h1>
            <h2 class="text-center">Get ready! Something really cool is coming!</h2>
        </section>
    </div>
@endsection
@section('script-extra')
    <script type="text/javascript">
        $(document).ready(function () {
            $('.sub-header').sticky({
                context: '#liveaboard-context'
            });
        });
    </script>
@endsection