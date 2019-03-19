@extends('front.layouts.app')
@section('title', 'Scubaya Registration')
@section('content')
    @include('front.layouts.mainheader')
    @include('front.layouts.registration_script')
    <section id="registration-section" class="margin-top-60">
        <div class="scubaya-box registration">
            <div class="scubaya-box-body">
                <h2 class="scubaya-box-msg text-center">Scubaya registration</h2>
                <form class="sign_up_form" name="login-form">
                    <div class="form-group">
                        <input type="radio" id="professional_account" name="create_account" value="professional_account"> Create Merchant Account</br>
                        <input type="radio" id="instructor_account" name="instructor_account" value="professional_account"> Create Instructor Account</br>
                        <input type="radio" id="diver_account" name="create_account" value="diver_account"> Create Diver Account
                    </div>
                </form>
            </div>
        </div>
    </section>
@endsection
