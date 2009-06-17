/**
 * Singup form AJAX functions.
 */
var SignupPage = Class.create({
    refrechCount: 1,
    initialize: function () {
        $('captchaChange').observe('click', this.changeCaptcha.bindAsEventListener(this));
    },
    changeCaptcha: function() {
        var image = $('captchaImage');
        image.writeAttribute('src', application_root+'Signup/Captcha/c/'+this.refrechCount++);
    }
});


