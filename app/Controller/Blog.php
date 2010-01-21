<?php
/**
 * Blog controller
 */
class Controller_Blog extends MvcSkel_Controller {
    public function actionIndex() {
        $smarty = new MvcSkel_Helper_Smarty('Blog/hello.tpl');
        $smarty->assign('title', 'Hello Page');
        return $smarty->render();
    }

    public function actionAnother() {
        echo "Second action!";
    }
}
?>