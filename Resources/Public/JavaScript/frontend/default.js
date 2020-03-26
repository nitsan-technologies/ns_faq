$(document).ready(function () {
    if ($('.ns-faq-expand-collapse-trigger').length) {
        $('.ns-faq-expand-collapse-trigger').on('click', function () {
            nsFaqExpandAll($(this));
        });
    }
    onFocusValidation();

    if ($('.tx_nsfaq .approvedmessage').length) {
        $('html, body').stop().animate({
            scrollTop: ($('.tx_nsfaq .approvedmessage').offset().top)
        }, 2000);
        setTimeout(function() {
            $('.tx_nsfaq .approvedmessage').fadeOut("slow");
        }, 9000);
    }

    if ($('.tx_nsfaq .approvefaq').length) {
        showpopup();
    }
    $(".tx_nsfaq .approvefaq #cancel_button").click(function(){
        hidepopup();
    });
    $(".tx_nsfaq .approvefaq #close_button").click(function(){
        hidepopup();
    });

    $('.searchfaqtext').keyup(delay(function (e) {
        getList($(this));
    }, 800));

    var categoryId = getUrlParameter('cat');
    var faqId = getUrlParameter('faq');
    if((categoryId && categoryId != 'undefined') && (faqId && faqId !='undefined')){
        $('.ns-faq-list').find('#'+categoryId).addClass('ns-faq-category-active');
        $('.ns-faq-list').find('#'+categoryId).children('.ns-faq-category-content').show();
        $('.ns-faq-list').find('#'+categoryId).find('.ns-faq-wrap').addClass('ns-faq-active');
        $('.ns-faq-list').find('#'+categoryId).find('.ns-faq-body').addClass('animated').show();
    }
    if(faqId && faqId !='undefined'){
        $('.ns-faq-list').find('#'+faqId).addClass('ns-faq-active');
        $('.ns-faq-list').find('#'+faqId).find('.ns-faq-body').addClass('animated').show();
    }

    if ($('.displayAll').length) {
        $('.ns-faq-list').find('.ns-faq-wrap').addClass('ns-faq-active');
        $('.ns-faq-list').find('.ns-faq-body').addClass('animated').show();
        if ($('.ns-faq-expand-collapse-trigger').length) {
            $('.ns-faq-expand-collapse-trigger').children('em').toggleClass('fa-angle-double-down fa-angle-double-up');
            $('.ns-faq-expand-collapse-trigger').children('span').text('Collapse All FAQs');
        }
    }
    if ($('.ns-faq-toggle').length) {
        $('.ns-faq-title').on('click', function () {
            nsFaqHideShow($(this));
        });
    }
    if ($('.ns-faq-category-with-toggle .ns-faq-category-title').length) {
        $('.ns-faq-category-with-toggle .ns-faq-category-title').on('click', function () {
            nsFaqHideShowCategory($(this));
        });
    }
    if ($('.ns-faq-scroll-top-wrap').length) {
        $('.ns-faq-scroll-top').on('click', function () {
            var $ScrollVal = $('.ns-faq-list').offset().top;
            $("html, body").animate({ scrollTop: $ScrollVal }, 1000);
            return false;
        });
    }

    $(document).on('click','.like',function () {
        if($('.dislike').hasClass('active')){
            $('.dislike').removeClass('active');
            count = $('.dislike').attr('data-count');
            $('.dislike').children('span').html(count);
            $('.dislike').attr('data-count',count);

        }
        $(this).toggleClass('active');
        faqId = $(this).data('faqid');
        url = $(this).data('uri');
        countforlikes = $(this).attr('data-count');
        countfordislikes = $('.dislike').attr('data-count');

        if($(this).hasClass('active')){
            countforlikes =  parseInt(countforlikes) + 1;
        }else{
            countforlikes = parseInt(countforlikes) - 1;
        }
        $(this).attr('data-count',countforlikes);
        $(this).children('span').html(countforlikes);
        $.ajax({
            url:url,
            method:'post',
            data:{type:'likes',faqid:faqId,countforlikes:countforlikes,countfordislikes:countfordislikes},
            success:function(r){

            }
        });
    });
    $(document).on('click','.dislike',function () {
        if($('.like').hasClass('active')){
            $('.like').removeClass('active');
            count = $('.like').attr('data-count');
            $('.like').children('span').html(count);
            $('.like').attr('data-count',count);
        }
        $(this).toggleClass('active');
        faqId = $(this).data('faqid');
        url = $(this).data('uri');
        countforlikes = $('.like').attr('data-count');
        countfordislikes = $(this).attr('data-count');

        if($(this).hasClass('active')){
            countfordislikes =  parseInt(countfordislikes) + 1;
        }else{
            countfordislikes = parseInt(countfordislikes) - 1;
        }
        $(this).attr('data-count',countfordislikes);
        $(this).children('span').html(countfordislikes);
        $.ajax({
            url:url,
            method:'post',
            data:{type:'dislikes',faqid:faqId,countforlikes:countforlikes,countfordislikes:countfordislikes},
            success:function(r){
              
            }
        })
    });

    //Submit FAQ
    $(document).on('submit', '.submit-faq .faq-submit-form', function(event) {
        if (!validateField($(this))) {
            event.preventDefault();
        }
    });

});
var getUrlParameter = function getUrlParameter(sParam) {
    var sPageURL = window.location.search.substring(1),
        sURLVariables = sPageURL.split('&'),
        sParameterName,
        i;

    for (i = 0; i < sURLVariables.length; i++) {
        sParameterName = sURLVariables[i].split('=');

        if (sParameterName[0] === sParam) {
            return sParameterName[1] === undefined ? true : decodeURIComponent(sParameterName[1]);
        }
    }
};

