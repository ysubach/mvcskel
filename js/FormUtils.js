/**
 * Some useful utils for ajax form handling.
 */
var FormUtils = Class.create({
    /**
     * C-r. If parameters are given, then it can be used for
     * very common case of form handling.
     */
    initialize: function(formId, submitId) {
        if (typeof(formId)=='undefined' || typeof(submitId)=='undefined') {
            return;
        }

        // set event for very common case of form handling
        var fu = new FormUtils();
        $(submitId).observe('click', function(event, form, fu){
            fu.handleRequest();
            $(form).request({
                onComplete: function(fu, transport) {
                    fu.handleResponse(transport);
                }.bind(this, fu)
            });
        }.bindAsEventListener(this, $(formId), fu));
    },
    /**
     * Handle request, make some visual effects.
     */
    handleRequest:function() {
        $('mvcskel_form_problem').hide();
        $('mvcskel_form_complete').show();
        $('mvcskel_form_progress').show();
    },

    /**
     * Handles response: decode request,
     * fill errors
     * or make redirect.
     */
    handleResponse: function (transport, errorCallback) {
        try {
            var res = transport.responseText.evalJSON();
            if (res.success) {
                if (res.location.length) {
                    //console.log(res.location);
                    window.location = res.location;
                }
                return true;
            } else {
                this.showErrors(res);
                if (errorCallback) {
                    errorCallback(res.errors);
                }
                return false;
            }
        } catch (e) {
            $('mvcskel_form_problem').show();
            return false;
        } finally {
            $('mvcskel_form_progress').hide();
        }
    },
    /**
     * Show errors in forms
     */
    showErrors:function (res) {
        $$('div.error').each(function(s) {
            s.hide();
        });
        for (var err in res.errors) {
            if ($('error-'+err)) {
                $('error-'+err).update(res.errors[err].join(',<br>')).show();

            }
        }
        $('mvcskel_form_complete').show();
    }
});
