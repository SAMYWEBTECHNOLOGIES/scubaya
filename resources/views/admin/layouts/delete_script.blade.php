<script type="text/javascript">
    jQuery('.delete').click(function (e) {
        e.preventDefault();
        bootbox.confirm({
            title:"Delete Confirmation",
            message: "Are you sure, you want to delete this?",
            buttons: {
                confirm: {
                    label: 'Yes',
                    className: 'btn-success'
                },
                cancel: {
                    label: 'No',
                    className: 'btn-danger'
                }
            },
            size: 'small',
            callback: function (result) {
                if(result) {
                    jQuery(e.currentTarget).parent().submit();
                }
            }
        });
    });
</script>