/**
 * Captcha image handling
 */
$(function(){
    var refrechCount = 1;
    $('#captchaChange').click(function(){
        var newSrc = mvcskel_root+'Signup/Captcha/c/'+refrechCount++;
        $('#captchaImage').attr('src', newSrc);
    });
});
