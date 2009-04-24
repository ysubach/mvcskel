<?php
/**
* Test controller.
*/
class Controller_Admin extends MvcSkel_Controller {
    public function __construct() {
        //$this->addFilter(new MvcSkel_Filter_Auth('Administrator'));
    }
    
    public function actionIndex() {
        $auth = new MvcSkel_Helper_Auth();
        echo "roles: " . $auth->getAuthData('roles');
        echo "<br>fname: " . $auth->getAuthData('fname');
        return '<br>you are admin';
    }

    /**
     * View list of users
     */
    public function actionUsers() {
        $smarty = new MvcSkel_Helper_Smarty('Admin/users.tpl');
        $usersList = new Helper_UsersList();
        $usersList->assignValues($smarty);
        return $smarty->render();
    }
}
?>