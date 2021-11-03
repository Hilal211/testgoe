    //var offset = 0;
    var idG=0;
var TotalStoreItems = 0;
var idleTime = 0;
var cartItem = [];
var gcid = "";
var isHome = "0";
var sortSelect = function (select, attr, order) {
    if(attr === 'text'){
        if(order === 'asc'){
            $(select).html($(select).children('option').sort(function (x, y) {
                return $(x).text().toUpperCase() < $(y).text().toUpperCase() ? -1 : 1;
            }));
            $(select).get(0).selectedIndex = 0;
            //e.preventDefault();
        }// end asc
        if(order === 'desc'){
            $(select).html($(select).children('option').sort(function (y, x) {
                return $(x).text().toUpperCase() < $(y).text().toUpperCase() ? -1 : 1;
            }));
            $(select).get(0).selectedIndex = 0;
            //e.preventDefault();
        }// end desc
    }

};
$(document).ready(function () {
    /*if (!$.cookie('guid'))
    {
        //gcid = guid();
        //$.cookie('guid', gcid, {expires: 7, path: '/'});
    }
    else
    {
        gcid = $.cookie('guid');
    }*/
    $( "#frmCheckout" ).submit(function( event ) {
        $('select#shipping_state,select#shipping_city').select2({disabled:false});
        //event.preventDefault();
        setTimeout(function(){
            $(this).submit();
        },200)
    })
    $('.dropdown-menu-category-section-content').each(function(){
        var SubCatLength = $(this).find('.sub-category-section').length
        var CatHolder = $(this).parents('li')
        if(SubCatLength==0){
            CatHolder.remove()
        }
    })
    var date = new Date();
    var NextDayArr = ['5PM','6PM','7PM','8PM','9PM','10PM','11PM'];
    if(CurrentURL=='frontend.cart-checkout'){
        if(CurrentHour !== undefined){
            if ($.inArray(CurrentHour,NextDayArr) != -1){
              date.setDate(date.getDate() + 1);  
            }
        }
    }
    //
    var disabledAttr = $('#preferred_date').attr('data-daysOfWeekDisabled');
    $('#preferred_date').datepicker({
        format: "d M yyyy",
        autoclose: true,
        startDate: date,
        daysOfWeekDisabled: disabledAttr
    }).on('changeDate', function(e) {
        var MoveLast = false;
        var element = e.target;
        var value  = $(element).val();
        var today = new Date();
        var TimeSelect = $(element).parents('.col-md-6').next('.col-md-6').find('select');
        $(TimeSelect).val('').change().html('');
        $(TimeSelect).select2('destroy'); 
        today.setHours(0);
        today.setMinutes(0);
        today.setSeconds(0);
        if (Date.parse(today) == Date.parse(value)) {
            $.each(JSON.parse(TodaySlot), function(index, title){
                var HTML = "<option value='"+index+"'>"+title+"</option>";
                $(TimeSelect).prepend(HTML);
            });
            MoveLast = false;
        }else{
            $.each(JSON.parse(OtherSlot), function(index, title){
                var HTML = "<option value='"+index+"'>"+title+"</option>";
                $(TimeSelect).prepend(HTML);
            });
            MoveLast = true;
        }
        $(element).parents('.col-md-6').next('.col-md-6').show();
        var selectList = $(TimeSelect).find('option')
        //selectList.sort(sort);
        $(TimeSelect).html(selectList);
        sortSelect('#preferred_time','text','asc');
        
        if($(TimeSelect).find('option:last-child').text()=='9AM - 10AM'){
            $(TimeSelect).find('option:last-child').insertBefore($(TimeSelect).find('option:eq(1)'));
            $(TimeSelect).find('option:last-child').remove();
        }

        $(TimeSelect).select2({
            placeholder: "Select",
            allowClear: true,
        });
        $(TimeSelect).val('').change();
    }).on('clearDate', function(e) {
        var element = e.target;
        $(element).parents('.col-md-6').next('.col-md-6').hide();
    });
    
    $(".autocomplete").autocomplete({
       //source: availableProducts
       source: function(request, response) {
            var results = $.ui.autocomplete.filter(availableProducts, request.term);

            if (!results.length) {
                results = [NoResultsLabel];
            }

            response(results);
        },
   }).autocomplete( "instance" )._renderItem = function( ul, item ) {
        var $img = $('<img>');
        $img.attr({
            src: item.image
        });
        return $( "<li>" )
        .append($img)
        .append( " "+item.value)
        .appendTo( ul );
    }

    $(".autocomplete").on("autocompleteselect", function (event, ui) {
        if (ui.item.label === NoResultsLabel) {
            event.preventDefault();
        }else{
            addToCart(ui.item.id);
            setTimeout(function(){
                $(event.target).val('');
            },100)
        }
    });
    $('.addresses').on('ifChecked', function(event){
        ResetAddress();
        ChangeAddress(event.target);
    });
    if($('#total-cart-items').length>0){

        if(getParameterByName('refer')=="store-selection" || getParameterByName('refer')=="cart"){
            reloadCartItem();
        }else{
            //alert('a');
            changeCartIcon();
        }
    }else{
        reloadCartItem();
    }
    if(CurrentURL=='customer.dashboard' || CurrentURL=='frontend.home'){
        isHome='1';
    }

    var LoggedMenu = $('#logged_in_menu').length;
    if(LoggedMenu!='0'){
        var idleInterval = setInterval(timerIncrement, 60000); // 1 minute
    }

    $(this).mousemove(function (e) {
        idleTime = 0;
    });
    $(this).keypress(function (e) {
        idleTime = 0;
    });
});
function timerIncrement() {
    idleTime = idleTime + 1;
    if (idleTime > 9) { // 10 minutes
        window.location.href=LogoutURL;
    }
}
function sort(a,b){
    a = a.text.toLowerCase();
    b = b.text.toLowerCase();
    if(a > b) {
        return 1;
    } else if (a < b) {
        return -1;
    }
    return 0;
}
function addToCart(pid){
    $("body").loader('show');
    $.ajax({
        type: "POST",
        url: APP_URL + '/cart/add',
        data: "pid=" + pid + "&guid=" + gcid,
        dataType: "json",
        success: function (response) {
            if(CurrentURL=='get.frontend.store-selection'){
                $("#storemsg").html('');
                $("#storemsg").fadeOut();
                $('#ProductPriceModal').modal('hide');
                
                $('.tbody-store-list').html('');
                reloadStore();
                changeCartIcon();
            }else{
                if (response.result == "success"){
                    reloadCartItem();
                }
            }
        },
        error: function () {

        }
    });
}
function reloadCartItem(){
    if(isHome!='1' && getParameterByName('refer')!="cart" && getParameterByName('refer')!="store-selection"){
        window.location.href=APP_URL+'?refer=cart';
    }else{
        $("body").loader('show');
        $.ajax({
            type: "GET",
            url: APP_URL + '/cart/list',
            data: "guid=" + gcid,
            dataType: "json",
            success: function (response) {
                var storedCartItem = response
                if (storedCartItem.length > 0) {
                    changeCartIcon(storedCartItem);
                    $("#empty-shopping-cart").addClass("hide");
                    $("#full-shopping-cart").removeClass("hide");
                    var html = "";
                    $.each(storedCartItem, function (index, item) {
                        html += '<tr>';
                        html += '<td class="table-shopping-cart-img">';
                        html += '<a href="javascript:;"><img  data-toggle="popover" title="'+item.product_name+'" data-content=\''+item.info+'\' data-placement="top" src="' + item.image + '" alt="' + item.product_name + '" title="' + item.product_name + '" /></a>';
                        html += '</td>';
                        html += '<td class="table-shopping-cart-title"><a href="javascript:;" data-toggle="popover" title= "'+item.product_name+'" data-content=\''+item.info+'\' data-placement="top">' + item.product_name + '</a></td>';
                        html += '<td>';
                        html += '<div class="input-group col-md-1">';
                        html += '<span class="input-group-addon btn-cart" onclick="changeQty(\'' + item.id + '\',this,\'minus\')"><i class="fa fa-minus"></i></span>';
                        html += '<input type="text" class="form-control table-shopping-qty show-tooltip" title="'+TypeQty+'" data-placement="top" onkeypress="EnterQty(\'' + item.id + '\',this,event)" value="' + item.qty + '">';
                        html += '<span class="input-group-addon btn-cart" onclick="changeQty(\'' + item.id + '\',this,\'add\')"><i class="fa fa-plus"></i></span>';
                        html += '</div>';
                        html += '</td>';
                        html += '<td>';
                        html += item.item_name;
                        html += '</td>';
                        html += '<td><a class="fa fa-close table-shopping-remove" href="javascript:void(0);" onclick="removeCartItem(\'' + item.id + '\')"></a></td>';
                        html += '</tr>';
                    });
                    $(".tbody-cart-items").html(html);
                    InitTooltip();
                    InitPopover();
                }
                else
                {
                    $("#empty-shopping-cart").removeClass("hide");
                    $("#full-shopping-cart").addClass("hide");
                    $(".check-cart-icon").removeClass('cart-success');
                    $("#total-cart-items").html(MyCart+' (0)');
                }
                $("body").loader('hide');
            },
        error: function () {
            $("body").loader('show');
        }
        });
    }
}

