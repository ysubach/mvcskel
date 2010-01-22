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

        var fu = this;
        var form = $(formId);

        // check input file, use AIM in that case
        if (form.select('input[type="file"]').length) {
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

        // set event for very common case of form handling
        $(submitId).observe('click', function(){
            fu.handleRequest();
            form.request({
                onComplete: function(transport) {
                    fu.handleResponse(transport);
                }
            });
        });
    },
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

/**
 *
 *  AJAX IFRAME METHOD (AIM)
 *  http://www.webtoolkit.info/
 *
 **/
AIM = {
    frame : function(c) {

        var n = 'f' + Math.floor(Math.random() * 99999);
        var d = document.createElement('DIV');
        d.innerHTML = '<iframe style="display:none" src="about:blank" id="'+n+'" name="'+n+'" onload="AIM.loaded(\''+n+'\')"></iframe>';
        document.body.appendChild(d);

        var i = document.getElementById(n);
        if (c && typeof(c.onComplete) == 'function') {
            i.onComplete = c.onComplete;
        }

        return n;
    },

    form : function(f, name) {
        f.setAttribute('target', name);
    },

    submit : function(f, c) {
        AIM.form(f, AIM.frame(c));
        if (c && typeof(c.onStart) == 'function') {
            return c.onStart();
        } else {
            return true;
        }
    },

    loaded : function(id) {
        var i = document.getElementById(id);
        if (i.contentDocument) {
            var d = i.contentDocument;
        } else if (i.contentWindow) {
            var d = i.contentWindow.document;
        } else {
            var d = window.frames[id].document;
        }
        if (d.location.href == "about:blank") {
            return;
        }

        if (typeof(i.onComplete) == 'function') {
            i.onComplete(d.body.innerHTML);
        }
    }
}
