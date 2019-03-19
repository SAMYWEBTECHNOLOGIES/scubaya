@extends('front.layouts.app')
@section('content')
    @include('front.layouts.mainheader')
    @include('front.layouts.registration_script')
    <section id="registration-section" class="margin-top-60">
        <div class="scubaya-box registration">
            <div class="scubaya-box-body">
                <h2 class="scubaya-box-msg text-center">Scubaya registration</h2>
                <form class="sign_up_form" name="login-form">
                    <div class="form-group">
                        <input type="radio" id="merchant_account" name="create_account" value="merchant_account"> Merchant Account</br>
                        <input type="radio" id="instructor_account" name="create_account" value="instructor_account"> Instructor Account
                    </div>
                </form>
            </div>
        </div>
    </section>
@endsection