function removeCartItem(cid){
    $("body").loader('show');
    $.ajax({
        type: "DELETE",
        url: APP_URL + '/cart/delete',
        data: "cid=" + cid,
        dataType: "json",
        success: function (response) {
            if (response.result == "success")
            {
                reloadCartItem();
            }
        },
        error: function () {
            $("body").loader('hide');
        }
    });
}
function changeQty(cid, ele, opt){
    var qty = parseInt($(ele).next(".table-shopping-qty").val());
    if (opt == "add") {
        var qty = parseInt($(ele).prev(".table-shopping-qty").val());
        qty++;
    }
    else {
        var qty = parseInt($(ele).next(".table-shopping-qty").val());
        qty--;
    }
    if (qty >= 1)
    {
        $("body").loader('show');
        $.ajax({
            type: "PUT",
            url: APP_URL + '/cart/update',
            data: "cid=" + cid+"&opt="+opt,
            dataType: "json",
            success: function (response) {
                if (response.result == "success")
                {
                    reloadCartItem();
                }
            },
            error: function () {
                $("body").loader('hide');
            }
        });
    }
}
function guid() {
    var date = new Date();
    var components = [
    date.getYear(),
    date.getUTCMonth(),
    date.getUTCDate(),
    date.getUTCHours(),
    date.getUTCMinutes(),
    date.getUTCSeconds(),
    date.getUTCMilliseconds(),
    Math.floor(Math.random())
    ];
    var uniqid = components.join("");
    return uniqid;
}

