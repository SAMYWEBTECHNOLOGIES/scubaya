<script type="text/javascript">

    $('#subscribe').submit(function(e){
        e.preventDefault();
        /*$('#login').button('loading');*/
        var email         =   $('#email').val();
        var token         =   "{{ csrf_token() }}";
        var url           =   "{{ route('scubaya::subscribe') }}";

        $.post( url,{ _token:token,email:email }, function( status )
        {
            /*$('#login').button('reset');*/
            if($.isEmptyObject(status.errors)){
                $('#email').val('');
                $('.scu-subscription #subscribe .ui.form').css('margin-top', '18px');
                $('#success-message').empty().show().addClass('ui success message').append('Your are subscribed now').delay(5000).fadeOut();
                setTimeout(function () {
                    $('.scu-subscription #subscribe .ui.form').css('margin-top', '0px');
                }, 5000);
            }
            else{
                if ('email' in status.errors){
                    $('#email_warning').empty().show().append(status.errors.email[0]).css('color','red').delay(5000).fadeOut();
                }
            }
        });
    });
</script>