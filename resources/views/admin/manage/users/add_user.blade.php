@extends('admin.layouts.app')
@section('title','Add User')
@section('breadcrumb')
    <li><a href="{{route('scubaya::admin::dashboard')}}">Manage</a></li>
    <li><a href="{{route('scubaya::admin::manage::users')}}">Users</a></li>
    <li class="active"><span>Add Users</span></li>
@endsection
@section('content')
    <section class="container screen-fit">
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title">Add User</h3>
            </div>
            <!-- /.box-header -->
            <!-- form start -->
            @if ($errors->any())
                <div class="row margin-top-10">
                    <div class="col-md-4 col-md-offset-4 alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            @endif

            <form role="form" method="post" action="{{route('scubaya::admin::manage::add_user')}}" enctype="multipart/form-data">
                {{ csrf_field() }}
                <div class="box-body">
                    <div class="row">
                        @if(Session::has('success_personal_information'))
                            <div class="alert alert-success">
                                <a href="#" class="close" data-dismiss="alert">&times;</a>
                                <p>{{ Session::get('success_personal_information') }}</p>
                            </div>
                        @endif
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="gender" data-toggle="tooltip">Gender</label>
                                <select id="gender" class="selectpicker form-control show-tick" name="gender">
                                    <option value="male">Male</option>
                                    <option value="female">Female</option>
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="dob">Date of Birth</label>
                                <button type="button"  onclick="changeStatus(this)" class="btn btn-toggle  active" data-toggle="button" aria-pressed="true">
                                    <div class="handle"></div>
                                </button>

                                <input type="hidden" name="personal_information[dob][]"  value="{{old('personal_information')['dob'][0] or 1}}">
                                <input type="text" class="form-control datepicker" data-date-format="yyyy/mm/dd" id="dob" value="{{old('personal_information')['dob'][1]}}" name="personal_information[dob][]" />
                            </div>

                            <div class="form-group">
                                <label for="user_name" data-toggle="tooltip">User name</label>
                                <button type="button"  onclick="changeStatus(this)" class="btn btn-toggle  active" data-toggle="button" aria-pressed= "true">
                                    <div class="handle"></div>
                                </button>

                                <input type="hidden" name="personal_information[user_name][]"  value="{{old('personal_information')['user_name'][0] or 1}}">
                                <input type="text" class="form-control" id="user_name" value="{{old('personal_information')['user_name'][1]}}" name="personal_information[user_name][]">
                            </div>

                            <div class="form-group">
                                <label for="first_name" data-toggle="tooltip">First name</label>
                                <button type="button"  onclick="changeStatus(this)" class="btn btn-toggle  active " data-toggle="button" aria-pressed="true">
                                    <div class="handle"></div>
                                </button>

                                <input type="hidden" name="personal_information[first_name][]"  value="{{old('personal_information')['first_name'][0] or 1}}">
                                <input type="text" class="form-control" id="first_name" value="{{old('personal_information')['first_name'][1]}}" name="personal_information[first_name][]">
                            </div>

                            <div class="form-group">
                                <label for="last_name" data-toggle="tooltip">Last name</label>
                                <button type="button"  onclick="changeStatus(this)" class="btn btn-toggle active" data-toggle="button" aria-pressed="true">
                                    <div class="handle"></div>
                                </button>
                                <input type="hidden" name="personal_information[last_name][]"  value="{{old('personal_information')['last_name'][0] or 1}}">
                                <input type="text" class="form-control" id="last_name" value="{{old('personal_information')['last_name'][1]}}" name="personal_information[last_name][]">
                            </div>

                            <div class="form-group">
                                <label for="email" data-toggle="tooltip">Email</label>
                                <button type="button"  onclick="changeStatus(this)" class="btn btn-toggle active" data-toggle="button" aria-pressed="true">
                                    <div class="handle"></div>
                                </button>
                                <input type="hidden" name="personal_information[email][]"  value="{{old('personal_information')['email'][0] or 1}}">
                                <input type="email" class="form-control" id="email" value="{{old('personal_information')['email'][1]}}" name="personal_information[email][]">
                            </div>

                            <div class="form-group">
                                <label for="password" data-toggle="tooltip">Password</label>
                                <input type="password" class="form-control" id="password" name="personal_information[password][]">
                            </div>

                            <div class="form-group">
                                <label for="password_confirmation" data-toggle="tooltip">Confirm Password</label>
                                <input type="password" class="form-control" id="password_confirmation" name="personal_information[password_confirmation][]">
                            </div>

                            <div class="form-group">
                                <label for="nationality" data-toggle="tooltip">Nationality</label>
                                <button type="button"  onclick="changeStatus(this)" class="btn btn-toggle active" data-toggle="button" aria-pressed="true">
                                    <div class="handle"></div>
                                </button>
                                <input type="hidden" name="personal_information[nationality][]"  value="{{old('personal_information')['nationality'][0] or 1}}">
                                <input type="text" class="form-control" id="nationality" value="{{old('personal_information')['nationality'][1]}}" name="personal_information[nationality][]">
                            </div>

                            <div class="form-group">
                                <label for="phone" data-toggle="tooltip">Phone</label>
                                <button type="button"  onclick="changeStatus(this)" class="btn btn-toggle active" data-toggle="button" aria-pressed="true">
                                    <div class="handle"></div>
                                </button>
                                <input type="hidden" name="personal_information[phone][]"  value="{{old('personal_information')['phone'][0] or 1}}">
                                <input type="text" class="form-control" id="phone" value="{{old('personal_information')['phone'][1]}}" name="personal_information[phone][]">
                            </div>

                            <div class="form-group">
                                <label for="mobile" data-toggle="tooltip">Mobile</label>
                                <button type="button"  onclick="changeStatus(this)" class="btn btn-toggle active" data-toggle="button" aria-pressed="true">
                                    <div class="handle"></div>
                                </button>
                                <input type="hidden" name="personal_information[mobile][]"  value="{{old('personal_information')['mobile'][0] or 1}}">
                                <input type="text" class="form-control" id="mobile" value="{{old('personal_information')['mobile'][1]}}" name="personal_information[mobile][]">
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="street" data-toggle="tooltip">Street</label>
                                <button type="button"  onclick="changeStatus(this)" class="btn btn-toggle active" data-toggle="button" aria-pressed="true">
                                    <div class="handle"></div>
                                </button>
                                <input type="hidden" name="personal_information[street][]"  value="{{old('personal_information')['street'][0] or 1}}">
                                <input type="text" class="form-control" value="{{old('personal_information')['street'][1]}}" id="street" name="personal_information[street][]">
                            </div>

                            <div class="form-group">
                                <label for="house_number" data-toggle="tooltip">House Number</label>
                                <button type="button"  onclick="changeStatus(this)" class="btn btn-toggle active" data-toggle="button" aria-pressed="true">
                                    <div class="handle"></div>
                                </button>
                                <input type="hidden" name="personal_information[house_number][]"  value="{{old('personal_information')['house_number'][0] or 1}}">

                                <input type="text" class="form-control" value="{{old('personal_information')['house_number'][1]}}" id="house_number" name="personal_information[house_number][]">
                            </div>

                            <div class="form-group">
                                <label for="house_number_extension" data-toggle="tooltip">House number Extension</label>
                                <button type="button"  onclick="changeStatus(this)" class="btn btn-toggle active" data-toggle="button" aria-pressed="true">
                                    <div class="handle"></div>
                                </button>

                                <input type="hidden" name="personal_information[house_number_extension][]"  value="{{old('personal_information')['house_number_extension'][0] or 1}}">
                                <input type="text" class="form-control" id="house_number_extension" value="{{old('personal_information')['house_number_extension'][1]}}" name="personal_information[house_number_extension][]">
                            </div>

                            <div class="form-group">
                                <label for="postal_code" data-toggle="tooltip">Postal Code</label>
                                <button type="button"  onclick="changeStatus(this)" class="btn btn-toggle active" data-toggle="button" aria-pressed="true">
                                    <div class="handle"></div>
                                </button>

                                <input type="hidden" name="personal_information[postal_code][]"  value="{{old('personal_information')['postal_code'][0] or 1}}">
                                <input type="text" class="form-control" id="postal_code" value="{{old('personal_information')['postal_code'][1]}}" name="personal_information[postal_code][]">
                            </div>

                            <div class="form-group">
                                <label for="city" data-toggle="tooltip">City</label>
                                <button type="button"  onclick="changeStatus(this)" class="btn btn-toggle active" data-toggle="button" aria-pressed="true">
                                    <div class="handle"></div>
                                </button>

                                <input type="hidden" name="personal_information[city][]"  value="{{old('personal_information')['city'][0] or 1}}">
                                <input type="text" class="form-control" value="{{old('personal_information')['city'][1]}}" id="city" name="personal_information[city][]">
                            </div>

                            <div class="form-group">
                                <label for="country" data-toggle="tooltip">Country</label>
                                <button type="button"  onclick="changeStatus(this)" class="btn btn-toggle active" data-toggle="button" aria-pressed="true">
                                    <div class="handle"></div>
                                </button>

                                <input type="hidden" name="personal_information[country][]"  value="{{old('personal_information')['country'][0] or 1}}">
                                <input type="text" class="form-control" id="country" value="{{old('personal_information')['country'][1]}}" name="personal_information[country][]">
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="main_image" data-toggle="tooltip" title="Upload main image"><i class="fa fa-upload" aria-hidden="true"></i>   Upload main image</label>
                                <input type="file" class="form-control" id="main_image" name="main_image" onchange="readURL(this);">
                            </div>
                        </div>
                    </div>

                    <div class="box-footer">
                        <a href="{{ route('scubaya::admin::manage::users') }}"><button type="button" class="btn btn-default">Cancel</button></a>
                        <button type="submit" class="btn btn-info pull-right">Save</button>
                    </div>
                </div>
            </form>
        </div>
    </section>

    <script type="text/javascript">
        jQuery(document).ready(function(scubaya) {
            scubaya('.datepicker').datepicker();
        });
        function changeStatus(data) {

            var isActive      =     jQuery(data).attr('aria-pressed') == 'true' ? 0 : 1;
            // console.log(isActive);
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