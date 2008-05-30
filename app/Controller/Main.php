<?php
require_once 'MvcSkel/Controller.php';
require_once 'MvcSkel/Helper/Smarty.php';
require_once 'MvcSkel/Helper/Config.php';
require_once 'MvcSkel/Helper/Log.php';

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
}
?>