/* Initial settings */
function initializeApplication() {
    if ($('formSignup')) {
        new SignupPage();
    }
    if ($('formProfile')) {
        new Profile();
    }
}

Event.observe(window, 'load', initializeApplication);
