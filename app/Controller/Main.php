<?php
/**
* Test controller.
*/
class Controller_Main extends MvcSkel_Controller {
    public function actionIndex() {
        $smarty = new MvcSkel_Helper_Smarty('main.tpl');            
        $log = MvcSkel_Helper_Log::get(get_class($this));
        $log2 = MvcSkel_Helper_Log::get();
        $log->debug('hi!');
        $log2->debug('hi2');
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