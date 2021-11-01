$.fn.tooltip.Constructor.DEFAULTS.trigger = 'hover';
$.fn.tooltip.Constructor.DEFAULTS.placement = 'bottom'; 

$.fn.modal.Constructor.prototype.enforceFocus = function() {};
$.fn.serializeObject = function()
{
    var o = {};
    var a = this.serializeArray();
    $.each(a, function() {
        if (o[this.name] !== undefined) {
            if (!o[this.name].push) {
                o[this.name] = [o[this.name]];
            }
            o[this.name].push(this.value || '');
        } else {
            o[this.name] = this.value || '';
        }
    });
    return o;
};

toastr.options = {
 "closeButton": true,
 "newestOnTop": true,    
 "showDuration": "5000",
 "hideDuration": "5000",
 "timeOut": "5000",
 "extendedTimeOut": "5000",
 "showEasing": "swing",
 "hideEasing": "linear",
 "showMethod": "fadeIn",
 "hideMethod": "fadeOut",
 "positionClass": "toast-top-full-width"
}
var APP_URL = $('meta[name="_base_url"]').attr('content');
$(document).ready(function(){
   	InitTooltip(); 
    InitSelect();
    InitDatePicker();
    InitMask();
    
    $.ajaxSetup({
        headers: { 'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content') },
        error : function(jqXHR, textStatus, errorThrown) {
            
            console.log("Error: " + textStatus + ": " + errorThrown);
            toastr.error('Oops! It looks like your session expried, Please reload Page and try again', 'Error!', { onHidden: function() {
                //window.location.reload();
                //alert('a');
            }});
            //alert('Something went wrong please try again!')
        },
    });

    if(timezone==''){
        getTimeZone();
    }
    /*$(document).ajaxStart(function() {      
        var $inputs = $("input, button, textarea, a");
        var $selects = $("select");
        $inputs.prop("disabled", true);
        $selects.prop("disabled", true);
    });
    $(document).ajaxStop(function() {
        var $inputs = $("input, button, textarea, a");
        var $selects = $("select");
        $inputs.prop("disabled", false);
        $selects.prop("disabled", false);
    });*/
});
function InitMask(){
    $("[data-mask]").inputmask();
}
function DisplayErrorMessages(Response,ErrorBlock,type){
    var ErrorHtml = "";
    if(type=='ul'){
        $.each(Response, function(index, element) {
            ErrorHtml += "<li>"+ element +"</li>";
        });

        ErrorBlock.find('ul').html(ErrorHtml);
        ErrorBlock.slideDown('1000');
    }else if(type=='toaster'){
        $.each(Response, function(index, element) {
            ErrorHtml += element +"<br>";
        });
        /*$.toaster({
            "closeButton": true,
            "extendedTimeOut":0,
            "priority": "danger",
            "positionClass": "toast-top-right",
            "message": "<br> "+ErrorHtml 
        });*/
        toastr.error(ErrorHtml, 'Error!');
    }
}

