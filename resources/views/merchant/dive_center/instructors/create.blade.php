@extends('merchant.layouts.app')
@section('title','Instructor')
@section('breadcrumb')
    <li><a href="#">Dive Center</a></li>
    <li><a href="{{route('scubaya::merchant::dive_center::dive_centers',[Auth::id()])}}">Manage Dive Centers</a></li>
    <li><a href="{{route('scubaya::merchant::instructor',[Auth::id(),$diveCenterId])}}">Instructors</a></li>
    <li class="active"><span>Add Instructor</span></li>
@endsection

@section('content')
    @include('merchant.layouts.mainheader')
    {{--@include('merchant.layouts.tariff_script')--}}
    <section id="create_room_section" class="padding-20">
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title">New Instructor</h3>
            </div>
            <!-- /.box-header -->
            <!-- form start -->
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

            <form role="form" method="post" action="{{ route('scubaya::merchant::save_instructor', [Auth::id(), $diveCenterId]) }}" enctype="multipart/form-data">
                {{ csrf_field() }}
                <div class="box-body">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="first_name">First Name</label>
                                <input type="text" class="form-control" id="first_name" placeholder="First name" name="first_name" value="{{old('first_name')}}" required="">
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="last_name">Last Name</label>
                                <input type="text" class="form-control" id="last_name" placeholder="Last name" name="last_name" value="{{old('last_name')}}" required="">
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="spoken_languages">Phone</label>
                                <input type="text" class="form-control" id="phone" placeholder="phone" name="phone" value="{{old('phone')}}" required>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="dob">Day of Birth</label>
                                <input type="text" class="form-control datepicker" data-date-format="yyyy/mm/dd" id="dob" placeholder="Date of birth" name="dob" value="{{old('dob')}}" required="">
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="nationality">Nationality</label>
                                <input type="text" class="form-control" id="nationality" placeholder="Nationality" name="nationality" value="{{old('nationality')}}" required="">
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="email">Email</label>
                                <input type="email" class="form-control" id="email" placeholder="Email" name="email" value="{{old('email')}}" required="">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="years_of_experience">Years of Experience</label>
                                <input type="number" class="form-control" id="years_of_experience" placeholder="Years of experience" name="years_of_experience" value="{{old('years_of_experience')}}" required="">
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="total_number_dives">Total number of Dives</label>
                                <input type="number" class="form-control" id="total_number_dives" placeholder="number of dives" name="total_number_dives" value="{{old('total_number_dives')}}" required="">
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="spoken_languages">Spoken languages</label>
                                <input type="text" class="form-control" id="spoken_languages" placeholder="languages" name="spoken_languages" value="{{old('spoken_languages')}}" required>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="facebook">Facebook</label>
                                <input type="text" class="form-control" id="facebook" placeholder="Facebook" name="facebook" value="{{old('facebook')}}">
                            </div>
                        </div>

                        <div class="col-md-4">
                           <div class="form-group">
                                <label for="twitter">Twitter</label>
                                <input type="text" class="form-control" id="twitter" placeholder="Twitter" name="twitter" value="{{old('twitter')}}">
                           </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="instagram">Instagram</label>
                                <input type="text" class="form-control" id="instagram" placeholder="Instagram" name="instagram" value="{{old('instagram')}}">
                            </div>
                        </div>


                    </div>
                    <hr>
                    <div class="row">
                        <div class=" col-md-12 ">
                            <div class="form-group">
                                <label class="form-title">Certifications</label>
                                <table class="table table-bordered table-hover" id="tableAddRow">
                                    <thead>
                                    <tr>
                                        <th>Affiliation</th>
                                        <th>Level</th>
                                        <th>Date</th>
                                        <th>Number</th>
                                        <th style="width:10px"><a class="addBtn" id="addBtn_0">Add</a></th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                        <tr id="addRow">
                                            <td class="col-md-3">
                                                <input type="text" class="form-control" id="affiliation" placeholder="affiliation" value="{{old('certifications')[0]}}" name="certifications[]">
                                            </td>
                                            <td class="col-md-3">
                                                <input type="text" class="form-control" id="level" placeholder="level" value="{{old('certifications')[1]}}" name="certifications[]">
                                            </td>
                                            <td class="col-md-3">
                                                <input type="text" class="form-control datepicker" data-date-format="yyyy/mm/dd" id="date" value="{{old('certifications')[2]}}" placeholder="date" name="certifications[]">
                                            </td>

                                            <td class="col-md-3">
                                                <input type="text" class="form-control" id="number" placeholder="number" value="{{old('certifications')[3]}}" name="certifications[]">
                                            </td>
                                            <td><a class="fa fa-remove addBtnRemove" id="addBtnRemove_0"></a></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="own_website">Own Website</label>
                                <input type="text" class="form-control" id="own_website" placeholder="Own Website(url)" name="own_website" value="{{old('own_website')}}">
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="from-group">
                                <label for="short_story">Short Introduction Story</label>
                                <textarea style="resize: none;" rows="1" class="form-control" id="short_story" placeholder="Short story" name="short_story" required="">{{old('short_story')}}</textarea>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="pricing">Pricing</label>
                                <input type="text" class="form-control" id="pricing" placeholder="pricing" name="pricing" value="{{old('pricing')}}" required>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- /.box-body -->
                <div class="box-footer">
                    <a href="{{route('scubaya::merchant::instructor',[Auth::id(), $diveCenterId])}}"><button type="button" class="btn btn-default">Cancel</button></a>
                    <button type="submit" class="btn btn-info pull-right">Create</button>
                </div>
                <!-- /.box-footer -->
            </form>
        </div>
    </section>

    @include('merchant.layouts.instructor_script')
@endsection
