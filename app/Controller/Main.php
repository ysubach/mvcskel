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
}
?>