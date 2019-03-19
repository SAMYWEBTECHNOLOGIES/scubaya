@php
    $reads  =   json_decode($diveCentersObject->read_before_you_go);
@endphp

@if($reads)
<hr class="type hrblue">
<section class="read-before-go-sections">
    <div class="">
        <h2 class="blue read-before-you-go-title">Read before you go</h2>

        @foreach($reads as $key => $value)
            @if(!$loop->last)
                <hr>
            @endif
            <div class = "ui grid">
                <div class ="ui four wide column">
                    <h5>{{ $value->title }}:</h5>
                </div>
                <div class = "ui twelve wide column">
                    <p>{{ $value->description }}</p>
                </div>
            </div>
        @endforeach
    </div>
</section>
@endif