function reloadStore(offset,limit){
    if (typeof(offset)==='undefined') offset = '0';
    if (typeof(limit)==='undefined') limit = '5';

    $("body").loader('show');
    $.ajax({
        type: "POST",
        url: APP_URL + '/cart/store-selection',
        data: "guid=" + gcid+'&limit='+limit+'&offset='+offset,
        dataType: "json",
        success: function (response) {
            var cartTotalItem = response.total_cart_product;
            var storedCartItem = response.storeList;
            var id=response.user_id;
            TotalStoreItems = response.totcount;
            
            if (storedCartItem.length > 0) {
                $("#empty-shopping-cart").addClass("hide");
                $("#full-shopping-cart").removeClass("hide");
                var html = "";
                $.each(storedCartItem, function (index, item) {
                    var Class = '';
                    var imageHTMl = '';
                    html += '<tr>';
                    if(item.image!=''){
                        imageHTMl = '<div class="col-md-2"><img class="store_image" src="'+item.image+'"></div>';
                    }
                    html += '<td class="table-shopping-cart-title">'+imageHTMl+'<div class="col-md-10">'+item.storename+'\
                    <div class="col-md-12 no-padding"> <div class="col-md-5 no-padding"> <input class="rating-input" value="'+item.avg_rating+'" type="number"></div>';
                    if(item.avg_rating!=null){
                        html += ' <div class="col-md-6 no-padding">\
                                  <a href="javascript:;" onclick="ShowAllRatings(this,\''+item.id+'\')" class="show-tooltip" title="'+RateMsg+'" data-placement="top">\
                                  <b>['+item.avg_rating+']</b></a>\
                                  </div>';
                    }
                    html += '</div>';
                    if(item.is_virtual == 0){
                        html += '<small class="col-md-12 no-padding">'+item.add1+' '+item.add2+' '+item.zip+'</small></div></td>';
                    }else{
                        html += '<small class="col-md-12 no-padding">'+item.zip+'</small></div></td>';
                    }
                    html += '<td>'+item.distance+' Miles</td>';
                    html += '<td class="text-right">'+item.qty_total+'</td>';
                    Class= item.store_qty_check;
                    // if((parseFloat(item.total_product)<cartTotalItem)){
                    //     Class= "table-shopping-orange";
                    // }else{
                    //     Class = "table-shopping-success";
                    // }
                    var price=item.qty_total;
                    price=price.substring(1);
                    var leftToCheckout=50-price;
                    leftToCheckout=leftToCheckout.toFixed(2);
                    html += '<td><a class="'+Class+' item-link cart-item-1 cart-indicator show-tooltip" title="'+ViewItems+'" href="javascript:;" onclick="ShowProducts(\''+Class+'\',\''+item.id+'\')">'+item.total_product+'/'+cartTotalItem+'</a></td>';
                    if(price<50){
                    html += '<td><a href="#" class="btn btn-primary btn-sm pull-right"  style="background-color:gray;opacity:0.5;cursor: not-allowed;"> $'+leftToCheckout+' left to checkout</a></td>';
                    }else{
                    html += '<td><a href="javascript:void(0);" onclick="checkOutStore(\''+item.id+'\',\'' + id + '\');" class="btn btn-primary btn-sm pull-right"><i class="fa fa-cart-plus"></i> '+Pay+' >></a></td>';
                    }
                    html += '</tr>';
                });
                $(".tbody-store-list").append(html);
                InitTooltip();
                InitRate();
            }
            else
            {
                $("#storemsg").html(msg);
                $("#storemsg").fadeIn();
            }
            $("body").loader('hide');
            
            var Oldoffset = $('#offset').val();
            var newOffset = parseFloat(Oldoffset) + parseFloat(5);
            
            $('#offset').val(newOffset)

            if(newOffset < TotalStoreItems){
                $("#loadmore").show();
            }
            else{
                $("#loadmore").hide(); //$("#loadmore").hide();
            }
        },
        error: function () {
            $("body").loader('show');
        }
    });
}
function ShowProducts(link_class,sid){
    $("body").loader('show');
    $.ajax({
        type: "POST",
        url: APP_URL + '/cart/'+sid+'/store_product_details',
        data: {class:link_class},
        dataType: "json",
        success: function (response) {
            $("body").loader('hide');
            $('#ProductPriceModal').remove();
            $('body').append(response.HTML);
            $('#ProductPriceModal').modal('show');
        },
        error: function () {
            $("body").loader('hide');
        }
    });
}
function showRelated(sid,productId){
    $("body").loader('show');
    $.ajax({
        type: "POST",
        url: APP_URL + '/cart/'+sid+'/related_products/'+productId,
        data: {},
        dataType: "json",
        success: function (response) {
            $("body").loader('hide');
            $('#related_products_table').show();
            $('#related_products_table').find('tbody').html('');
            $.each(response.products, function (index, item) {
                $('#related_products_table').find('tbody').append($('<tr>')
                    .append($('<td>').html(item.product_name))
                    .append($('<td>').html(item.price))
                    .append($('<td>').append($('<a>')
                                        .attr('href', 'javascript:void(0);')
                                        .attr('onclick', 'addToCart("'+item.related_product_id+'")')
                                        .text('Add to cart')
                    ))
                );
            });
        },
        error: function () {
            $("body").loader('hide');
        }
    });
}
function checkOutStore(sid,id){
    var LoggedMenu = $('#logged_in_menu').length;
    if(LoggedMenu=='0'){
        toastr.warning(LoginMsg, Warning);
        $('a[href="#nav-login-dialog"]').click();
        $('#nav-login-dialog').find('#pay_store_id').val(sid);
    }else{
        window.location.href=APP_URL+"/cart/"+sid+"/checkout";
        var idd= parseInt(id);
    
        var data = new FormData();
        data.append("user_id",idd); 
        data.append("step",3); 

        $.ajax({
            type: "POST",
            enctype: 'multipart/form-data',
            url:APP_URL + "/addstat",
            data: data,
            processData: false,
            contentType: false,
            cache: false,
            timeout: 800000,
            success: function (data) {
                console.log("SUCCESS : ");
            },
            error: function (e) {
                $("#output").text(e.responseText);
                console.log("ERROR : ", e);
                $("#btnSubmit").prop("disabled", false);
            }
        });

    }
}
 function selectStore(id){
    var LoggedMenu = $('#logged_in_menu').length;
    if(LoggedMenu=='0'){
        toastr.warning(LoginMsg, Warning);
        $('a[href="#nav-login-dialog"]').click();
    }else{
        window.location.href=APP_URL+"/cart/store-selection";
        console.log(id);
        var idd= parseInt(id);
    
        var data = new FormData();
        data.append("user_id",idd); 
        data.append("step",2); 

        $.ajax({
            type: "POST",
            enctype: 'multipart/form-data',
            url:APP_URL + "/addstat",
            data: data,
            processData: false,
            contentType: false,
            cache: false,
            timeout: 800000,
            success: function (data) {
                console.log("SUCCESS : ");
            },
            error: function (e) {
                $("#output").text(e.responseText);
                console.log("ERROR : ", e);
                $("#btnSubmit").prop("disabled", false);
            }
        });

    }
}
function changeCartIcon(storedCartItem){
    if (typeof(storedCartItem)==='undefined') storedCartItem = '';

    if(storedCartItem!=''){
        if (storedCartItem.length > 0) {
            $(".check-cart-icon").addClass('cart-success');
            $("#total-cart-items").html(MyCart+' ('+storedCartItem.length+')');
        }
        else
        {
            $(".check-cart-icon").removeClass('cart-success');
            $("#total-cart-items").html(MyCart+' (0)');
        }
        return false;
    }
    $.ajax({
        type: "GET",
        url: APP_URL + '/cart/list',
        data: "guid=" + gcid,
        dataType: "json",
        success: function (response) {
            var storedCartItem = response
            if (storedCartItem.length > 0) {
                $(".check-cart-icon").addClass('cart-success');
                $("#total-cart-items").html(MyCart+' ('+storedCartItem.length+')');
            }
            else
            {
                $(".check-cart-icon").removeClass('cart-success');
                $("#total-cart-items").html(MyCart+' (0)');
            }
            $("body").loader('hide');
        },
        error: function () {
            $("body").loader('show');
        }
    });
}
function reloadStoreProducts(sid){
    $("body").loader('show');
    $.ajax({
        type: "POST",
        url: APP_URL + '/cart/'+sid+'/products',
        data: "guid=" + gcid,
        dataType: "json",
        success: function (response) {
            var storedCartItem = response.productList;
            if (storedCartItem.length > 0) {
                $("#empty-shopping-cart").addClass("hide");
                $("#full-shopping-cart").removeClass("hide");
                var html = "";
                $.each(storedCartItem, function (index, item) {
                    html += '<tr>';
                    html += '<td class="table-shopping-cart-img">';
                    //html += '<a href="#"><img src="' + item.image + '" title="' + item.product_name + '" /></a>';
                    html += '<a href="javascript:;"><img data-toggle="popover" title="'+item.product_name+'" data-content=\''+item.info+'\' data-placement="top" src="' + item.image + '" alt="' + item.product_name + '" title="' + item.product_name + '" /></a>';
                    html += '</td>';
                    html += '<td class="table-shopping-cart-title"><a href="javascript:;" data-toggle="popover" title="'+item.product_name+'" data-content=\''+item.info+'\' data-placement="top" >' + item.product_name + '</a></td>';
                    html += '<td class="text-right">'+ item.price + '</td>';
                    if(parseFloat(item.qty)<parseFloat(item.inventory)){
                        html += '<td>'+item.qty+' '+item.item_name+'</td>';    
                    }else{
                        html += '<td>'+item.inventory+' '+item.item_name+'</td>';
                    }
                    //html += '<td>'+item.inventory+' / ' + item.qty + ' '+item.item_name+'</td>';
                    
                    html += '<td class="text-right">'+ item.total_product_price + '</td>';
                    html += '</tr>';
                });
                html += '<tr><td colspan="4" class="text-right"><b>'+SubTotal+':</b> </td><td class="text-right"><b id="subTotal">'+response.subTotal+'</b></td></tr>';
                html += '<tr id="DiscountRow" style="display:none"><td colspan="4" class="text-right"><a href="javascript:;" onclick="removeCoupon()" class="show-tooltip" title="Remove Coupon"><i class="fa fa-times-circle"></i></a> <b>('+CouponLabel1+' "<span id="couponText"></span>" '+CouponLabel2+') '+Discount+':</b> </td><td class="text-right"><b id="DiscountAmt"></b></td></tr>';
                html += '<tr><td colspan="4" class="text-right"><b>'+ShippingCost+' <span id="Ship_Miles"></span>:</b> </td><td class="text-right"><b id="shippingCharge">'+response.shippingCharge+'</b></td></tr>';

                html += '<tr> \
                            <td rowspan="2" colspan="2" class="text-right"><b id="tax_description" class="p-right-20"></b></td>\
                            <td class="text-right" colspan="2"><b>'+Federal+' <b id="f_taxPer">0</b> </td>\
                            <td class="text-right"><b> <b id="f_taxTotal">0</b> </td>\
                        </tr>';

                html += '<tr> \
                            <td class="text-right border-t-0" colspan="2"><span><b>'+Province+' <b id="p_taxPer">0</b></span> <b id="taxTotal" class="display-none">$0.00</b></td>\
                            <td class="text-right border-t-0"><b id="p_taxTotal">0</b></td>\
                        </tr>';
                        
                //html += '<tr><td colspan="4" class="text-right"><b>Tax :</b> </td><td class="text-right"><b id="taxTotal">$0.00</b></td></tr>';
                html += '<tr><td colspan="4" class="text-right"><b>'+Recycling+'</b> </td><td class="text-right"><b id="recycle_fee"></b></td></tr>';

                html += '<tr><td colspan="4" class="text-right"><b>'+Grand+' :</b> </td><td class="text-right"><b id="grandTotal">'+response.grandTotal+'</b></td></tr>';
                $(".tbody-store-list").html(html);
                InitTooltip();
                InitPopover();
            }
            else{
                window.location.href=APP_URL;
            }
            $("body").loader('hide');
            $('#store_details_section').find('#address').html(response.store_address);
            console.log(response.store.is_virtual);
            if(response.store.is_virtual == 1){
                $('#store_details_section').find('#store_name').html(response.store.legalentityname);
            }else{
                $('#store_details_section').find('#store_name').html(response.store.storename);
            }
            $('#store_details_section').find('#phone').html(response.store.contactnumber);
        },
        error: function () {
            $("body").loader('show');
        }
    });
}
function ChangeAddress(element){
    var Parent = $(element).parents('.radio-inline');
    var Phone = $(Parent).find('#phn').html();
    var Email = $(Parent).find('#email').html();
    var Address = $(Parent).find('#add').html();
    var Apt = $(Parent).find('#apt').html();
    var City = $(Parent).find('#city').data('id');
    var State = $(Parent).find('#state').data('id');
    var ZipCode = $(Parent).find('#zip').html();

    $('#shipping_phone').val(Phone);
    $('#shipping_email').val(Email);
    $('#shipping_address').val(Address);
    $('#shipping_apt').val(Apt);
    
    $('select[name="shipping_state"]').val(State).change();
    setTimeout(function(){
        $('select[name="shipping_city"]').val(City).change();
    },1000)
    $('#shipping_zip').val(ZipCode);
    ResetAddress();
}
function displayMoreRow(){
    var offset = $('#offset').val();
    // if(TotalStoreItems < offset){
    //     $("#storemsg").html(msg); //alert("No more store available");
    //     $("#storemsg").fadeIn();
    //     $('#storemsg').delay(2000).fadeOut();
    //     $("#loadmore").find('a').attr('disabled','disabled'); //$("#loadmore").hide();
    //     return false;
    // }
    reloadStore(offset);
}
function SaveShippingAddress(sid){
    $('.address-error').hide().find('.alert-danger').html('');
    var Phone = $('#shipping_phone').val().trim();
    var Email = $('#shipping_email').val().trim();
    var Address1 = $('#shipping_address').val().trim();
    var Apt = $('#shipping_apt').val().trim();
    var City = $('#shipping_city option:selected').text();
    var State = $('#shipping_state option:selected').text();
    var Zip = $('#shipping_zip').val().trim();
    var Firstname = $('#shipping_firstname').val().trim();
    var Lastname = $('#shipping_lastname').val().trim();
    var FullAddress = Address1+', '+City+', '+State+' '+Zip;
    var ErrorHTML = "";
    var error=0;
    if(Firstname==''){
      ErrorHTML +="<li>"+FName_msg+"</li>"
      error++;
    }if(Lastname==''){
      ErrorHTML +="<li>"+LName_msg+"</li>"
      error++;
    }if(Phone==''){
      ErrorHTML +="<li>"+Phone_msg+"</li>"
      error++;
    }if(Email==''){
      ErrorHTML +="<li>"+Email_msg+"</li>"
      error++;
    }if(Address1==''){
      ErrorHTML +="<li>"+Address_msg+"</li>"
      error++;
    }if(Zip==''){
      ErrorHTML +="<li>"+Zip_msg+"</li>"
      error++;
    }if(City==''){
      ErrorHTML +="<li>"+City_msg+"</li>"
      error++;
    }if(State==''){
      ErrorHTML +="<li>"+State_msg+"</li>"
      error++;
    }

    var atpos = Email.indexOf("@");
    var dotpos = Email.lastIndexOf(".");
    if(Email!=''){
        if (atpos<1 || dotpos<atpos+2 || dotpos+2>=Email.length) {
            ErrorHTML +="<li>The Email must be a valid email address.</li>"
            error++;
        }
    }
    if(error>0){
      $('.address-error').show();
      $('.address-error').find('.alert-danger').append('<ul></ul>')
      $('.address-error').find('ul').html(ErrorHTML)
      return false;
    }

    new google.maps.Geocoder().geocode({'address':FullAddress}, function (results, status) {
        if (status == google.maps.GeocoderStatus.OK) {
            var lat = results[0].geometry.location.lat();
            var long = results[0].geometry.location.lng();
            $('#add-reset-btn').show();
            calculateShipping(sid,lat,long);
            $('.address-error').hide();
            if(Apt != ''){
                $('#customer_details_section').find('#customer_apt').html(Apt+', ');
            }
            $('#customer_details_section').find('#customer_address1').html(Address1);
            $('#customer_details_section').find('#customer_address2').html(City+', '+State+' '+Zip);
            $('#reset-instruction').show();
        } else {
            $('.address-error').show().find('.alert-danger').html('Invalid address');
            $('#shipping-details').hide();
            $('#payment-details').hide();
        }
    });
}
function couponKeyPress(event){
    var key = event.which;
    if(key == 13){
        $('#applyCouponBtn').trigger('click');
        event.preventDefault();
    }
}
function ApplyCoupon(sid){
    var coupon = $('#coupon_code').val();
    var zip = $('#shipping_zip').val();
    var subTotal = $('input[name="sub_total"]').val();
    var grandTotal = $('input[name="grand_total"]').val();
    $.ajax({
        type: "POST",
        url: APP_URL+'/cart/apply-coupon',
        data: {'sid':sid,'coupon':coupon,'zip':zip,'sub_total':subTotal,'grand_total':grandTotal},
        dataType: "json",
        success: function(res) {
            console.log(res);
            if(res.success){
                $('#DiscountRow').css('display','contents');
                $('#couponText').html(res.coupon);
                $('#DiscountAmt').html(res.discount);
                $('input[name="grand_total"]').val()
            }else{
                $('#no_coupon_msg').html(res.message).show();
            }
        },
        error: function () {
            $("body").loader('hide');
        }
    })
}
function removeCoupon(){
    $('#coupon_code').removeAttr('readonly');
    var coupon = $('#coupon_code').val('');
    $('#add-okay-btn').trigger('click');
}
function ResetCookie(){
    $.ajax({
        type: "POST",
        url: APP_URL+'/delete-cookies',
        data: '',
        dataType: "json",
        success: function(res) {
            //window.location.href=APP_URL;
            if(CurrentURL=='frontend.cart-checkout'){
                window.location.href=APP_URL;
            }else{
                GetLocation();    
            }
        }
    });
}
function ResetAddress(){
    $('#add-reset-btn').hide();
    $('#shipping_address,#shipping_zip, #shipping_apt, #coupon_code').removeAttr('readonly');
    $('#order_now_btn').attr('disabled','disabled');
    $('select#shipping_state,select#shipping_city').select2({disabled:false});
    $('#shipping-details').hide();
    $('#payment-details').hide();
    $('#reset-instruction').hide();
    $('.address-error').hide();
}
function calculateShipping(sid,lat,long){
    var tBody = $(".tbody-store-list")
    var subHolder = $(".tbody-store-list").find('#subTotal');
    var shippingHolder = $(".tbody-store-list").find('#shippingCharge');
    var grandHolder = $(".tbody-store-list").find('#grandTotal');
    var milesHolder = $(".tbody-store-list").find('#Ship_Miles');
    var FtaxHolder = $(".tbody-store-list").find('#f_taxPer');
    var PtaxHolder = $(".tbody-store-list").find('#p_taxPer');
    var RecycleFee = $(".tbody-store-list").find('#recycle_fee');

    var Ftotal = $(".tbody-store-list").find('#f_taxTotal');
    var Ptotal = $(".tbody-store-list").find('#p_taxTotal');

    var TaxDesc = $(".tbody-store-list").find('#tax_description');
    var taxHolder = $(".tbody-store-list").find('#taxTotal');
    var coupon = $('#coupon_code').val();
    var zip = $('#shipping_zip').val();
    $.ajax({
        type: "POST",
        url: APP_URL+'/cart/get-shipping',
        data: {'sid':sid,'lat':lat,'long':long,'coupon':coupon,'zip':zip},
        dataType: "json",
        success: function(res) {
            if(res.toDistance<=3){
                subHolder.html(res.actualSubTotal);
                shippingHolder.html(res.shippingCharge);
                grandHolder.html(res.grandTotal);
                taxHolder.html(res.tax);
                FtaxHolder.html(res.ftax_percantage);
                PtaxHolder.html(res.ptax_percantage);

                Ftotal.html(res.ftax);
                Ptotal.html(res.ptax);

                TaxDesc.html(res.tax_desc);
                RecycleFee.html(res.recycle_fee);
                milesHolder.html('('+res.toDistance+' mi)');
                $('input[name="sub_total"]').val(res.actualSubTotal);
                $('input[name="shipping_charge"]').val(res.shippingCharge);
                $('input[name="grand_total"]').val(res.grandTotal);
                $('input[name="tax_total"]').val(res.tax);
                $('input[name="ftax_total"]').val(res.ftax);
                $('input[name="ptax_total"]').val(res.ptax);
                $('input[name="recycle_fee"]').val(res.recycle_fee);

                $('#shipping-details').show();
                $('#payment-details').show();
                setTimeout(function(){
                    $('#shipping_address,#shipping_zip, #shipping_apt, #coupon_code').attr('readonly','readonly');
                    $('#order_now_btn').removeAttr('disabled');
                    $('select#shipping_state,select#shipping_city').select2({disabled:true});
                },100);
                $('html, body').animate({
                    'scrollTop' : $("#payment-details").parents('.panel').position().top-40
                });

                $('input[name="coupon_applied"]').val(res.coupon_applied);
                if(res.coupon_applied){
                    $('#DiscountRow').css('display','contents');
                    $('#couponText').html(res.coupon);
                    $('#DiscountAmt').html(res.discount);
                    $('input[name="discount"]').val(res.discount);
                    $('#no_coupon_msg').html('').hide();
                }else{
                    $('#DiscountRow').css('display','none');
                    $('#couponText').html('');
                    $('#DiscountAmt').html('');
                    $('input[name="discount"]').val('0');
                    $('#no_coupon_msg').html(res.coupon_error).show();
                }
            }else{
                ErrorHTML ="<li>"+AddMileMsg+"</li>"
                $('.address-error').show();
                $('.address-error').find('.alert-danger').append('<ul></ul>')
                $('.address-error').find('ul').html(ErrorHTML)
                $('#add-reset-btn').show();
                setTimeout(function(){
                    $('#shipping_address,#shipping_zip, #shipping_apt, #coupon_code').attr('readonly','readonly');
                    $('#order_now_btn').removeAttr('disabled');
                    $('select#shipping_state,select#shipping_city').select2({disabled:true});
                },100);
                return false;
            }
        }
    });
}
function ShowAllRatings(element,sid){
    $("body").loader('show');
    $.ajax({
        type: "POST",
        url: APP_URL + '/cart/'+sid+'/get_ratings',
        data: "",
        dataType: "json",
        success: function (response) {
            $("body").loader('hide');
            $('#StoreRatingsModal').remove();
            $('body').append(response.HTML);
            InitRate();
            $('#StoreRatingsModal').modal('show');
        },
        error: function () {
            $("body").loader('hide');
        }
    });
}

