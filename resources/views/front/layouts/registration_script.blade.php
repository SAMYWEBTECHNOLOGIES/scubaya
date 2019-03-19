<script type="text/javascript">
    jQuery.noConflict();
    jQuery(document).ready(function($){
        /* when click on this option then open sign up form for merchant */
        $('#professional_account').on('click',function(){
            window.location = '{{route('scubaya::register::create_account')}}';
        });

        $('#professional_login').on('click',function(){
            window.location = '{{route('scubaya::merchant::login')}}';
        });

        /* when click on this option then open sign up form for diver */
        $('#diver_account').on('click',function(){
            window.location = '{{route('scubaya::register::create_diver_account_page1')}}';
        });

        $('#instructor_account').on('click',function(){
            window.location = '{{route('scubaya::register::instructor_account')}}';
        });

        $('#merchant_sign_up_form').formValidation({
            framework: 'bootstrap',
            icon: {
                valid: 'fa fa-check',
                invalid: 'fa fa-times',
                validating: 'fa fa-refresh'
            },
           /* This option will not ignore invisible fields which belong to inactive panels */
            excluded: ':disabled',
            fields: {
                first_name: {
                    validators: {
                        notEmpty: {
                            message: 'First name  is required'
                        }
                    }
                },
                last_name: {
                    validators: {
                        notEmpty: {
                            message: 'Last name is required'
                        }
                    }
                },
                merchant_email: {
                    validators: {
                        notEmpty: {
                            message: 'The email address is required'
                        },
                        emailAddress: {
                            message: 'The email address is not valid'
                        }
                    }
                },
                merchant_password:{
                    validators: {
                        notEmpty: {
                            message: 'The password is required and cannot be empty'
                        }
                    }
                }
            }
        });

        $('.datepicker').datepicker({
            format: 'mm-dd-yyyy'
        });

        /* show and hide select period open */
        $('#select_period').click(function ()  {
            $('#select_opening_period').removeClass('hidden');
        });

        $('#whole_year').click(function ()  {
            $('#select_opening_period').addClass('hidden');
        });
        /* show and hide select period close */


       /* show and hide hotel / airport details open */
        $('#transfer_airport_hotel_yes').click(function ()  {
            $('#hotel_airport_transfer_details').removeClass('hidden');
        });

        $('#transfer_airport_hotel_no').click(function ()  {
            $('#hotel_airport_transfer_details').addClass('hidden');
        });
        /* show and hide hotel / airport details close */


       /* add and remove packages row open */
        var packageMaxFields   =   19;
        var packageRowCounter     =   0;

        $('#add_package_row').click( function () {
            if(packageRowCounter < packageMaxFields){
                packageRowCounter++;
                $('div.package-add-more').append( '<div class="package_row'+packageRowCounter+'">' +
                        '<div class="form-group col-md-12 col-sm-12">' +
                        '<div class="form-group col-md-3 col-sm-3"> ' +
                        '<label for="no_of_dives">Number Of dives*</label> ' +
                        '<input type="text" class="form-control" name="no_of_dives[]" placeholder="Enter number of dives"> ' +
                        '</div> ' +
                        '<div class="form-group col-md-3 col-sm-3"> ' +
                        '<label for="package_price">Price (incl VAT)*</label> ' +
                        '<input type="text" class="form-control"  name="package_price[]" placeholder="Enter Price"> ' +
                        '</div> ' +
                        '<div class="form-group col-md-2 col-sm-2"> ' +
                        '<label for="package_currency">Select Currency*</label>' +
                        '<!-- TODO: fetch from config --> ' +
                        '<select name="package_currency[]"  class="form-control"> ' +
                        '<option value="" selected disabled >-- Select Currency --</option> ' +
                        '</select> ' +
                        '</div> ' +
                        '<div class="form-group col-md-3 col-sm-3"> ' +
                        '<label for="package_comments">Comments*</label> ' +
                        '<textarea name="package_comments[]" class="form-control" rows="1"></textarea> ' +
                        '</div> ' +
                        '<div class="form-group col-md-1 col-sm-1"> ' +
                        '<label for="package_comments">Remove</label> ' +
                        '<a id="'+packageRowCounter+'" class="remove_package_row btn btn-default">-</a>' +
                        '</div>'+
                        '</div>'+
                        '</div>'
                );
            }
        });

        $(document).on('click', '.remove_package_row', function () {
            event.preventDefault();
            $('.package_row'+this.id).remove(); //Remove field html
            packageRowCounter--; //Decrement field counter
        });
        /* add and remove packages row close */


        /* add and remove scuba diving options open */
        var diveRowCounter     =   0;
        var diveMaxFields      =   10;
        $('#add_dive_row').click( function () {
            if(diveRowCounter < diveMaxFields){
                diveRowCounter++;
                $('div.diving-option-add-more').append( '<div class="dive_row'+diveRowCounter+'">' +
                        '<div class="form-group col-md-12 col-sm-12">' +
                        '<div class="form-group col-md-3 col-sm-3">' +
                        '<label for="diving_options">Diving Options</label>' +
                        '<input type="text" class="form-control"  name="diving_options[]" placeholder="Enter diving options">' +
                        '</div>' +

                        '<div class="form-group col-md-3 col-sm-3">' +
                        '<label for="dive_price">Price (incl VAT)</label>' +
                        '<input type="text" class="form-control" name="dive_price[]" placeholder="Enter Price">' +
                        '</div>' +

                        '<div class="form-group col-md-2 col-sm-2">' +
                        '<label for="dive_currency">Select Currency</label>' +
                        '<!-- TODO: fetch from config -->' +
                        '<select name="dive_currency[]"  class="form-control">' +
                        '<option value="" selected disabled >-- Select Currency --</option>' +
                        '</select>' +
                        '</div>' +

                        '<div class="form-group col-md-3 col-sm-3">' +
                        '<label for="dive_comments">Comments</label>' +
                        '<textarea name="dive_comments[]"  class="form-control" rows="1"></textarea>' +
                        '</div>' +
                        '<div class="form-group col-md-1 col-sm-1"> ' +
                        '<label for="dive_comments">Remove</label> ' +
                        '<a id="'+diveRowCounter+'" class="remove_dive_row btn btn-default">-</a>' +
                        '</div>'+
                        '</div>' +
                        '</div>'
                );
            }
        });

        $(document).on('click', '.remove_dive_row', function () {
            event.preventDefault();
            $('.dive_row'+this.id).remove(); //Remove field html
            diveRowCounter--; //Decrement field counter
        });
        /* add and remove scuba diving options close */


       /* add and remove training and certification fields open */
        var tcRowCounter     =   0;
        var tcMaxFields      =   15;
        $('#add_tc_row').click( function () {
            if(tcRowCounter < tcMaxFields){
                tcRowCounter++;
                $('div.tc-add-more').append( '<div class="tc_row'+tcRowCounter+'">' +
                        '<div class="form-group col-md-12 col-sm-12">' +
                        '<div class="form-group col-md-2 col-sm-2">' +
                        '<label for="tc_affiliation">Affiliations</label>' +
                        <?php $affiliations =   config('scubaya.affiliations'); ?>
                        '<select name="tc_affiliation[]"  class="form-control">' +
                        '<option value="" disabled selected>-- Select Affiliation --</option>' +
                        @foreach($affiliations as $affiliation)
                        '<option value="{{$affiliation}}">{{$affiliation}}</option>' +
                        @endforeach
                        '</select>' +
                        '</div>' +

                        '<div class="form-group col-md-2 col-sm-2">' +
                        '<label for="tc_course_level">Course Level</label>' +
                        '<input type="text" class="form-control"  name="tc_course_level[]" placeholder="Enter course level">' +
                        '</div>' +

                        '<div class="form-group col-md-2 col-sm-2">' +
                        '<label for="tc_price">Price (incl VAT)</label>' +
                        '<input type="text" class="form-control"  name="tc_price[]" placeholder="Enter Price">' +
                        '</div>' +

                        '<div class="form-group col-md-2 col-sm-2">' +
                        '<label for="tc_currency">Select Currency</label>' +
                        '<!-- TODO: fetch from config -->' +
                        '<select name="tc_currency[]"  class="form-control">' +
                        '<option value="" selected disabled >-- Select Currency --</option>' +
                        '</select>' +
                        '</div>' +

                        '<div class="form-group col-md-3 col-sm-3">' +
                        '<label for="tc_comments">Comments</label>' +
                        '<textarea name="tc_comments[]"  class="form-control" rows="1"></textarea>' +
                        '</div>' +

                        '<div class="form-group col-md-1 col-sm-1"> ' +
                        '<label for="dive_comments">Remove</label> ' +
                        '<a id="'+tcRowCounter+'" class="remove_tc_row btn btn-default">-</a>' +
                        '</div>'+
                        '</div>' +
                        '</div>'
                );
            }
        });

        $(document).on('click', '.remove_tc_row', function () {
            event.preventDefault();
            $('.tc_row'+this.id).remove(); //Remove field html
            tcRowCounter--; //Decrement field counter
        });
        /* add and remove training and certification fields close */


        /* add and remove speciality fields open */
        var specialityRowCounter     =   0;
        var specialityMaxFields      =   10;
        $('#add_specialities_row').click( function () {
            if(specialityRowCounter < specialityMaxFields){
                specialityRowCounter++;
                $('div.specialities-add-more').append( '<div class="specialities_row'+specialityRowCounter+'">' +
                        '<div class="form-group col-md-12 col-sm-12">' +
                        '<div class="form-group col-md-2 col-sm-2">' +
                        '<label for="tc_affiliation">Affiliations</label>' +
                        <?php $affiliations =   config('scubaya.affiliations'); ?>
                        '<select name="tc_affiliation[]"  class="form-control">' +
                        '<option value="" disabled selected>-- Select Affiliation --</option>' +
                        @foreach($affiliations as $affiliation)
                        '<option value="{{$affiliation}}">{{$affiliation}}</option>' +
                        @endforeach
                        '</select>' +
                        '</div>' +

                        '<div class="form-group col-md-2 col-sm-2">' +
                        '<label for="tc_course_level">Course Level</label>' +
                        '<input type="text" class="form-control"  name="tc_course_level[]" placeholder="Enter course level">' +
                        '</div>' +

                        '<div class="form-group col-md-2 col-sm-2">' +
                        '<label for="tc_price">Price (incl VAT)</label>' +
                        '<input type="text" class="form-control"  name="tc_price[]" placeholder="Enter Price">' +
                        '</div>' +

                        '<div class="form-group col-md-2 col-sm-2">' +
                        '<label for="tc_currency">Select Currency</label>' +
                        '<!-- TODO: fetch from config -->' +
                        '<select name="tc_currency[]"  class="form-control">' +
                        '<option value="" selected disabled >-- Select Currency --</option>' +
                        '</select>' +
                        '</div>' +

                        '<div class="form-group col-md-3 col-sm-3">' +
                        '<label for="tc_comments">Comments</label>' +
                        '<textarea name="tc_comments[]"  class="form-control" rows="1"></textarea>' +
                        '</div>' +

                        '<div class="form-group col-md-1 col-sm-1"> ' +
                        '<label for="dive_comments">Remove</label> ' +
                        '<a id="'+specialityRowCounter+'" class="remove_specialities_row btn btn-default">-</a>' +
                        '</div>'+
                        '</div>' +
                        '</div>'
                );
            }
        });

        $(document).on('click', '.remove_specialities_row', function () {
            event.preventDefault();
            $('.specialities_row'+this.id).remove(); //Remove field html
            specialityRowCounter--; //Decrement field counter
        });
        /* add and remove speciality fields close */


      /*  add and remove hotel open */
        var hotelRowCounter     =   0;
        var hotelMaxFields      =   3;
        $('#add_hotel_row').click( function () {
            if (hotelRowCounter < hotelMaxFields) {
                hotelRowCounter++;
                $('div.add-more-hotel').append('<div class="hotel_row'+hotelRowCounter+'">' +
                        '<div class="col-md-12 col-sm-12"> '+
                        '<div class="form-group col-md-6 col-sm-6">'+
                        ' <label for="hotel_recommend">Hotel Name</label>'+
                        '<input type="text"  name="hotel_recommend[]"  class="form-control" placeholder="Enter name">'+
                        '</div>'+
                        '<div class="form-group col-md-1 col-sm-1"> ' +
                        '<label for="remove">Remove</label> ' +
                        '<a id="'+hotelRowCounter+'" class="remove_hotel_row btn btn-default">-</a>' +
                        '</div>'+
                        '</div>'+
                        '</div>'
                );
            }
        });

        $(document).on('click', '.remove_hotel_row', function () {
            event.preventDefault();
            $('.hotel_row'+this.id).remove(); //Remove field html
            hotelRowCounter--; //Decrement field counter
        });
        /*  add and remove hotel close */
    });

</script>