/* Initial settings */
function initializeApplication() {
    if ($('formSignup')) {
        new SignupPage();
    }
}

Event.observe(window, 'load', initializeApplication);
