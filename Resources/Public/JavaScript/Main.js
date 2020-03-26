define([
    'jquery',
    'TYPO3/CMS/Backend/Modal',
    'TYPO3/CMS/NsFaq/Main',
    'datatables',
    'TYPO3/CMS/Backend/jquery.clearable'
], function ($, Model) {
    $('.ns-ext-datatable').DataTable({
        "language": {
            "lengthMenu": "Display _MENU_ FAQs",
            "emptyTable": "No FAQs Available",
            "zeroRecords": "No matching FAQs found"
        },
    });

    $('.field-info-trigger').on('click', function(){
        $(this).parents('.form-group').find('.field-info-text').slideToggle();
    });
    
    $('.ns-ext-table-wrap .dataTables_length select,\ .ns-ext-table-wrap .dataTables_filter input').addClass('form-control');

    $('#TypoScriptTemplateModuleController').on('submit',function(e){
        e.preventDefault();
        url = $(this).attr('action');
        $.ajax({
            url:url,
            method:'post',
            data:$(this).serializeArray(),
            success:function(){
                window.location.reload();
            }
        })
    });
    $(document).on('click','.deleteFaq',function(e){
        deleteSingleItem($(this));
    });
   
    //CheckAll
    $(".ns-ext-select-all #checkAll").change(function() {
        if (this.checked) {
            $(".checkSingle").each(function() {
                this.checked=true;
            });
        } else {
            $(".checkSingle").each(function() {
                this.checked=false;
            });
        }
    });

    $(".ns-ext-checkmark-wrap .checkSingle").click(function () {
        if ($(this).is(":checked")) {
            var isAllChecked = 0;

            $(".ns-ext-checkmark-wrap .checkSingle").each(function() {
                if (!this.checked)
                    isAllChecked = 1;
            });

            if (isAllChecked == 0) {
                $(".ns-ext-select-all #checkAll").prop("checked", true);
            }
        }
        else {
            $(".ns-ext-select-all #checkAll").prop("checked", false);
        }
    });

    $(document).on('click', '.deleteSelectedBtn',function (e) {
        var id = [];
        var deleteUrl = $(this).data('href');
        var deleteButton = $(this).data('btnclass');
        var checkboxClass = $(this).data('checkbox');
        console.log(checkboxClass);
        $('.'+checkboxClass+':checkbox:checked').each(function(i){
            id[i] = $(this).val();
        });
        console.log(deleteUrl);
        $(document).on('click','.'+deleteButton,function(){
            $($(this)).closest('div[class^="modal fade"]').modal('hide');
            $.ajax({
                url: deleteUrl,
                data:{uids:id},
                success:function () {
                    window.location.reload();
                }
            });
        });

        e.preventDefault();
    });
});

function deleteSingleItem(selector){
    deleteUrl = $(selector).data('href');
    deleteButton = $(selector).data('btnclass');
    $(document).on('click','.'+deleteButton,function(){
        $($(this)).closest('div[class^="modal fade"]').modal('hide');
        $.ajax({
            url: deleteUrl,
            success:function () {
                window.location.reload();
            }
        });
    });
}
