<?php
/**
* Test controller.
*/
class Controller_Admin extends MvcSkel_Controller {
    public function __construct() {
        //$this->addFilter(new MvcSkel_Filter_Auth('Administrator'));
    }
    
    public function actionIndex() {
        $smarty = new MvcSkel_Helper_Smarty('Admin/index.tpl');
        $smarty->setLayout('adminMaster.tpl');
        $smarty->assign('title', 'Admin Dashboard');
        $this->prepareDashboard($smarty);
        return $smarty->render();
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

    public function actionShowConfig() {
        $config = MvcSkel_Helper_Config::read();
        var_dump($config);
    }

    public function actionPhpInfo() {
        phpinfo();
    }

    protected function prepareDashboard($smarty) {
        // get user count
        $q = Doctrine_Query::create()
        ->from('User');
        $smarty->assign('totalUsers', $q->count());

        $q->where('lastLoginDT>?', date('Y-m-d', strtotime("-1 day")));
        $smarty->assign('uniqueLogins', $q->count());

        $q->where('registrationDT>?', date('Y-m-d', strtotime("-1 week")));
        $smarty->assign('newUsers', $q->execute());
    }
}
?>