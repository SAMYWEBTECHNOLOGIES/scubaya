<script type="text/javascript">
    $(document).ready(function(){
        var getDataIds = [];
        $('#group option').each(function(){
            getDataIds.push($(this).data('id'));
        });

        getDataIds.join('|');

        var dataIds =   cleanArray(getDataIds);

        var options =   $('#group option').map(function(option){

            var id  =   $(this).data('id');

            if(typeof id != 'undefined'){
                return '<option value="'+$(this).val()+'">'+$(this).text()+'<option>';
            }
        }).get();

        $('#group option').map(function(){
            var id  =   $(this).data('parent');
            var val =   parseInt($(this).val());

            if(typeof id != 'undefined'){
                dataIds.splice(dataIds.indexOf(id)+1,0,val);
                options.splice(dataIds.indexOf(id)+1,0,'<option value="'+$(this).val()+'">'+$(this).text()+'<option>');
            }
        });

        $('#group option').not(':selected').remove();
        $.each(options, function (i,option) {
            $('#group').append(option);
        });
        $('#group option').filter(function() {
            return !this.value || $.trim(this.value).length == 0;
        }).remove();


        $.each(options, function (i,option) {
            $('#edit_group_options').append(option);
        });
        $('#edit_group_options option').filter(function() {
            return !this.value || $.trim(this.value).length == 0;
        }).remove();

        $('.selectpicker').selectpicker('refresh');

        $('.dropdown-submenu a.test').on("click", function(e){
            $(this).next('ul').toggle();
            e.stopPropagation();
            e.preventDefault();
        });

        $.fn.extend({
            filterTable: function(){
                return this.each(function(){
                    $(this).on('keyup', function(e){
                        $('.filterTable_no_results').remove();
                        var $this = $(this),
                            search = $this.val().toLowerCase(),
                            target = $this.attr('data-filters'),
                            $target = $(target),
                            $rows = $target.find('tbody tr');

                        if(search == '') {
                            $rows.show();
                        } else {
                            $rows.each(function(){
                                var $this = $(this);
                                $this.text().toLowerCase().indexOf(search) === -1 ? $this.hide() : $this.show();
                            })
                        }
                    });
                });
            }
        });
        $('[data-action="filter"]').filterTable();

        $('.container').on('click', '.panel-heading span.filter', function(e){
            var $this = $(this),
                $panel = $this.parents('.panel');

            $panel.find('.panel-body').slideToggle();
            if($this.css('display') != 'none') {
                $panel.find('.panel-body input').focus();
            }
        });
        $('[data-toggle="tooltip"]').tooltip();

        $('ul.dropdown-menu [data-toggle=dropdown]').on('click', function(event) {
            event.preventDefault();
            event.stopPropagation();
            $(this).parent().siblings().removeClass('open');
            $(this).parent().toggleClass('open');
        });
    });

    function cleanArray(actual) {
        var newArray = new Array();
        for (var i = 0; i < actual.length; i++) {
            if (actual[i]) {
                newArray.push(actual[i]);
            }
        }
        return newArray;
    }
</script>