function imNotARobot() {
    if (grecaptcha.getResponse() == "") {
        $(".submit-faq #googlecaptcha").closest('.ns-form-group').addClass('has-error');
        $(".submit-faq #googlecaptcha_error").show();
        return false;
    } else {
        $(".submit-faq #googlecaptcha").closest('.ns-form-group').removeClass('has-error');
        $(".submit-faq #googlecaptcha_error").hide();
    }
}
function delay(callback, ms) {
    var timer = 0;
    return function() {
        var context = this, args = arguments;
        clearTimeout(timer);
        timer = setTimeout(function () {
            callback.apply(context, args);
        }, ms || 0);
    };
}
function refreshrecaptcha() {
    var googlecaptcha = document.getElementById("googlecaptcha");
    if (googlecaptcha) {
        setTimeout(function () {
            $('.g-recaptcha').html('');
            sitekey = $('.googlecaptchakey').data('googlesitekey');
            grecaptcha.render("googlecaptcha", {
                "sitekey": sitekey
            });
            grecaptcha.reset();
        }, 200);
    }
}

function nsFaqExpandAll($this) {
    $this.toggleClass('open');
    $this.children('em').toggleClass('fa-angle-double-down fa-angle-double-up');
    if ($this.children('span').text() == 'Expand All FAQs') {
        $this.children('span').text('Collapse All FAQs');
        $this.parents('.ns-faq-list').find('.ns-faq-wrap').addClass('ns-faq-active');
        $this.parents('.ns-faq-list').find('.ns-faq-body').addClass('animated').show();

        //for category
        if($this.parents('.ns-faq-list').find('.ns-faq-category-with-toggle')){
            $this.parents('.ns-faq-list').find('.ns-faq-category').addClass('ns-faq-category-active');
            $this.parents('.ns-faq-list').find('.ns-faq-category-content').show();
        }

    } else {
        $this.children('span').text('Expand All FAQs');
        $this.parents('.ns-faq-list').find('.ns-faq-wrap').removeClass('ns-faq-active');
        $this.parents('.ns-faq-list').find('.ns-faq-body').hide();
        if($this.parents('.ns-faq-list').find('.ns-faq-category-with-toggle').length > 0) {
            $this.parents('.ns-faq-list').find('.ns-faq-category').removeClass('ns-faq-category-active');
            $this.parents('.ns-faq-list').find('.ns-faq-category-content').hide();
        }
    }
}
function nsFaqHideShow($this) {
    if($this.parents('.ns-faq-blocks').hasClass('ns-faq-accordion')){
        $this.parent('.ns-faq-wrap').siblings('.ns-faq-wrap').removeClass('ns-faq-active').find('.ns-faq-body').hide();
    }
    $this.parent('.ns-faq-wrap').toggleClass('ns-faq-active').find('.ns-faq-body').addClass('animated').toggle();
}
function nsFaqHideShowCategory($this) {
    $this.parent('.ns-faq-category-with-toggle').toggleClass('ns-faq-category-active').find('.ns-faq-category-content').toggle();
}
function getList(item){
    var filter, searchfor, count = 0;
    filter = $(item).val();
    searchfor = $(item).data('searchfor');
    $("#"+searchfor).find('.ns-faq-wrap').each(function(){
        if ($(this).text().search(new RegExp(filter, "i")) < 0) {
            $(this).slideUp();
            if (!$(this).is(':visible')) {
                $(this).parents('.ns-faq-category').slideUp();
            }
        } else {
            $(this).slideDown();
            $(this).parents('.ns-faq-category').slideDown();
            count++;
        }
    });
}

