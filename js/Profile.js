/**
 * Profile AJAX functions.
 */
var Profile = Class.create({
    initialize: function () {
        this.fu = new FormUtils();
        $('formProfileSubmit').observe('click', this.clickSubmit.bindAsEventListener(this));
    },
    clickSubmit: function() {
        this.fu.handleRequest();
        $('formProfile').request({
            onComplete: function(transport) {
                this.fu.handleResponse(transport);
            }.bind(this)
        });
    }
});
