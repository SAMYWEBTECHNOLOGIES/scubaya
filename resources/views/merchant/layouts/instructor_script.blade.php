<script type="text/javascript">

   jQuery(document).ready(function($) {
        $('.datepicker').datepicker();

        $('.fa-remove').click(function () {
//            alert('work man');
            $(this).closest('tr').remove();
        });



        $('.addBtn').on('click', function () {
            addTableRow();
        });

        var i = 1;
        var increment   =   4;
        function addTableRow()
        {
            $("#tableAddRow").append('' +
            '<tr id="addRow">' +
            '<td class="col-md-3">' +
            '<input type="text" class="form-control" id="affiliation" placeholder="affiliation" name="certifications[]">' +
            '</td>' +
            '<td class="col-md-3">' +
            '<input type="text" class="form-control" id="level" placeholder="level" name="certifications[]">' +
            '</td>' +
            '<td class="col-md-3">' +
            '<input type="text" class="form-control datepicker" data-date-format="yyyy/mm/dd" id="date" placeholder="date" name="certifications[]">' +
            '</td>' +
            '<td class="col-md-3">' +
            '<input type="text" class="form-control" id="number" placeholder="number" name="certifications[]">' +
            '</td>' +
            '<td>' +
            '<a onclick="delTableRow()" class="fa fa-remove addBtnRemove" id="addBtnRemove_0"></a>' +
            '</td>' +
            '</tr>');
            i++;
            $('.datepicker').datepicker();
        }
    });
    //delete the table row in affiliations
    function delTableRow(){
        jQuery('#addRow').remove();
    }


</script>