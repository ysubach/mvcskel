/**
 * Some useful utils for ajax form handling.
 */
jQuery.fn.fuinit = function() {
    this.submit(function(){
        var form = $(this);
        FormUtils.handleRequest(form);
        jQuery.getJSON($(this).attr('action'), $(this).serialize(), function(data){
            FormUtils.handleResponse(form, data);
        });
        return false;
    }).find('button[type=submit]').attr('disabled', false);

/*
    // check input file, use AIM in that case
    if (form.select('input[type="file"]')) {
        $(submitId).observe('click', function(){
            // setup form with AIM handlers
            fu.handleRequest();
            AIM.submit(form, {
                onComplete: function(content) {
                    //console.log(content);
                    fu.handleResponse({
                        responseText: content
                    });
                }
            });
            form.submit();
        });
        return;
    }
    */
};

var FormUtils = {
    /**
     * Handle request, make some visual effects.
     */
    handleRequest: function(form) {
        form.find('.error, .notification').remove();
        var button = form.find('button[type=submit]');
        button.attr('disabled', true);
    
        var img = button.find('img');
        var tmp = img.attr('src');
        img.attr('src', mvcskel_root+'images/progress.gif');
        img.attr('tmp', tmp);
    },

    /**
     * Handles response: decode request,
     * fill errors
     * or make redirect.
     */
    handleResponse: function (form, data, errorCallback) {
        try {
            if (data.success) {
                if (data.location.length) {
                    //console.log(data.location);
                    window.location = data.location;
                }
                return true;
            } else {
                FormUtils.showErrors(form, data);
                if (errorCallback) {
                    errorCallback(data.errors);
                }
                return false;
            }
        } catch (e) {
            form.find('.clearBoth').before('<div class="notification alert">Could not handle request, please try again later.</div>');
            return false;
        } finally {
            var img = form.find('button[type=submit] img');
            img.attr('src', img.attr('tmp'));
        //img.remove();
        }
    },
    
    /**
     * Show errors in forms
     */
    showErrors:function (form, data) {
        for (var err in data.errors) {
            form.find('input[name='+err+'],textarea[name='+err+'],select[name='+err+']').
            after('<div class="error">'+data.errors[err].join('<br>')+'</div>');
        }
        form.find('.clearBoth').before('<div class="notification">Please check your input, errors was found.</div>');
        form.find('button[type=submit]').attr('disabled', false);
    }
};

/**
 * Handle some form automatically
 */
$(function(){
    $('form.fu').fuinit();
});
