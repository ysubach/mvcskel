<?php
require_once 'MvcSkel/Controller.php';
require_once 'MvcSkel/Helper/Smarty.php';
require_once 'MvcSkel/Helper/Config.php';
require_once 'MvcSkel/Helper/Log.php';
require_once 'MvcSkel/Helper/Auth.php';
require_once 'MvcSkel/Filter/Auth.php';

/**
* Test controller.
*/
class Controller_Admin extends MvcSkel_Controller {
    public function __construct() {
        $this->addFilter(new MvcSkel_Filter_Auth('Administrator'));
    }
    
    public function actionIndex() {
        $auth = new MvcSkel_Helper_Auth();
        echo "roles: " . $auth->getAuthData('roles');
        echo "<br>fname: " . $auth->getAuthData('fname');
        return '<br>you are admin';
    }
}
?>