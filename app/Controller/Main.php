<?php
/**
* Test controller.
*/
class Controller_Main extends MvcSkel_Controller {
    public function actionIndex() {
        $smarty = new MvcSkel_Helper_Smarty('main.tpl');            
        $smarty->assign('title', 'Summary Page');
        return $smarty->render();
    }

    public function actionShowConfig() {
        $config = MvcSkel_Helper_Config::read();
        var_dump($config);
    }
    
    public function actionPhpInfo() {
        phpinfo();
    }

    public function actionCheckAuth() {
        $auth = new MvcSkel_Helper_Auth();
        $auth->start();
        $res = 'not authenticated';
        if ($auth->checkAuth()) {
            $res = 'authenticated<br>';
            if ($auth->checkRole('User')) {
                $res .= 'has User role<br>';
            }
            if ($auth->checkRole('Administrator')) {
                $res .= 'has Administrator role<br>';
            }
        }
        return $res;
    }
}
?>