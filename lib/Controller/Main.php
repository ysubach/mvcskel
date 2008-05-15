<?php
    require_once 'MvcSkel/Controller.php';
    require_once 'MvcSkel/Helper/Smarty.php';
    require_once 'MvcSkel/Helper/Config.php';

/**
* Test controller.
*/
    class Controller_Main extends MvcSkel_Controller {
        public function actionIndex() {
            $smarty = new MvcSkel_Helper_Smarty('main.html');            
            return $smarty->render();
        }
        
        public function actionShowConfig() {
            $config = MvcSkel_Helper_Config::read();
            var_dump($config);
        }
    }
?>