function DisplaySuccessMessage(Response,SuccessBlock){
    var SuccessHtml = Response;
    SuccessBlock.find('span').html(SuccessHtml);
    SuccessBlock.slideDown('1000');
}
function getParameterByName(name, url) {
    if (!url) url = window.location.href;
    name = name.replace(/[\[\]]/g, "\\$&");
    var regex = new RegExp("[?&]" + name + "(=([^&#]*)|&|#|$)"),
    results = regex.exec(url);
    if (!results) return null;
    if (!results[2]) return '';
    return decodeURIComponent(results[2].replace(/\+/g, " "));
}
function InitSelect(){
    $(".select2").select2({
        placeholder: SelectPlaceHolder,
        allowClear: true
    });
    $('.select2').on('select2:open', function (evt) {
      $('.select2-search__field').prop('disabled',false)
    });
}
function InitRate(){
    $(".rating-input").rating({
      theme: 'krajee-fa',
      filledStar: '<i class="fa fa-star"></i>',
      emptyStar: '<i class="fa fa-star-o"></i>',
      clearButton: '<i class="fa fa-ban"></i>',
      showClear: true,
      showCaption: false,
      hoverOnClear: false,
      displayOnly:true,
      min:0,
      max:5,
      step:0.5,
      size:'store-rate'
    });
}
function InitDatePicker(){
    var date = new Date();
    date.setDate(date.getDate() + 1);
    $('.date-picker').datepicker({
        todayHighlight: 1,
        format: "d M yyyy",
        autoclose: true,
        endDate: '+0d',
    })
}
function InitTooltip(){
	$('.show-tooltip').tooltip();
	$('[data-toggle="tooltip"]').tooltip(); 
}
function InitPopover(){
    $('body').popover({
        html:true,
        selector: "[data-toggle='popover']",
        trigger: 'click',
    }).on("show.bs.popover", function(e){                
        $("[data-toggle='popover']").not(e.target).popover("destroy");
        $(".popover").remove();                    
    });
    $('body').on('click', function (e) {
        if ($(e.target).data('toggle') !== 'popover'
            && $(e.target).parents('.popover.in').length === 0) { 
            $('[data-toggle="popover"]').popover('hide');
        }
    });
}
function sleep(milliseconds) {
  var start = new Date().getTime();
  for (var i = 0; i < 1e7; i++) {
    if ((new Date().getTime() - start) > milliseconds){
      break;
    }
  }
}
function getCities(element){
    var state = $(element).val();
	var Form = $(element).parents('form');
	var Holder = $(element).parents('.form-group');
    if(CurrentURL=='frontend.store_owner' || CurrentURL=='admin.store.details' || CurrentURL=='frontend.buyer-register'){
	   var CitiesHolder = $(Holder).parents('.row:first').find('.form-group:eq(1)');
    }else{
        var CitiesHolder = $(Holder).parents('.row:first').find('.form-group:eq(2)');
    }
	var CityList = $(CitiesHolder).find('select');

    var isSuccess = checkLimitedAccess(state,'');
    console.log(isSuccess);
    if(CurrentURL=='frontend.buyer-register' || CurrentURL=='frontend.store_owner' || CurrentURL=='frontend.cart-checkout' || CurrentURL=='pages.profile'){
        var Page_URL = APP_URL+"/get-cities";
    }else{
        var Page_URL = APP_URL+"/en/get-cities";
    }

    if(state!=''){
    	$(CityList).html('');
        $.ajax({
            type: "POST",
            url: Page_URL,
            data: "state_id="+state,
            dataType: "json",
            beforeSend: function(msg){
                $(Form).loader('show');
            },
            success: function(res) {
            	$(CityList).val('').change().html('');

                $(Form).loader('hide');
                if(res.cities!=''){
                    $.each(res.cities, function(index, sub_cats){
                        var HTML = "<option value='"+index+"'>"+sub_cats+"</option>";
                        $(CityList).append(HTML);
                    });
                }
                $(CityList).val('').change();
            }
        });
    }
}
function MakeDeleteModal(id,title,message,event,type){
    if(type=='1'){
        $.ajax({
            type: "GET",
            url: APP_URL+"/delete-modal",
            data: "title="+title+"&message="+message+"&event="+event+"&id="+id,
            dataType: "html",
            success: function(res) {
                $('body').append(res);
            },
        }).done(function(result){
            $('body').loader('hide');
            $('#DeleteModal').modal('show');
        });
    }else{
        var DeleteModal = $('#DeleteModal');
        $(DeleteModal).find('#title_holder').html(title);
        $(DeleteModal).find('#delete-modal-id').val(id);
        $(DeleteModal).find('#message_holder').html(message);
        $(DeleteModal).find('#btn_holder').attr('onclick',event);
        $('body').loader('hide');
        $('#DeleteModal').modal('show');
    }
}
function SaveNotificationForm(form){
    var Data = $(form).serialize();
    $.ajax({
      type: "POST",
      url: $(form).attr('action'),
      data: Data,
      dataType: "json",
      success: function(res) {
        if(res.status=='success'){
            toastr.success(Successmsg,Success, { onHidden: function() {
                $('#get_notification_modal').modal('hide');
                window.location.href=APP_URL;
            }});
        }
      },
      error: function (jqXHR, exception) {
        var Response = jqXHR.responseText,
        ErrorBlock = $(form).find('.form-errors'),
        Response = $.parseJSON(Response);
        DisplayErrorMessages(Response,ErrorBlock,'ul');
      }
    });
    return false;
}
function checkLimitedAccess(state,city){
    var LimitedAccess = true;
    if(LimitedAccess){
        var HomeDelivery = $('input[name="home_delivery"]:checked').val()
        if(HomeDelivery === undefined){
            HomeDelivery = $('input[name="homedelievery"]:checked').val()
        }
        if(HomeDelivery=='0'){
            if(state!='11' && city==''){
                toastr.warning(Limitedmsg,Warning);
                $('.limited-access-holder').show();
                $('.limited-access-holder').find('a').click();
                return false;
            }else{
                if(city!=null && city!=''){
                    if(city!='727'){
                        toastr.warning(Limitedmsg,Warning);
                        $('.limited-access-holder').show();
                        $('.limited-access-holder').find('a').click();
                        return false;    
                    }
                    else{
                        $('.limited-access-holder').hide();
                        return true;
                    }
                }else{
                    $('.limited-access-holder').hide();
                    return true;
                }
            }
        }else{
            $('.limited-access-holder').hide();
            return true;
        }
    }
}
function CheckCity(element){
    var state = $('#state').val();
    var city = $(element).val();
    if(city!=null){
        checkLimitedAccess(state,city)
    }
}
function getTimeZone(){
  var tz = jstz.determine(); // Determines the time zone of the browser client
  var timezone = tz.name();
  console.log(timezone);
  $.ajax({
    data: {timezone:timezone},
    type: 'POST',            
    url: APP_URL +'/create-cookies',
    dataType: "json",             
  });
}

