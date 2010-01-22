/* Initial settings */
function initializeApplication() {
    if ($('formSignup')) {
        new SignupPage();
    }
    if ($('formProfile')) {
        new FormUtils('formProfile', 'formProfileSubmit');
    }
}

Event.observe(window, 'load', initializeApplication);
