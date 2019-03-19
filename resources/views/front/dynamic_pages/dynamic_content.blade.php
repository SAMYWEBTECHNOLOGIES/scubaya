@extends('front.layouts.master')
@section('page-title')
    {{$content->name}}
@endsection
@section('content')
    @include('front._partials.header')
    <section id="{{strtolower(str_replace(' ','-', $content->name))}}-section">
        <div class="ui container">
            {!! $content->content !!}
        </div>
    </section>
@endsection