// Referech Captcha
function refreshFaqCaptcha() {
    var img = document.images['captchaimg'];
    img.src = img.src.substring(0, img.src.lastIndexOf("?")) + "?rand=" + Math.random() * 1000;
}

// Custom validation for onfocus
function onFocusValidation() {

    $(".submit-faq #submittername").focusout(function() {
        elementObj = $(this);
        if (elementObj.val() != '') {
            if (!validateName($('.submit-faq #submittername').val())) {
                $(".submit-faq #name_error_msg").show();
                $(".submit-faq #name_error").hide();
                $(".submit-faq #submittername").parent().addClass('has-error');
                var flag = 0;
            } else {
                elementObj.parent().removeClass('has-error');
                $(".submit-faq #name_error_msg").hide();
                $(".submit-faq #name_error").hide();
            }
        } else {
            $(".submit-faq #submittername").parent().addClass('has-error');
            $(".submit-faq #name_error").show();
            $(".submit-faq #name_error_msg").hide();
        }
    });

    $(".submit-faq #submitteremail").focusout(function() {
        elementObj = $(this);
        if (elementObj.val() != '') {
            if (!validateEmail($('.submit-faq #submitteremail').val())) {
                $(".submit-faq #email_error_msg").show();
                $(".submit-faq #email_error").hide();
                $(".submit-faq #submitteremail").parent().addClass('has-error');
                var flag = 0;
            } else {
                elementObj.parent().removeClass('has-error');
                $(".submit-faq #email_error_msg").hide();
                $(".submit-faq #email_error").hide();
            }
        } else {
            $(".submit-faq #submitteremail").parent().addClass('has-error');
            $(".submit-faq #email_error").show();
            $(".submit-faq #email_error_msg").hide();
        }
    });

    $(".submit-faq #answer").focusout(function() {
        elementObj = $(this);
        if (elementObj.val() != '') {
            var length = $.trim($(".submit-faq #answer").val()).length;
            if (length == 0) {
                $(".submit-faq #answer_error").show();
                $(".submit-faq #answer").parent().addClass('has-error');
                var flag = 0;
            } else {
                $(".submit-faq #answer").parent().removeClass('has-error'); // remove it
                $(".submit-faq #answer_error").hide();
            }

        } else {
            $(".submit-faq #answer").parent().addClass('has-error');
            $(".submit-faq #answer_error").show();
        }
    });

    $(".submit-faq #captcha").focusout(function() {
        elementObj = $(this);
        if (elementObj.val() != '') {
            var length = $.trim($(".submit-faq #captcha").val()).length;
            if (length == 0) {
                $(".submit-faq #captcha_error").show();
                $(".submit-faq #captcha").parent().addClass('has-error');
                var flag = 0;
            } else {
                $(".submit-faq #captcha").parent().removeClass('has-error'); // remove it
                $(".submit-faq #captcha_error").hide();
                $(".submit-faq #captcha_valid_error").hide();
            }
        } else {
            $(".submit-faq #captcha").parent().addClass('has-error');
            $(".submit-faq #captcha_error").show();
        }
    });
}

