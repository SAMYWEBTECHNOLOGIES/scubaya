@extends('instructor.layouts.app')
@section('content')

    <section id="create_room_section" class="padding-20">
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title">Complete your profile</h3>
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

            <form id="verification_form_instructor" enctype="multipart/form-data" name="verification_form_instructor" method="post" action="{{route('scubaya::instructor::update_profile',[Auth::guard('merchant')->user()->id])}}">
                {{ csrf_field() }}

                    <div class="box-body">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="dob">Day of Birth</label>
                                    <input type="text" class="form-control datepicker" data-date-format="yyyy/mm/dd" id="dob" placeholder="Date of birth" name="dob" value="{{@$profile_detail->dob}}" required="">
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="nationality">Nationality</label>
                                    <input type="text" class="form-control" id="nationality" placeholder="Nationality" name="nationality" value="{{@$profile_detail->nationality}}" required="">
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="phone">Phone</label>
                                    <input type="text" class="form-control" id="phone" placeholder="Phone" name="phone" value="{{@$profile_detail->phone}}" required="">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="years_of_experience">Years of Experience</label>
                                    <input type="number" class="form-control" id="years_of_experience" placeholder="Years of experience" name="years_of_experience" value="{{@$profile_detail->years_experience}}" required="">
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="total_number_dives">Total number of Dives</label>
                                    <input type="number" class="form-control" id="total_number_dives" placeholder="number of dives" name="total_number_dives" value="{{@$profile_detail->total_dives}}" required="">
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="spoken_languages">Spoken languages</label>
                                    <input type="text" class="form-control" id="spoken_languages" placeholder="languages" name="spoken_languages" value="{{@$profile_detail->spoken_languages}}" required>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="facebook">Facebook</label>
                                    <input type="text" class="form-control" id="facebook" placeholder="Facebook" name="facebook" value="{{@$profile_detail->facebook}}">
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="twitter">Twitter</label>
                                    <input type="text" class="form-control" id="twitter" placeholder="Twitter" name="twitter" value="{{@$profile_detail->twitter}}">
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="instagram">Instagram</label>
                                    <input type="text" class="form-control" id="instagram" placeholder="Instagram" name="instagram" value="{{@$profile_detail->instagram}}">
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
                                        @if(count($profile_detail->certifications))

                                            @foreach(json_decode(@$profile_detail->certifications) as $certification)
                                                <tr id="addRow">
                                                    <td class="col-md-3">
                                                        <input type="text" class="form-control" id="affiliation" placeholder="affiliation" value="{{$certification[0]}}" name="certifications[]">
                                                    </td>
                                                    <td class="col-md-3">
                                                        <input type="text" class="form-control" id="level" placeholder="level" value="{{$certification[1]}}" name="certifications[]">
                                                    </td>
                                                    <td class="col-md-3">
                                                        <input type="text" class="form-control datepicker" data-date-format="yyyy/mm/dd" id="date" placeholder="date" value="{{$certification[2]}}" name="certifications[]">
                                                    </td>
                                                    <td class="col-md-3">
                                                        <input type="text" class="form-control" id="number" placeholder="number" value="{{$certification[3]}}" name="certifications[]">
                                                    </td>
                                                    <td><a class="fa fa-remove addBtnRemove" id="addBtnRemove_0"></a></td>
                                                </tr>
                                            @endforeach
                                        @else
                                            <tr id="addRow">
                                                <td class="col-md-3">
                                                    <input type="text" class="form-control" id="affiliation" placeholder="affiliation"  name="certifications[]">
                                                </td>
                                                <td class="col-md-3">
                                                    <input type="text" class="form-control" id="level" placeholder="level"  name="certifications[]">
                                                </td>
                                                <td class="col-md-3">
                                                    <input type="text" class="form-control datepicker" data-date-format="yyyy/mm/dd" id="date" placeholder="date"  name="certifications[]">
                                                </td>
                                                <td class="col-md-3">
                                                    <input type="text" class="form-control" id="number" placeholder="number" name="certifications[]">
                                                </td>
                                                <td><a class="fa fa-remove addBtnRemove" id="addBtnRemove_0"></a></td>
                                            </tr>
                                        @endif
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
                                    <input type="text" class="form-control" id="own_website" placeholder="Own Website(url)" name="own_website" value="{{@$profile_detail->own_website}}">
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="from-group">
                                    <label for="short_story">Short Introduction Story</label>
                                    <textarea style="resize: none;" rows="1" class="form-control" id="short_story" placeholder="Short story" name="short_story" required="">{{@$profile_detail->short_story}}</textarea>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="pricing">Pricing</label>
                                    <input type="text" class="form-control" id="pricing" placeholder="pricing" name="pricing" value="{{@$profile_detail->pricing}}" required>
                                </div>
                            </div>
                        </div>

                        <hr>

                        @foreach(json_decode($profile_detail->merchant_ids) as $id)
                            <div class="row">
                                <?php $merchant_details    =   \App\Scubaya\model\MerchantDetails::where('merchant_primary_id',$id)->select(['merchant_id','full_name'])->first();
                                ?>

                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="merchants">Connected Merchants</label>
                                        <input type="text" class="form-control add-merchant" id="merchants" value="{{$merchant_details->merchant_id}}" placeholder="paste the id and press enter" disabled="">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group"><label></label>Name<input type="text" class="form-control" disabled="" value="{{$merchant_details->full_name}}">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group" style="margin-top: 20px;"><label></label><span class="fa fa-plus-circle fa-2x"></span><span style="margin-left: 25px;" class="fa fa-minus-circle fa-2x"></span>
                                    </div>
                                </div>
                                <input type="hidden" value="{{$id}}" name="merchants[]">
                            </div>
                        @endforeach
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="merchants">Search merchants here to connect</label>
                                    <input type="text" class="form-control add-merchant" id="merchants" placeholder="paste the id and press enter">
                                </div>
                            </div>
                        </div>
                    </div>

                <div class="box-footer">
                    <a href="{{route('scubaya::instructor::dashboard',[Auth::guard('merchant')->user()->id])}}"><button type="button" class="btn btn-default">Cancel</button></a>
                    <button type="submit" class="btn btn-info pull-right">Update</button>
                </div>
            </form>
        </div>
    </section>

    @include('merchant.layouts.instructor_script')
    {{--<script src="https://code.jquery.com/jquery-1.12.4.js"></script>--}}
    <script src="{{asset('plugins/jQuery/jquery-2.2.3.min.js')}}"></script>
    <script src="{{asset('plugins/jQuery-ui/jquery-ui.js')}}"></script>
    <link rel="stylesheet" href="{{('plugins/jQuery-ui/jquery-ui.min.css')}}">
    <script type="text/javascript">
        jQuery.noConflict();
        var merchants =JSON.parse( '<?php echo (\App\Scubaya\model\MerchantDetails::select('merchant_id','full_name','merchant_primary_id')->get()->toJson()); ?>');

        jQuery(document).ready(function($) {

            /*function after pressing the enter*/
            $(document.body).on('keypress','.add-merchant',function(e){
               $(this).closest('.row').find('p').remove();
               if(e.which === 13){
                   let id   =   $(this).val();
                   let details =   search_merchant(id);
                   if(details){

                       $(this).prop('disabled',true);
                       $(this).closest('.row').append('<div class="col-md-3"><div class="form-group"><label></label>Name<input type="text" class="form-control" disabled value="'+details.full_name+'"></div></div>');
                       $(this).closest('.row').append('<div class="col-md-3"><div class="form-group" style="margin-top: 20px;"><label></label><span class="fa fa-plus-circle fa-2x"></span><span style="margin-left: 25px;" class="fa fa-minus-circle fa-2x"></span></div></div>');
                       $(this).closest('.row').append('<input type="hidden" value="'+details.merchant_primary_id+'" name="merchants[]">');

                       return false;

                   }else{
                       $(this).closest('.form-group').append('<p>No results found</p>');
                       return false;
                   }
               } return true;
            });

            /*searching for merchant in merchants array*/
            var search_merchant = function(id) {
                var i = null;
                for (i = 0; merchants.length > i; i += 1) {
                    if (merchants[i].merchant_id === id) {
                        return merchants[i];
                    }
                }
                return false;
            };

            /*add a another merchant*/
            $(document.body).on('click','.fa-plus-circle',function(){
                $(this).closest('.row').after('<div class="row"><div class="col-md-3"><div class="form-group"><label for="merchants">Connect to more</label><input type="text" class="form-control add-merchant" id="merchants" placeholder="paste the id and press enter"></div></div></div>');
            });

            /*delete the merchant selected*/
            $(document.body).on('click','.fa-minus-circle',function(){
                $(this).closest('.row').remove();
                if($("input[class*='add-merchant']").length < 1){
                    $('.box-body').append('<div class="row"><div class="col-md-3"><div class="form-group"><label for="merchants">Search merchants here to connect</label><input type="text" class="form-control add-merchant" id="merchants" placeholder="paste the id and press enter"></div></div></div>');
                }
            });

        });
    </script>

@endsection