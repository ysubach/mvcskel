/**
 * Some useful utils for ajax form handling.
 */
var FormUtils = Class.create({
    initialize: function(){},
    /**
     * Handle request, make some visual effects.
     * @param formId id of form which will be submitted
     */
    handleRequest:function() {
        this.hideProblem();
        this.hideComplete();
        this.showProgress();
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
            this.showProblem();
            return false;
        } finally {
            this.hideProgress();
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
        this.showComplete();
    },

    /**
     * Show problem div.
     */
    showProblem: function() {
        if ($('mvcskel_form_problem')) {
            $('mvcskel_form_problem').show();
        }
    },
    hideProblem: function() {
        if ($('mvcskel_form_problem')) {
            $('mvcskel_form_problem').hide();
        }
    },
    /**
     * Show progress div.
     */
    showProgress: function() {
        if ($('mvcskel_form_progress')) {
            $('mvcskel_form_progress').show();
        }
    },
    hideProgress: function() {
        if ($('mvcskel_form_progress')) {
            $('mvcskel_form_progress').hide();
        }
    },
    /**
     * Show complete div, when ok, with errors was returned
     */
    showComplete: function() {
        if ($('mvcskel_form_complete')) {
            $('mvcskel_form_complete').show();
        }
    },
    hideComplete: function() {
        if ($('mvcskel_form_complete')) {
            $('mvcskel_form_complete').hide();
        }
    }
});