// Custom Validation
function validateField() {
    var flag = 1;
    var elementObj;
    var captcha = document.getElementById("captcha");
    var googlecaptcha = document.getElementById("googlecaptcha");

    if (!$('.submit-faq #submittername').val()) {
        $(".submit-faq #submittername").parent().addClass('has-error');
        $('.submit-faq #name_error').show();
        var flag = 0;
    } else {

        if (!validateName($('.submit-faq #submittername').val())) {
            $(".submit-faq #name_error_msg").show();
            $(".submit-faq #name_error").hide();
            $(".submit-faq #submittername").parent().addClass('has-error');
            var flag = 0;
        } else {
            $(".submit-faq #submittername").parent().removeClass('has-error');
            $(".submit-faq #name_error_msg").hide();
            $(".submit-faq #name_error").hide();
        }
    }

    if (!$('.submit-faq #submitteremail').val()) {
        $(".submit-faq #submitteremail").parent().addClass('has-error');
        $(".submit-faq #email_error").show();
        $(".submit-faq #email_error_msg").hide();
        var flag = 0;
    } else {
        console.log(validateEmail($('.submit-faq #submitteremail').val()));
        if (!validateEmail($('.submit-faq #submitteremail').val())) {
            $(".submit-faq #email_error_msg").show();
            $(".submit-faq #email_error").hide();
            $(".submit-faq #submitteremail").parent().addClass('has-error');
            var flag = 0;
        } else {
            $(".submit-faq #submitteremail").parent().removeClass('has-error');
        }
    }

    if (!$('.submit-faq #answer').val()) {
        $(".submit-faq #answer").parent().addClass('has-error');
        $(".submit-faq #answer_error").show();
        var flag = 0;
    } else {
        var length = $.trim($(".submit-faq #answer").val()).length;
        if (length == 0) {
            $(".submit-faq #answer_error").show();
            $(".submit-faq #answer").parent().addClass('has-error');
            var flag = 0;
        } else {
            $(".submit-faq #answer").parent().removeClass('has-error'); // remove it
        }
    }

    if(captcha){
        if (!$('.submit-faq #captcha').val()) {
            $(".submit-faq #captcha").parent().addClass('has-error');
            $(".submit-faq #captcha_error").show();
            $(".submit-faq #captcha_valid_error").hide();
            var flag = 0;
        } else {
            if (validateCaptcha($('.submit-faq #captcha').val()) == 'true') {
                $(".submit-faq #captcha").parent().removeClass('has-error'); // remove it
            } else {
                $(".submit-faq #captcha_valid_error").show();
                $(".submit-faq #captcha_error").hide();
                $(".submit-faq #captcha").parent().addClass('has-error');
                var flag = 0;
            }
        }
    }

    if (googlecaptcha) {
        if (grecaptcha.getResponse() == "") {
            $(".submit-faq #googlecaptcha").closest('.ns-form-group').addClass('has-error');
            $(".submit-faq #googlecaptcha_error").show();
            var flag = 0;
        } else {
            $(".submit-faq #googlecaptcha").closest('.ns-form-group').removeClass('has-error');
            $(".submit-faq #googlecaptcha_error").hide();
        }
    }

    if ($('.error-msg-red').length) {
        var flag = 0;
    }

    if (flag == 1) {
        return true;
    }
    return false;
}

// Validate Captcha field using ajax request
function validateCaptcha(captcha) {

    var dataString = 'captcha=' + captcha;
    var url = $('.verification').val();
    var response = $.ajax({
        type: 'POST',
        async: false,
        url: url,
        data: dataString,
        success: function(response) {

        },
        error: function() {
            alert('Captcha not Mathched');
        }
    });
    return response.responseText;
}

// Validate Email field
function validateEmail($email) {
    var emailReg = /^([\w-\.]+@([\w-]+\.)+[\w-]{2,4})?$/;
    return emailReg.test($email);
}

// Validate Name field
function validateName($name) {
    console.log($name);
    var nameReg = /[^0-9]/g;
    return nameReg.test($name);
}

// Referech Captcha
function refreshCaptcha() {
    var img = document.images['captchaimg'];
    img.src = img.src.substring(0, img.src.lastIndexOf("?")) + "?rand=" + Math.random() * 1000;
}

// Show popup div
function showpopup()
{
    $(".tx_nsfaq .approvefaq #popup_box").fadeToggle();
    $(".tx_nsfaq .approvefaq #popup_box").css({"visibility":"visible","display":"block"});
}

// Hide popup div
function hidepopup()
{
    $(".tx_nsfaq .approvefaq #popup_box").fadeToggle();
    $(".tx_nsfaq .approvefaq #popup_box").css({"visibility":"hidden","display":"none"});
}

