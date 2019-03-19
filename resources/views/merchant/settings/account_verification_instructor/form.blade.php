<script type="text/javascript">
    @if ($errors->saveRequestErrors->any())
    jQuery(document).ready(function($) {
        $('#verification-form-modal').modal({show: true});
    });
    @endif
</script>

{{--<div aria-hidden="true" class="modal fade bs-example-modal-lg" tabindex="-1" id="verification-form-modal" role="dialog" aria-labelledby="myLargeModalLabel">--}}
    {{--@if(count($merchantDetails) > 0 && $status == MERCHANT_STATUS_PENDING)--}}
        {{--<div class="modal-dialog modal-sm" role="document">--}}
            {{--<div class="modal-content">--}}
                {{--<div class="model-header">--}}
                    {{--<h3 class="text-center blue">Warning</h3>--}}
                {{--</div>--}}
                {{--<div class="modal-body">--}}
                    {{--<p class="text-center">To generate a new request please delete the previous one.</p>--}}
                {{--</div>--}}
            {{--</div>--}}
        {{--</div>--}}
    {{--@else--}}
        {{--<div class="modal-dialog modal-lg" role="document">--}}
            {{--<div class="modal-content">--}}
                <div class="model-header">
                    <h3 class="text-center blue">Fill the additional details</h3>
                </div>
                @if ($errors->saveRequestErrors->any())
                    <div class="row margin-top-10">
                        <div class="col-md-8 col-md-offset-2 alert alert-danger">
                            <ul>
                                @foreach ($errors->saveRequestErrors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                @endif
                <div class="modal-body">
                    <!-- The form is placed inside the body of modal -->
                    <form id="verification_form_instructor" enctype="multipart/form-data" name="verification_form_instructor" method="post" action="{{route('scubaya::merchant::save_account_instructor',[Auth::guard('merchant')->user()->id])}}">
                        {{ csrf_field() }}
                        <div class="row">
                            <div class="box-body">
                                {{--<div class="row">--}}
                                    {{--<div class="col-md-4">--}}
                                        {{--<div class="form-group">--}}
                                            {{--<label for="first_name">First Name</label>--}}
                                            {{--<input type="text" class="form-control" id="first_name" placeholder="First name" name="first_name" value="{{old('first_name')}}" required="">--}}
                                        {{--</div>--}}
                                    {{--</div>--}}

                                    {{--<div class="col-md-4">--}}
                                        {{--<div class="form-group">--}}
                                            {{--<label for="last_name">Last Name</label>--}}
                                            {{--<input type="text" class="form-control" id="last_name" placeholder="Last name" name="last_name" value="{{old('last_name')}}" required="">--}}
                                        {{--</div>--}}
                                    {{--</div>--}}

                                    {{--<div class="col-md-4">--}}
                                        {{--<div class="form-group">--}}
                                            {{--<label for="spoken_languages">Phone</label>--}}
                                            {{--<input type="text" class="form-control" id="phone" placeholder="phone" name="phone" value="{{old('phone')}}" required>--}}
                                        {{--</div>--}}
                                    {{--</div>--}}

                                    {{--<div class="col-md-3">--}}
                                    {{--<div class="form-group">--}}
                                    {{--<label for="user_name">User Name</label>--}}
                                    {{--<input type="text" class="form-control" id="user_name" placeholder="User name" name="user_name" value="{{old('user_name')}}" required="">--}}
                                    {{--</div>--}}
                                    {{--</div>--}}

                                    {{--<div class="col-md-3">--}}
                                    {{--<div class="form-group">--}}
                                    {{--<label for="password">Password</label>--}}
                                    {{--<input type="text" class="form-control" id="password" placeholder="password" name="password" required="">--}}
                                    {{--</div>--}}
                                    {{--</div>--}}
                                {{--</div>--}}

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

                                    {{--<div class="col-md-4">--}}
                                        {{--<div class="form-group">--}}
                                            {{--<label for="email">Email</label>--}}
                                            {{--<input type="email" class="form-control" id="email" placeholder="Email" name="email" value="{{old('email')}}" required="">--}}
                                        {{--</div>--}}
                                    {{--</div>--}}
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
                                                        <input type="text" class="form-control" id="affiliation" placeholder="affiliation" name="certifications[]">
                                                    </td>
                                                    <td class="col-md-3">
                                                        <input type="text" class="form-control" id="level" placeholder="level" name="certifications[]">
                                                    </td>
                                                    <td class="col-md-3">
                                                        <input type="text" class="form-control datepicker" data-date-format="yyyy/mm/dd" id="date" placeholder="date" name="certifications[]">
                                                    </td>

                                                    <td class="col-md-3">
                                                        <input type="text" class="form-control" id="number" placeholder="number" name="certifications[]">
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

                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="merchants">Search merchants here to connect</label>
                                            <input type="text" class="form-control" id="merchants" placeholder="search merchants" name="merchants">
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

                            <div class="col-md-6">
                                <div class="form-group">
                                    <button type="submit" class="btn btn-primary" id="verification_submit">Submit</button>
                                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            {{--</div>--}}
        {{--</div>--}}
{{--</div>--}}
<script src="https://code.jquery.com/jquery-1.12.4.js"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<script type="text/javascript">
    jQuery.noConflict();
    jQuery(document).ready(function($) {
        $( function() {
            var merchants = JSON.parse( '<?php echo \App\Scubaya\model\Merchant::all()->pluck('email')->toJson(); ?>');
            console.log(merchants);
            function split( val ) {
                return val.split( /,\s*/ );
            }
            function extractLast( term ) {
                return split( term ).pop();
            }

            $( "#merchants" )
                // don't navigate away from the field on tab when selecting an item
                    .on( "keydown", function( event ) {
                        if ( event.keyCode === $.ui.keyCode.TAB &&
                                $( this ).autocomplete( "instance" ).menu.active ) {
                            event.preventDefault();
                        }
                    })
                    .autocomplete({
                        minLength: 0,
                        source: function( request, response ) {
                            // delegate back to autocomplete, but extract the last term
                            response( $.ui.autocomplete.filter(
                                    merchants, extractLast( request.term ) ) );
                        },
                        focus: function() {
                            // prevent value inserted on focus
                            return false;
                        },
                        select: function( event, ui ) {
                            var terms = split( this.value );
                            // remove the current input
                            terms.pop();
                            // add the selected item
                            terms.push( ui.item.value );
                            // add placeholder to get the comma-and-space at the end
                            terms.push( "" );
                            this.value = terms.join( ", " );
                            return false;
                        }
                    });
        } );

    });
</script>