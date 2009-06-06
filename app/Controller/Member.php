<?php
/**
* Test controller.
*/
class Controller_Member extends MvcSkel_Controller {
    public function __construct() {
        //$this->addFilter(new MvcSkel_Filter_Auth(array('User', 'Administrator')));
    }
    
    public function actionIndex() {
        $auth = new MvcSkel_Helper_Auth();
        echo "roles: " . $auth->getAuthData('roles');
        echo "<br>fname: " . $auth->getAuthData('fname');
        return '<br>you are member';
    }

    /** Member signup form */
    public function actionSignup() {
        $smarty = new MvcSkel_Helper_Smarty('Member/signup.tpl');
        $form = new Helper_SignupForm('Member/Signup', 'Member/SignupOk', $smarty);
        $smarty->assign('title', 'Sign Up MvcSkel');
        return $form->process();
    }

    /** Member signup result page */
    public function actionSignupOk() {
        $smarty = new MvcSkel_Helper_Smarty('Member/signupOk.tpl');
        $smarty->assign('title', 'Successful Singup');
        return $smarty->render();
    }
}
?>