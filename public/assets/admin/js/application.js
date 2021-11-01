$.fn.dataTable.ext.errMode = function ( settings, helpPage, message ) { 
    console.log(message);
    console.log();
    console.log(helpPage);
    
    toastr.error(settings.jqXHR.responseJSON.message, 'Error!', { onHidden: function() {
        //window.location.reload();
        //alert('a');
    }});
};
var global_sub_cat = "";
$(document).ready(function(){
    $( document ).ajaxStart(function() {
        $("body").loader('show');
    });
    $( document ).ajaxStop(function() {
        $("body").loader('hide');
    });

    if($(".file-input").length>0)
    {
        $('.file-input').each(function(i, row) {
            var SelectedImage = $(this).attr('data-image');
            $(this).fileinput({
                showUpload: false,
                showRemove: false,
                previewSettings:{
                    image: {width: "100%", height: "100%",'float':'left'},
                }
            })
            
            if(SelectedImage != '' && SelectedImage !== undefined){

                $(this).fileinput('destroy');    
                $(this).fileinput('refresh',{
                    'initialPreview': [
                        '<img src="'+SelectedImage+'" class="file-preview-image" width="auto">'
                    ],
                    'initialPreviewAsData': true,
                    'overwriteInitial': true,
                    showUpload: false,
                    showRemove: false,
                    
                })
            }
        });
        // $(".file-input").fileinput({
        //     showUpload: false,
        //     showRemove: false,
        //     previewSettings:{
        //         image: {width: "100%", height: "100%",'float':'left'},
        //     }
        // });
    }
    $('.file-input').on('fileclear', function(event) {
        //alert("fileclear");
    });
    $(".alert-success-message").delay(2000).slideUp(200, function() {
        $(this).alert('close');
    });
    $('.i-check, .i-radio').iCheck({
        checkboxClass: 'icheckbox_square-blue',
        radioClass: 'iradio_square-blue'
    });
})
function getSubCategories(element){
    var cat = $(element).val();
    var Form = $(element).parents('form');
    var Holder = $(element).parents('.form-group');
    var SubCategoryHolder = $(Holder).next('.form-group');
    
    var SubList = $(SubCategoryHolder).find('select');
    if(cat!=''){
        $.ajax({
            type: "POST",
            url: APP_URL+"/get-subs",
            data: "cat_id="+cat,
            dataType: "json",
            beforeSend: function(msg){
                $(Form).loader('show');
            },
            success: function(res) {

                $(SubList).val('').change().html('');
                $(Form).loader('hide');
                if(res.sub_cats!=''){
                    $.each(res.sub_cats, function(index, sub_cats){
                        var HTML = "<option value='"+index+"'>"+sub_cats+"</option>";
                        $(SubList).append(HTML);
                    });
                }
                $(SubList).val(global_sub_cat).change();
            }
            
        });
    }
}
function ResetForm(form){
    $(form)[0].reset();
    $(form).find('select').val('').change();
    $(form).find('input:checkbox').iCheck('uncheck');
    $(form).find('input:checkbox').iCheck('update');
    $(form).find('input:radio').prop('checked',false).change();
}