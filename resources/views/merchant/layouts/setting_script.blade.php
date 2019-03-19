<script type="text/javascript">
    jQuery.noConflict();
    jQuery(document).ready(function($) {
        $('.datepicker').datepicker({
            format: 'mm-dd-yyyy'
        });

        $(function() {
            $('.datetimepicker3').datetimepicker({
                format: 'LT'
            });
        });

        /*$('input[name="merchant_category[]"]').click(function () {
            if ($('input[name="merchant_category[]"]:checked').length != 0) {
                $('#merchant_category_error').text('');
                $('#verification_submit').prop('disabled', false);
            }
        });

        $('#verification_submit').click(function () {
            if ($('input[name="merchant_category[]"]:checked').length == 0) {
                $('#merchant_category_error').text('Please choose at least one category').css('color','red');
                $('#verification_submit').prop('disabled', true);
            }
        });*/
    });
</script>