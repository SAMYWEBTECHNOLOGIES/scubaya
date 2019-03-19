<script type="text/javascript">
    jQuery(document).ready(function(scubaya) {
        /*call the draggable and droppable initially*/
        droppable();draggable();

        var comb_no     =   2;
        /*event calling on add pool*/
        scubaya('.add-pool').on('click',function () {
           scubaya(this).before('<div class="from-group combination" data-comb="'+comb_no+'" style="width: 550px;height:100px;border: 2px solid;padding: 5px;margin: 25px;"></div>');
           comb_no++;
           droppable();
        });

        scubaya('.datepicker').datepicker();

        scubaya('.datetimepicker3').datetimepicker({
            format: 'LT'
        });

        function draggable() {
            scubaya( "#divers span" ).draggable({
                cursor: "crosshair",
                helper:'clone'
            });

            scubaya( "#instructors span" ).draggable({
                helper:'clone',
                cursor: "crosshair"
            });

            scubaya( "#locations span" ).draggable({
                helper:'clone',
                cursor: "crosshair"
            });

            scubaya( "#boats span" ).draggable({
                helper:'clone',
                cursor: "crosshair"
            });
        }

        function droppable(){
            scubaya('.combination').droppable({
                tolerance   :'fit',

                drop:function (event, ui) {

                    let id           =   scubaya(ui.draggable).attr('id');
                    let name         =   scubaya(ui.draggable).data('name');
                    let html         =   scubaya(ui.draggable).html();
                    let scope        =   scubaya(ui.draggable).data('scope');
                    let pad          =   scubaya(ui.draggable).attr('class');
                    let comb         =   scubaya(this).data('comb');
                    let comb_drag    =   scubaya(ui.draggable).parent().closest('div').data('comb');

                    if (typeof comb_drag !== 'undefined'){
                        scubaya(ui.draggable).parent().closest('div').find('[name="comb['+comb_drag+']['+id+']"]').remove();
                    }

                    scubaya(ui.draggable).remove();

                    scubaya(this).append('<span class="'+scope+' '+pad+'" id="'+id+'" data-scope="'+scope+'" data-name="'+name+'">'+html+'</span>' +
                        '<input type=hidden name="comb['+comb+']['+id+']">');

                    scubaya('#'+id).draggable({
                        helper:'clone'
                    })
                }
            });

            scubaya('#divers').droppable({
                accept:".divers",
                drop:function(event,ui){
                    reverseDroppable(event,ui,this);
                }
            });

            scubaya('#instructors').droppable({
                accept:".instructors",
                drop:function(event,ui){
                    reverseDroppable(event,ui,this);
                }
            });

            scubaya('#locations').droppable({
                accept:".locations",
                drop:function(event,ui){
                    reverseDroppable(event,ui,this);
                }
            });

            scubaya('#boats').droppable({
                accept:".boats",
                drop:function(event,ui){
                    reverseDroppable(event,ui,this);
                }
            });
        }

        function reverseDroppable(event,ui,t)
        {
            let id      =   scubaya(ui.draggable).attr('id');
            let name    =   scubaya(ui.draggable).data('name');
            let html    =   scubaya(ui.draggable).html();
            let scope   =   scubaya(ui.draggable).data('scope');
            let pad     =   scubaya(ui.draggable).attr('class');
            let comb    =   scubaya(ui.draggable).parent().closest('div').data('comb');

            scubaya(ui.draggable).parent().closest('div').find('[name="comb['+comb+']['+id+']"]').remove();

            scubaya(ui.draggable).remove();

            scubaya(t).append('<span class="'+pad+'" id="'+id+'" data-scope="'+scope+'" data-name="'+name+'">'+html+'</span>');

            scubaya('#'+id).draggable({
                helper:'clone'
            })
        }
    });
</script>