@extends('admin.layouts.app')
@section('title','Edit User')
@section('breadcrumb')
    <li><a href="{{route('scubaya::admin::dashboard')}}">Manage</a></li>
    <li><a href="{{route('scubaya::admin::manage::users')}}">Users</a></li>
    <li class="active"><span>Edit Users</span></li>
@endsection
@section('content')
    <section class="container screen-fit">
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title">Edit User</h3>
            </div>
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        <a href="#" class="close" data-dismiss="alert">&times;</a>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            <!-- form start -->
            <form role="form" method="post" action="{{route('scubaya::admin::manage::edit_user',[$personal_settings->id])}}" enctype="multipart/form-data">
                {{ csrf_field() }}
                {{--Custom request method--}}
                {{ method_field('UPDATE') }}

                <div class="box-body">
                    <div class="row">
                        @if(Session::has('success_personal_information'))
                            <div class="alert alert-success">
                                <a href="#" class="close" data-dismiss="alert">&times;</a>
                                <p>{{ Session::get('success_personal_information') }}</p>
                            </div>
                        @endif
                        <div class="col-md-4">
                            @php
                                if(isset($personal_settings) && $personal_settings->gender) {
                                    $gender      =   decrypt($personal_settings->gender);
                                } else {
                                    $gender      =   null;
                                }
                            @endphp
                            <div class="form-group">
                                <label for="gender" data-toggle="tooltip">Gender</label>
                                <select id="gender" class="selectpicker form-control show-tick" name="gender">
                                    <option value="male" @if($gender == "male") selected @endif>Male</option>
                                    <option value="female" @if($gender == "female") selected @endif>Female</option>
                                </select>
                            </div>
                            @php
                                if(isset($personal_settings) && $personal_settings->dob) {
                                    $dob      =   \App\Scubaya\model\UserPersonalInformation::formatDataToShow(decrypt(@$personal_settings->dob),'dob');
                                } else {
                                    $dob      =   null;
                                }
                            @endphp
                            <div class="form-group">
                                <label for="dob">Date of Birth</label>
                                <button type="button"  onclick="changeStatus(this)" class="btn btn-toggle {{!is_null(@$dob->show) ? (@$dob->show ? 'active' : '') : 'active'}}" data-toggle="button" aria-pressed="{{!is_null(@$dob->show) ? (@$dob->show ? 'true' : 'false') : 'true'}}">
                                    <div class="handle"></div>
                                </button>

                                <input type="hidden" name="personal_information[dob][]"  value="{{!is_null(@$dob->show) ? (@$dob->show ? 1 : 0):1}}">
                                <input type="text" class="form-control datepicker" data-date-format="yyyy/mm/dd" id="dob" value="{{@$dob->dob ? ($dob->dob) : ''}}" name="personal_information[dob][]" />
                            </div>
                            @php
                                if(isset($personal_settings) && $personal_settings->user_name) {
                                    $user_name  =   \App\Scubaya\model\UserPersonalInformation::formatDataToShow(decrypt(@$personal_settings->user_name),'user_name');
                                } else {
                                    $user_name  =   null;
                                }
                            @endphp
                            <div class="form-group">
                                <label for="user_name" data-toggle="tooltip">User name</label>
                                <button type="button"  onclick="changeStatus(this)" class="btn btn-toggle {{!is_null(@$user_name->show) ? (@$user_name->show ? 'active' : '') : 'active'}}" data-toggle="button" aria-pressed="{{ !is_null(@$user_name->show) ? (@$user_name->show ? 'true' : 'false') : 'true'}}">
                                    <div class="handle"></div>
                                </button>
                                <input type="hidden" name="personal_information[user_name][]"  value="{{!is_null(@$user_name->show) ? (@$user_name->show ? 1 : 0):1}}">
                                <input type="text" class="form-control" id="user_name" value="{{@$user_name->user_name ? ($user_name->user_name):'' }}" name="personal_information[user_name][]">
                            </div>

                            @php
                                if(isset($personal_settings) && $personal_settings->first_name) {
                                    $firstName  =   \App\Scubaya\model\User::decryptString($personal_settings->first_name);

                                    $firstName  =   json_encode([$firstName => 1]);

                                    $first_name =   \App\Scubaya\model\UserPersonalInformation::formatDataToShow($firstName, 'first_name');
                                } else {
                                    $first_name =   null;
                                }
                            @endphp
                            <div class="form-group">
                                <label for="first_name" data-toggle="tooltip">First name</label>
                                <button type="button"  onclick="changeStatus(this)" class="btn btn-toggle {{!is_null(@$first_name->show) ? (@$first_name->show ? 'active' : '') : 'active'}}" data-toggle="button" aria-pressed="{{ !is_null(@$first_name->show) ? (@$first_name->show ? 'true' : 'false') : 'true'}}">
                                    <div class="handle"></div>
                                </button>

                                <input type="hidden" name="personal_information[first_name][]"  value="{{!is_null(@$first_name->show) ? (@$first_name->show ? 1 : 0):1}}">
                                <input type="text" class="form-control" id="first_name" value="{{@$first_name->first_name?($first_name->first_name):''}}" name="personal_information[first_name][]">
                            </div>
                            @php
                                if(isset($personal_settings) && $personal_settings->last_name) {

                                    $lastName   =   \App\Scubaya\model\User::decryptString($personal_settings->last_name);

                                    $lastName   =   json_encode([$lastName => 1]);

                                    $last_name  =   \App\Scubaya\model\UserPersonalInformation::formatDataToShow($lastName, 'last_name');
                                } else {
                                    $last_name  =   null;
                                }
                            @endphp
                            <div class="form-group">
                                <label for="last_name" data-toggle="tooltip">Last name</label>
                                <button type="button"  onclick="changeStatus(this)" class="btn btn-toggle {{!is_null(@$last_name->show) ? (@$last_name->show ? 'active' : '') : 'active'}}" data-toggle="button" aria-pressed="{{ !is_null(@$last_name->show) ? (@$last_name->show ? 'true' : 'false') : 'true'}}">
                                    <div class="handle"></div>
                                </button>
                                <input type="hidden" name="personal_information[last_name][]"  value="{{!is_null(@$last_name->show) ? (@$last_name->show ? 1 : 0):1}}">
                                <input type="text" class="form-control" value="{{@$last_name->last_name ?($last_name->last_name) :''}}" id="last_name" name="personal_information[last_name][]">
                            </div>
                            @php
                                if(isset($personal_settings) && $personal_settings->email) {
                                    $Email   =   App\Scubaya\model\User::decryptString($personal_settings->email);

                                    $Email   =   json_encode([$Email => 1]);

                                    $email   =   \App\Scubaya\model\UserPersonalInformation::formatDataToShow($Email,'email');
                                } else {
                                    $email   =   null;
                                }
                            @endphp
                            <input type="hidden" value="{{@$email->email}}" name="old_email">
                            <div class="form-group">
                                <label for="email" data-toggle="tooltip">Email</label>
                                <button type="button"  onclick="changeStatus(this)" class="btn btn-toggle {{!is_null(@$email->show) ? (@$email->show ? 'active' : '') : 'active'}}" data-toggle="button" aria-pressed="{{ !is_null(@$email->show) ? (@$email->show ? 'true' : 'false') : 'true'}}">
                                    <div class="handle"></div>
                                </button>
                                <input type="hidden" name="personal_information[email][]"  value="{{!is_null(@$email->show) ? (@$email->show ? 1 : 0):1}}">
                                <input type="email" class="form-control" id="email" value="{{@$email->email ? ($email->email):''}}" name="personal_information[email][]">
                            </div>
                            @php
                                if(isset($personal_settings) && $personal_settings->nationality) {
                                    $nationality    =   \App\Scubaya\model\UserPersonalInformation::formatDataToShow(decrypt(@$personal_settings->nationality),'nationality');
                                } else {
                                    $nationality    =   null;
                                }
                            @endphp
                            <div class="form-group">
                                <label for="nationality" data-toggle="tooltip">Nationality</label>
                                <button type="button"  onclick="changeStatus(this)" class="btn btn-toggle {{!is_null(@$nationality->show) ? (@$nationality->show ? 'active' : '') : 'active'}}" data-toggle="button" aria-pressed="{{ !is_null(@$nationality->show) ? (@$nationality->show ? 'true' : 'false') : 'true'}}">
                                    <div class="handle"></div>
                                </button>
                                <input type="hidden" name="personal_information[nationality][]"  value="{{!is_null(@$nationality->show) ? (@$nationality->show ? 1 : 0):1}}">
                                <input type="text" class="form-control" id="nationality" value="{{@$nationality->nationality ? $nationality->nationality : ''}}" name="personal_information[nationality][]">
                            </div>
                            @php
                                if(isset($personal_settings) && $personal_settings->phone) {
                                    $phone   =   \App\Scubaya\model\UserPersonalInformation::formatDataToShow(decrypt(@$personal_settings->phone),'phone');
                                } else {
                                    $phone   =   null;
                                }
                            @endphp
                            <div class="form-group">
                                <label for="phone" data-toggle="tooltip">Phone</label>
                                <button type="button"  onclick="changeStatus(this)" class="btn btn-toggle {{!is_null(@$phone->show) ? (@$phone->show ? 'active' : '') : 'active'}}" data-toggle="button" aria-pressed="{{ !is_null(@$phone->show) ? (@$phone->show ? 'true' : 'false') : 'true'}}">
                                    <div class="handle"></div>
                                </button>
                                <input type="hidden" name="personal_information[phone][]"  value="{{!is_null(@$phone->show) ? (@$phone->show ? 1 : 0):1}}">
                                <input type="text" class="form-control" id="phone" value="{{@$phone->phone ? $phone->phone : ''}}" name="personal_information[phone][]">
                            </div>
                            @php
                                if(isset($personal_settings) && $personal_settings->mobile) {
                                    $mobile   =   \App\Scubaya\model\UserPersonalInformation::formatDataToShow(decrypt(@$personal_settings->mobile),'mobile');
                                } else {
                                    $mobile   =     null;
                                }
                            @endphp
                            <div class="form-group">
                                <label for="mobile" data-toggle="tooltip">Mobile</label>
                                <button type="button"  onclick="changeStatus(this)" class="btn btn-toggle {{!is_null(@$mobile->show) ? (@$mobile->show ? 'active' : '') : 'active'}}" data-toggle="button" aria-pressed="{{ !is_null(@$mobile->show) ? (@$mobile->show ? 'true' : 'false') : 'true'}}">
                                    <div class="handle"></div>
                                </button>
                                <input type="hidden" name="personal_information[mobile][]"  value="{{!is_null(@$mobile->show) ? (@$mobile->show ? 1 : 0):1}}">
                                <input type="text" class="form-control" value="{{@$mobile->mobile ? $mobile->mobile : ''}}" id="mobile" name="personal_information[mobile][]">
                            </div>
                        </div>
                        @php
                            if(isset($personal_settings) && $personal_settings->street) {
                                $street   =   \App\Scubaya\model\UserPersonalInformation::formatDataToShow(decrypt(@$personal_settings->street),'street');
                            } else {
                                $street   =   null;
                            }
                        @endphp
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="street" data-toggle="tooltip">Street</label>
                                <button type="button"  onclick="changeStatus(this)" class="btn btn-toggle {{!is_null(@$street->show) ? (@$street->show ? 'active' : '') : 'active'}}" data-toggle="button" aria-pressed="{{ !is_null(@$street->show) ? (@$street->show ? 'true' : 'false') : 'true'}}">
                                    <div class="handle"></div>
                                </button>
                                <input type="hidden" name="personal_information[street][]"  value="{{!is_null(@$street->show) ? (@$street->show ? 1 : 0):1}}">
                                <input type="text" class="form-control" value="{{@$street->street ? $street->street : ''}}" id="street" name="personal_information[street][]">
                            </div>
                            @php
                                if(isset($personal_settings) && $personal_settings->house_number) {
                                    $house_number   =   \App\Scubaya\model\UserPersonalInformation::formatDataToShow(decrypt(@$personal_settings->house_number),'house_number');
                                } else {
                                    $house_number   =   null;
                                }
                            @endphp
                            <div class="form-group">
                                <label for="house_number" data-toggle="tooltip">House Number</label>
                                <button type="button"  onclick="changeStatus(this)" class="btn btn-toggle {{!is_null(@$house_number->show) ? (@$house_number->show ? 'active' : '') : 'active'}}" data-toggle="button" aria-pressed="{{ !is_null(@$house_number->show) ? (@$house_number->show ? 'true' : 'false') : 'true'}}">
                                    <div class="handle"></div>
                                </button>
                                <input type="hidden" name="personal_information[house_number][]"  value="{{!is_null(@$house_number->show) ? (@$house_number->show ? 1 : 0):1}}">

                                <input type="text" class="form-control" value="{{@$house_number->house_number ? $house_number->house_number : ''}}" id="house_number" name="personal_information[house_number][]">
                            </div>
                            @php
                                if(isset($personal_settings) && $personal_settings->house_number_extension) {
                                    $house_number_extension   =   \App\Scubaya\model\UserPersonalInformation::formatDataToShow(decrypt(@$personal_settings->house_number_extension),'house_number_extension');
                                } else {
                                    $house_number_extension   =   null;
                                }
                            @endphp
                            <div class="form-group">
                                <label for="house_number_extension" data-toggle="tooltip">House number Extension</label>
                                <button type="button"  onclick="changeStatus(this)" class="btn btn-toggle {{!is_null(@$house_number_extension->show) ? (@$house_number_extension->show ? 'active' : '') : 'active'}}" data-toggle="button" aria-pressed="{{ !is_null(@$house_number_extension->show) ? (@$house_number_extension->show ? 'true' : 'false') : 'true'}}">
                                    <div class="handle"></div>
                                </button>

                                <input type="hidden" name="personal_information[house_number_extension][]"  value="{{!is_null(@$house_number_extension->show) ? (@$house_number_extension->show ? 1 : 0):1}}">
                                <input type="text" class="form-control" id="house_number_extension" value="{{@$house_number_extension->house_number_extension ? $house_number_extension->house_number_extension : ''}}" name="personal_information[house_number_extension][]">
                            </div>
                            @php
                                if(isset($personal_settings) && $personal_settings->postal_code) {
                                    $postal_code    =   \App\Scubaya\model\UserPersonalInformation::formatDataToShow(decrypt(@$personal_settings->postal_code),'postal_code');
                                } else {
                                    $postal_code    =   null;
                                }
                            @endphp
                            <div class="form-group">
                                <label for="postal_code" data-toggle="tooltip">Postal Code</label>
                                <button type="button"  onclick="changeStatus(this)" class="btn btn-toggle {{!is_null(@$postal_code->show) ? (@$postal_code->show ? 'active' : '') : 'active'}}" data-toggle="button" aria-pressed="{{ !is_null(@$postal_code->show) ? (@$postal_code->show ? 'true' : 'false') : 'true'}}">
                                    <div class="handle"></div>
                                </button>

                                <input type="hidden" name="personal_information[postal_code][]"  value="{{!is_null(@$postal_code->show) ? (@$postal_code->show ? 1 : 0):1}}">
                                <input type="text" class="form-control" id="postal_code" value="{{@$postal_code->postal_code ? $postal_code->postal_code : ''}}" name="personal_information[postal_code][]">
                            </div>
                            @php
                                if(isset($personal_settings) && $personal_settings->city) {
                                    $city   =   \App\Scubaya\model\UserPersonalInformation::formatDataToShow(decrypt(@$personal_settings->city),'city');
                                } else {
                                    $city   =   null;
                                }
                            @endphp
                            <div class="form-group">
                                <label for="city" data-toggle="tooltip">City</label>
                                <button type="button"  onclick="changeStatus(this)" class="btn btn-toggle {{!is_null(@$city->show) ? (@$city->show ? 'active' : '') : 'active'}}" data-toggle="button" aria-pressed="{{ !is_null(@$city->show) ? (@$city->show ? 'true' : 'false') : 'true'}}">
                                    <div class="handle"></div>
                                </button>

                                <input type="hidden" name="personal_information[city][]"  value="{{!is_null(@$city->show) ? (@$city->show ? 1 : 0):1}}">
                                <input type="text" class="form-control" value="{{@$city->city ? $city->city : ''}}" id="city" name="personal_information[city][]">
                            </div>
                            @php
                                if(isset($personal_settings) && $personal_settings->country) {
                                    $country    =   \App\Scubaya\model\UserPersonalInformation::formatDataToShow(decrypt(@$personal_settings->country),'country');
                                } else {
                                    $country    =   null;
                                }
                            @endphp
                            <div class="form-group">
                                <label for="country" data-toggle="tooltip">Country</label>
                                <button type="button"  onclick="changeStatus(this)" class="btn btn-toggle {{!is_null(@$country->show) ? (@$country->show ? 'active' : '') : 'active'}}" data-toggle="button" aria-pressed="{{ !is_null(@$country->show) ? (@$country->show ? 'true' : 'false') : 'true'}}">
                                    <div class="handle"></div>
                                </button>

                                <input type="hidden" name="personal_information[country][]"  value="{{!is_null(@$country->show) ? (@$country->show ? 1 : 0):1}}">
                                <input type="text" class="form-control" id="country" value="{{@$country->country ? $country->country : ''}}" name="personal_information[country][]">
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="main_image" data-toggle="tooltip" title="Upload main image"><i class="fa fa-upload" aria-hidden="true"></i>   Upload main image</label>
                                <input type="file" class="form-control" id="main_image" name="main_image" onchange="readURL(this);">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="box-footer">
                    <a href="{{ route('scubaya::admin::manage::users') }}"><button type="button" class="btn btn-default">Cancel</button></a>
                    <button type="submit" class="btn btn-info pull-right">Update</button>
                </div>
            </form>
        </div>
    </section>
    @php
        $clientGeoInfo =   geoip($_SERVER['REMOTE_ADDR']);
    @endphp

    <script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAm_-PodAPns0u0-bvF3qHHV3G_sLe0gdI"></script>
    <script type="text/javascript">
        jQuery(document).ready(function(scubaya) {
            scubaya('.datepicker').datepicker();
        });
        function changeStatus(data) {
            var isActive      =     jQuery(data).attr('aria-pressed').trim() == 'true' ? 0 : 1;
            jQuery(data).next().val(isActive);
        }

        function readURL(input) {
            if (input.files && input.files[0] && input.files.length == 1 ) {
                var reader = new FileReader();
                reader.onload = function (e) {
                    jQuery(input).closest('div').find('img').remove();
                    jQuery(input).after('<img  src="'+e.target.result+'" width="30%" height="30%">');
                };
                reader.readAsDataURL(input.files[0]);
            }else{
                var i =1;
                for(i;i<input.files.length+1;i++){
                    var reader = new FileReader();
                    reader.onload = function (e) {
                        jQuery(input).after('<img src="'+e.target.result+'" width="30%" height="30%">');
                    };
                    reader.readAsDataURL(input.files[i-1]);
                }
            }
        }



    </script>

@stop