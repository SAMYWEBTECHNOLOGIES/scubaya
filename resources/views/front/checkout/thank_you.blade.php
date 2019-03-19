@extends('front.layouts.master')
@section('page-title')
    Thank You
@endsection
@section('content')
    @include('front._partials.header')

    <section class="thank-you">
        <div class="ui container text-center">
            <hr class="border">
            <h1>THANKS</h1>

            <div class="thank-you-message text-center">
                <p>YOUR BOOKING IS RECEIVED.</p>
                <p>Now it's time to make great things happen!!</p>
            </div>

            <a href="{{ route('scubaya::home') }}">
                <button class="ui green button">ENJOY DIVING</button>
            </a>
        </div>
    </section>
@endsection