<?php
/**
* Test controller.
*/
class Controller_Member extends MvcSkel_Controller {
    public function __construct() {
        $this->addFilter(new MvcSkel_Filter_Auth(array('User', 'Administrator')));
    }
    
    public function actionIndex() {
        $auth = new MvcSkel_Helper_Auth();
        echo "roles: " . $auth->getAuthData('roles');
        echo "<br>fname: " . $auth->getAuthData('fname');
        return '<br>you are member';
    }
}
?>