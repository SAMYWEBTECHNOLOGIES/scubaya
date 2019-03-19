@extends('front.layouts.master')
@section('content')
    @include('front._partials.header')
    <div class="ui container">
        <div class="ui grid">
            <div class="five wide column">ddd</div>
            <div class="eleven wide column"> @foreach($boats as $boat)
                    <div class="scb-boat">
                        <div>
                        </div>
                        <img src="{{ asset('assets/images/scubaya/boats/').'/'.$boat->id.'-'.$boat->image }}" />
                        <div><strong>Engine Power</strong></div>
                        <div>{{ $boat->engine_power }}</div>
                        <div><strong>Type</strong></div>
                        <div>{{ $boat->type }}</div>
                        <div><strong>Max Passengers</strong></div>
                        <div>{{ $boat->max_passengers }}</div>

                    </div>

                @endforeach</div>
        </div>

    </div>
@endsection