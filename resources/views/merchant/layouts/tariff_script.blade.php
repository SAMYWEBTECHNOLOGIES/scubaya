<script type="text/javascript">
    jQuery.noConflict();
    jQuery(document).ready(function($) {
        $('.datepicker').datepicker({
            format: 'mm-dd-yyyy'
        });

        $('#interval_price_selector').change(function(){
           if($(this).val() == 2){
               $('.tariff_multi_input').addClass('hidden');
               $('.mindays_multi_input').removeClass('hidden');

               $('#manual_input_wrapper').removeClass('alert-success');
               $('#manual_input_wrapper').addClass('alert-error');
           }else{
               $('.tariff_multi_input').removeClass('hidden');
               $('.mindays_multi_input').addClass('hidden');

               $('#manual_input_wrapper').addClass('alert-success');
               $('#manual_input_wrapper').removeClass('alert-error');
           }
        });
        $('#tariff_description').summernote({
            height: 300,
        });
    });

    function set_mindays_by_dow(dow_class)
    {
        var dayOfWeek  =   jQuery('#'+dow_class).val();console.log(dayOfWeek);
        var inputVal   =   jQuery("input[name='micro[room_te_mindays]["+dayOfWeek+"]']").val();
        jQuery('.'+dow_class+'_mindays').val(inputVal);
        jQuery('.'+dow_class+'_mindays').css("border","1px solid red");
    }

    function set_rates_by_dow(dow_class)
    {
        var dayOfWeek   = jQuery('#'+dow_class).val();
        var inputVal    = jQuery("input[name='micro[room_te_rates]["+dayOfWeek+"]']").val();
        jQuery('.'+dow_class+'_rates').val(inputVal);
        jQuery('.'+dow_class+'_rates').css("border","1px solid red");
    }

    function jomres_micromanage_rate_picker(input_type)
    {
        var picker_input    =   '';

        if (input_type == "mindaysinput")
        {
            picker_input = 'picker_mindays_value';
        }
        else{
            picker_input = 'picker_rate_value';
        }

        var from_value  = jQuery('#start_date').val();
        var to_value    = jQuery('#end_date').val();
        var new_rate    = jQuery('#'+picker_input).val(); console.log(new_rate);

        epoch_from  = jomres_micromanage_picker_get_epoch(from_value);console.log(epoch_from);
        epoch_to    = jomres_micromanage_picker_get_epoch(to_value);console.log(epoch_to);

        var i        = epoch_from;
        var r_colour = random_colour();

        while (i <= epoch_to)
        {   console.log(i);
            console.log('jk'+(i/1000));
            jQuery("input[name='micro["+input_type+"]["+i/1000+"]']").css("border","2px solid #"+r_colour);
            jQuery("input[name='micro["+input_type+"]["+i/1000+"]']").val(new_rate);
            var tomorrow = new Date( i.getTime() + 86400000 );
            i = tomorrow;
        }
    }

    function jomres_micromanage_picker_get_epoch(jsdate)
    {
        var day     =   0;
        var mon     =   0;
        var year    =   0;
        var dateArray = new Array(3);

        dateArray = jsdate.split('-');
        day=dateArray[1];
        mon=dateArray[0];
        year=dateArray[2];

        return new Date(Date.UTC(year,mon-1,day,0,0,0));
    }

    // http://www.namepros.com/code/37251-javascript-random-hex-color.html
    function random_colour()
    {
        colours = new Array(14);
        colours[0]="0";
        colours[1]="1";
        colours[2]="2";
        colours[3]="3";
        colours[4]="4";
        colours[5]="5";
        colours[5]="6";
        colours[6]="7";
        colours[7]="8";
        colours[8]="9";
        colours[9]="a";
        colours[10]="b";
        colours[11]="c";
        colours[12]="d";
        colours[13]="e";
        colours[14]="f";

        digit = new Array(5);
        colour= "";
        for (i=0;i<6;i++){
            digit[i]=colours[Math.round(Math.random()*14)];
            colour = colour+digit[i];
        }
        return colour;
    }
</script>