/*home page*/
var ZipSection = $('.zip-section');
var ZipInfo = ZipSection.find('.help-block');
var lat = "";
var long = "";
$(document).ready(function () {
    if(CurrentURL=='customer.dashboard' || CurrentURL=='frontend.home'){
        if(zip==''){
            GetLocation();
        }else{
            //openZipModal('saved',zip);
        }
        if(CartCookie==''){
            window.location.reload();
        }
    }
});

function handleLocationError(browserHasGeolocation) {
    alert(browserHasGeolocation ? 'Error: The Geolocation service failed.' : 'Error: Your browser doesn\'t support geolocation.');
}
function GetZip() {
    
    var point = new google.maps.LatLng(lat,long);
    var countryStatus = true;
    var LoopEntryStatus = true;
    var zip_code = "";
    var country_name = "";
    
    new google.maps.Geocoder().geocode({'latLng': point}, function (res, status) {
        
        if(status == 'OK') {
        
            var arrAddress = res[0].address_components;
            
            $.each(arrAddress, function (i, address_component) {
                
                if(LoopEntryStatus)
                {
                    if(address_component.types[0] == "country" && countryStatus){
                        if(address_component.short_name=='CA' || address_component.short_name=='US') {
                            country_name = address_component.short_name;
                            countryStatus = false;
                            
                        }else{
                            openZipModal('country');
                        }
                    }
                    else
                    {
                        if (address_component.types[0] == "postal_code") {
    
                            LoopEntryStatus = false;
                            if(country_name=='CA' || country_name=='US') {
                                zip_code = address_component.long_name;
                            } else { }
                        } else { }
                    }
                }
                
                
                if(zip_code!='' && (country_name=='CA' || country_name=='US'))
                {
                    openZipModal('success',zip_code);
                    createCookies();
                }
                else
                { openZipModal('country'); }
                
            });
        }
        else
        {
            openZipModal('country');
        }
        
    });
    
    
    
    /*var point = new google.maps.LatLng(lat,long);
    
    new google.maps.Geocoder().geocode({'latLng': point}, function (res, status) {
        var arrAddress = res[0].address_components;
        //console.log(arrAddress);
        $.each(arrAddress, function (i, address_component) {
            
            //alert(JSON.stringify(address_component));
            if(address_component.types[0] == "country"){
                //alert(address_component.short_name);
                if(address_component.short_name=='CA' || address_component.short_name=='US') {
                    //console.log(address_component);
                    if (address_component.types[0] == "postal_code"){
                        
                        zip_code = address_component.long_name;
                        openZipModal('success',zip_code);
                        createCookies();
                    }else{
                        zip_code = arrAddress[arrAddress.length - 1].long_name;
                        openZipModal('success',zip_code);
                        //createCookies();
                    }
                }else{
                    openZipModal('country');
                }
            }
        });
    });*/
}
function createCookies(type){
    if (typeof(type)==='undefined') type = 'old';
    $.ajax({
        type: "POST",
        url: APP_URL+'/create-cookies',
        data: 'zip='+zip+'&lat='+lat+'&long='+long,
        dataType: "json",
        success: function(res) {
            if(type=='new'){
                //$(ZipInfo).html('Saved New Details');
                setTimeout(function(){
                    $('#zipModal').modal('hide');
                },500)
                var zipCode = $(ZipSection).find('#zip').val()    
                $('.display_zip_code').html(zipCode);

                if(CurrentURL=='get.frontend.store-selection'){
                    $("#storemsg").html('');
                    $("#storemsg").fadeOut();
                    
                    $(".tbody-store-list").html('');
                    $('#offset').val('0')
                    reloadStore(0);
                }
            }
        }
    });
}
function openZipModal(type,zip_code){
    if (typeof(zip_code)==='undefined') zip_code = '';
    if(type=='success'){
        $(ZipSection).find('#zip').val(zip_code);
        if(CurrentURL=='get.frontend.store-selection'){
            $(ZipInfo).html('<i class="fa fa-map-marker"></i> '+ZipSuccessStore);
        }else{
            $(ZipInfo).html('<i class="fa fa-map-marker"></i> '+ZipWelcome);
        }
        $('#zipModal').modal('show');
        zip = zip_code;
    }else if(type=='fail'){
        $(ZipSection).find('#zip').val('');
        $(ZipInfo).html('<i class="fa fa-map-marker"></i> '+ZipFail);
        $('#zipModal').modal('show');
    }else if(type=='country'){
        $(ZipSection).find('#zip').val('');
        $(ZipInfo).html('<i class="fa fa-map-marker"></i> '+ZipLimited);
        $('#zipModal').modal('show');
    }else if(type=='saved'){
        $(ZipSection).find('#zip').val(zip_code);
        $(ZipInfo).html('<i class="fa fa-map-marker"></i> '+OldZip);
        $('#zipModal').modal('show');
    }
}
function saveNewZip(){
    var zipCode = $(ZipSection).find('#zip').val()
    var OriginalZip = $(ZipSection).find('#zip').val()
    var NotFound = false;
    zipCode = zipCode.replace(' ', '');
        new google.maps.Geocoder().geocode( { 'address': zipCode}, function(results, status) {
            if(results.length != 0){
                var arrAddress = results[0].address_components;
                $.each(arrAddress, function (i, address_component) {
                    if(address_component.types[0] == "country"){
                        if(address_component.short_name=='CA' || address_component.short_name=='US'){
                            lat = results[0].geometry.location.lat();
                            long = results[0].geometry.location.lng();
                            zip = zipCode;
                            createCookies('new');
                        }else{
                            openZipModal('country');
                        }
                    }
                });
            }else{
                NotFound = true;
                //$(ZipInfo).html(InvalidZip);
            }
        });
        setTimeout(function(){
            if(NotFound){
                new google.maps.Geocoder().geocode( { 'address': OriginalZip}, function(results, status) {
                    if(results.length != 0){
                        var arrAddress = results[0].address_components;
                        $.each(arrAddress, function (i, address_component) {
                            if(address_component.types[0] == "country"){
                                if(address_component.short_name=='CA' || address_component.short_name=='US'){
                                    lat = results[0].geometry.location.lat();
                                    long = results[0].geometry.location.lng();
                                    zip = OriginalZip;
                                    createCookies('new');
                                }else{
                                    openZipModal('country');
                                }
                            }
                        });
                    }else{
                        $(ZipInfo).html(InvalidZip);
                        NotFound = true;
                    }
                });
            }
        }, 1000);
}
function EnterQty(cid,element,e){
    var qty = $(element).val();
    var key = e.which;
    if (key == 13) {
        $("body").loader('show');
        $.ajax({
            type: "PUT",
            url: APP_URL + '/cart/update',
            data: "cid=" + cid+"&opt=value&qty_value="+qty,
            dataType: "json",
            success: function (response) {
                if (response.result == "success"){
                    reloadCartItem();
                }
            },
            error: function (jqXHR, exception) {
              var Response = jqXHR.responseText,              
              Response = $.parseJSON(Response);
              DisplayErrorMessages(Response,"",'toaster');
              $("body").loader('hide');
            }
        });
    }
}
function GetLocation(){
    /*$.ajax({
        type: "POST",
        url: 'https://www.googleapis.com/geolocation/v1/geolocate?key=AIzaSyA1GpIUeTcjRKCrf3asCqS-ZwrS_BU7vRY',
        data: '',
        dataType: "json",
        success: function (response) {
            console.log(response);
            lat = response.location.lat
            long = response.location.lng
            GetZip();
        },
        error: function (jqXHR, textStatus, errorThrown) {
            alert(jqXHR.responseJSON.error.message)
            if(jqXHR.responseJSON.error !== undefined){
                openZipModal('fail');
            }
        }
    });*/
     

    if (navigator.geolocation) {
        
     
        navigator.geolocation.getCurrentPosition(function(position) {
              //console.log(position);  
              lat = position.coords.latitude;
              long = position.coords.longitude;
              //console.log(lat)
              //console.log(long)
            GetZip();
        }, function(error) {
            if(error.code=='1') { 
               console.log(error);
              /*$.toast({
                 heading: '',
                 text: 'User denied Geolocation',
                 icon: '',
                 loader: true,        // Change it to false to disable loader
                 loaderBg: '#9EC600'  // To change the background
              });*/
        
            toastr.options = {
               "closeButton": true,
               "debug": false,
               "positionClass": "toast-top-right",
               "onclick": null,
               "showDuration": "300",
               "hideDuration": "1000",
               "timeOut": "3000",
               "extendedTimeOut": "1000",
               "showEasing": "swing",
               "hideEasing": "linear",
               "showMethod": "fadeIn",
               "hideMethod": "fadeOut"
            };
            toastr.error(error.message);

            }
            else if(error.code=='3') {
               GetLocation();
            }
            else { /*handleLocationError(true);*/ }
        },{timeout:2000})
    }else{
        
        handleLocationError(false);
    }
}