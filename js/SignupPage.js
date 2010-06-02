/**
 * Captcha image handling
 */
$(function(){
    $('#captchaChange').
    each(function(){
        this.refrechCount = 1;
    }).click(function(){
        var newSrc = mvcskel_root+'Signup/Captcha/c/'+this.refrechCount++;
        $('#captchaImage').attr('src', newSrc);
    });
});
