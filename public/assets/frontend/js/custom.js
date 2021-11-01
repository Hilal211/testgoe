"use strict";

//  i Check plugin
$('.i-check, .i-radio').iCheck({
    checkboxClass: 'i-check',
    radioClass: 'i-radio'
});

// price slider
/*$("#price-slider").ionRangeSlider({
    min: 130,
    max: 575,
    type: 'double',
    prefix: "$",
    prettify: false,
    hasGrid: false
});*/
/*
$('#jqzoom').jqzoom({
    zoomType: 'standard',
    lens: true,
    preloadImages: false,
    alwaysOn: false,
    zoomWidth: 460,
    zoomHeight: 460,
    // xOffset:390,
    yOffset: 0,
    position: 'left'
});*/

/*
$('.form-group-cc-number input').payment('formatCardNumber');
$('.form-group-cc-date input').payment('formatCardExpiry');
$('.form-group-cc-cvc input').payment('formatCardCVC');*/

// Register account on payment
$('#create-account-checkbox').on('ifChecked', function() {
    $('#create-account').removeClass('hide');
});

$('#create-account-checkbox').on('ifUnchecked', function() {
    $('#create-account').addClass('hide');
});

$('#shipping-address-checkbox').on('ifChecked', function() {
    $('#shipping-address').removeClass('hide');
});

$('#shipping-address-checkbox').on('ifUnchecked', function() {
    $('#shipping-address').addClass('hide');
});

$('.owl-carousel').each(function(){
    $(this).owlCarousel();
});


$(".search-btn").on('click', function() {
    $('.search-input').trigger("focus");
    $('.search-input').trigger("keydown");
});

// Lighbox gallery
$('#popup-gallery').each(function() {
    $(this).magnificPopup({
        delegate: 'a.popup-gallery-image',
        type: 'image',
        gallery: {
            enabled: true
        }
    });
});

// Lighbox image
$('.popup-image').magnificPopup({
    type: 'image'
});

// Lighbox text
$('.popup-text').magnificPopup({
    removalDelay: 500,
    closeBtnInside: true,
    callbacks: {
        beforeOpen: function() {
            this.st.mainClass = this.st.el.attr('data-effect');
        }
    },
    midClick: true
});



$(".product-page-qty-plus").on('click', function() {
    var currentVal = parseInt($(this).prev(".product-page-qty-input").val(), 10);

    if (!currentVal || currentVal == "" || currentVal == "NaN") currentVal = 0;

    $(this).prev(".product-page-qty-input").val(currentVal + 1);
});

$(".product-page-qty-minus").on('click', function() {
    var currentVal = parseInt($(this).next(".product-page-qty-input").val(), 10);
    if (currentVal == "NaN") currentVal = 1;
    if (currentVal > 1) {
        $(this).next(".product-page-qty-input").val(currentVal - 1);
    }
});

$(document).ready(function(){
    if($(window).width() <= 980){
        $("#ProductMenu li").click(function() {
            var SubMenu = $(this).find('.dropdown-menu-category-section');
            if($(SubMenu).is(":visible")){
                //$()
                $(SubMenu).hide();
            }else{
                $(SubMenu).show();
            }
        })

    }
    if($(window).width() <= 768){
        $("#main-nav-collapse").find('.navbar-nav li').hover(function() {
            var SubMenu = $(this).find('.dropdown-menu');
            if($(SubMenu).is(":visible")){
                //console.log('shown')
                $(SubMenu).hide();
            }else{
                //console.log('hidden')
                $(SubMenu).show();
            }
        })
    }
})
function Subscribe(Form){
    console.log(Form);
    var Data = $(Form).serialize();
    console.log(Data);
    $.ajax({
        type: "POST",
        url: $(Form).attr('action'),
        data: Data,
        dataType: "json",
        success: function(res) {
            console.log(res);
            toastr.success(res.message, 'Success!');
            window.location.reload();
        },
        error: function (jqXHR, exception) {
            $("#form-container").loader('hide');
            var Response = jqXHR.responseText,
            ErrorBlock = $(Form).prev('.form-errors'),
            Response = $.parseJSON(Response);
            DisplayErrorMessages(Response,ErrorBlock,'toaster');
        }
    });
    return false;
}