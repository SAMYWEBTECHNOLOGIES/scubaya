@extends('front.layouts.app')
@section('title', 'Diver Registration')
@section('content')
    @include('front.layouts.mainheader')

    <section id="diver_registration1" class="margin-top-60" >
        <div class="container">
        <div class="panel panel-primary">{{--<br><br><br>--}}
            <div class="" style="margin-top: 20px;">
                <h3 class="panel-title text-center">{{strtoupper('Diver ')}} <span class="blue">{{strtoupper('Registration')}}</span></h3>
            </div>
            <div class="panel-body">
                <form enctype="multipart/form-data"  method="post" action="{{route('scubaya::register::diver::register_page_1')}}">
                    {{csrf_field()}}
                        <div class="row">

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="surname">Surname</label>
                                        {{--<span class="input-group-addon"><i class="fa fa-user fa" aria-hidden="true"></i></span>--}}
                                        <input class="form-control" placeholder="{{'surname'}}" id="surname" type="text" name="surname" required>
                                </div>
                            </div>


                                <div class="col-md-4">
                                    <div class="form-group">
                                    <label for="first_name">First Name</label>
                                    <input class="form-control" placeholder="{{'First Name' }}" id="first_name"  type="text" name="first_name" value="{{old('first_name')}}" required>
                                </div>
                            </div>


                                <div class="col-md-4">
                                    <div class="form-group">
                                    <label for="name">Pseudonym</label>
                                    <input class="form-control" placeholder="{{'Pseudonym' }}" id="pseudonym" value="{{old('pseudonym')}}"  type="text" name="pseudonym" required>
                                </div>
                            </div>
                        </div>

                        <div class="row">

                                <div class="col-md-4">
                                    <div class="form-group">
                                    <label for="name">Date of Birth</label>
                                    <input class="form-control datepicker" data-date-format="yyyy/mm/dd" name="dob" id="dob" value="{{old('dob')}}" placeholder="Date of Birth" required>
                                </div>
                            </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                    <label for="name">Nationality</label>
                                    <input class="form-control" placeholder="{{'Nationality' }}" id="nationality" value="{{old('nationality')}}"  type="text" name="nationality" required>
                                </div>
                            </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                    <label for="name">Residence</label>
                                    <input class="form-control" placeholder="{{'Residence' }}" id="residence"  type="text" name="residence" required value="{{old('residence')}}">
                                </div></div>
                            </div>

<hr>

                    <div class="row">

                            <div class="col-md-3 ">
                                <div class="form-group">
                                    {{--<span class="input-group-addon"><i class="fa fa-envelope fa" aria-hidden="true"></i></span>--}}
                                    <label for="email">Your Email</label>
                                    <input type="text" class="form-control" name="email" id="email"  placeholder="Enter your Email" required>
                            </div>
                        </div>

                            <div class="col-md-3">
                                <div class="form-group">
                                <label for="email_confirmation">Confirm Email</label>
                                <input type="text" class="form-control" name="email_confirmation" id="email_confirmation"  placeholder="Confirm Email" value="{{old('email_confirmation')}}" required>
                            </div>
                        </div>


                            <div class="col-md-3 ">
                                <div class="form-group">
                                    {{--<span class="input-group-addon"><i class="fa fa-lock fa-lg" aria-hidden="true"></i></span>--}}
                                    <label for="password" >Password</label>
                                    <input type="password" class="form-control" name="password" id="password"  placeholder="Enter your Password" required>
                            </div>
                        </div>

                            <div class="col-md-3 ">
                                <div class="form-group">
                                <label for="confirm_password" >Confirm Password</label>
                                <input type="password" class="form-control" name="password_confirmation" id="password_confirmation"  placeholder="Confirm your Password" required>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-offset-11 ">
                            <div class="form-group col-md-2">
                                <input type="submit" class="btn btn-primary" name="diver_registration1" id="dive_center_submit" value="NEXT">
                            </div>
                        </div>
                    </div>

                </form>
        </div>

    </div></div>
</section>
<script>
    $(document).ready(function(){
        $('.datepicker').datepicker();
    });
</script>
@endsection