function SetFormValues(Values,Form){
    $.each(Values, function(index, element) {
        switch(element.type) {
            case 'text':
            if(typeof element.wait != 'undefined'){
                setTimeout(function(){
                    var Input = $(Form).find('input[name="'+index+'"]');
                    Input.val(element.value);
                },500)
            }else{
                var Input = $(Form).find('input[name="'+index+'"]');
                Input.val(element.value);
            }
            break;
            case 'textarea':
            if(typeof element.wait != 'undefined'){
                setTimeout(function(){
                    var Input = $(Form).find('textarea[name="'+index+'"]');
                    Input.val(element.value);
                },500)
            }else{
                var Input = $(Form).find('textarea[name="'+index+'"]');
                Input.val(element.value);
            }
            break;
            case 'select':
                if(typeof element.wait != 'undefined'){
                    setTimeout(function(){
                        var Select = $(Form).find('select[name="'+index+'"]');
                        Select.val(element.value).change();
                    },1000)
                }else{
                    var Select = $(Form).find('select[name="'+index+'"]');
                    Select.val(element.value).change();
                }
            break;
            case 'multi-select':
                if(typeof element.wait != 'undefined'){
                    setTimeout(function(){
                        var Select = $(Form).find('select[name="'+index+'"]');
                        Select.val(element.value).change();
                    },1000)
                }else{
                    var Values = element.selectedValue;
                    var Select = $(Form).find('#'+index);
                    $('#'+index).val(Values).change();
                }
            break;
            case 'checkbox':
                var Values = element.checkedValue;
                $(Values).each(function (element,index) {
                    var Checkbox = $(Form).find('input:checkbox[name="'+index+'"][value="'+this+'"]');
                    Checkbox.iCheck('check');
                },[element,index]);
            break;
            case 'radio':
                var Value = element.selectedValue;
                var Radiobox = $(Form).find('input:radio[name="'+index+'"][value="'+Value+'"]');
                Radiobox.iCheck('check');
            break;
            case 'label':
                var Input = $(Form).find('label[for="'+index+'"]');
                Input.html(element.value);
            break;
            default:
                ''
        }
    });
}
function Refund(element){
    var Id = $(element).data('id');
    $.ajax({
      type: "POST",
      url: APP_URL+'/refund',
      data: {order_id:Id},
      dataType: "json",
      success: function(res) {
          toastr.success('Refunded successfully', 'Success', { onHidden: function() {
            window.location.reload
          }});
      },
      error : function(jqXHR, textStatus, errorThrown) {
        var StatusCode = jqXHR.status;
        if(StatusCode=='403'){
          toastr.error(jqXHR.responseJSON.error,"");
        }
      },
    });
}