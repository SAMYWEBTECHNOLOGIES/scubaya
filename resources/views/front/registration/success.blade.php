@extends('front.layouts.master')
@section('page-title')
    Thank You
@endsection
@section('content')
    @include('front._partials.header')
    <section id="success-section">
        <div class="ui grid center aligned">
            <div class="sixteen wide column">
                <h1  class="sign_up_message">Thank You</h1>
                <p>You're Signed Up!</p>
            </div>
            <div class = "sixteen wide column success-envelope">
                <i class="fa fa-envelope blue"></i>
            </div>
            <div class = "sixteen wide column">
                <p>A confirmation link has been sent to your email address.</p>
            </div>
            <div class = "sixteen wide column">
                <button class="ui primary button">
                    <a target="_blank" class ="merchant-url"  href="{{route('scubaya::merchant::login_merchant')}}" style = "">
                        Go To Merchant Account
                    </a>
                </button>
            </div>
        </div>
    </section>
@endsection
