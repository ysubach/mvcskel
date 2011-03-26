<?php
/**
 * Rendering of static pages
 */
class Controller_Static extends MvcSkel_Controller {
    /**
     * Special entry for home
     */
    public function actionIndex() {
        $_REQUEST['page'] = 'Home';
        return $this->actionRender();
    }

    /**
     * Main entry point
     */
    public function actionRender() {
        $p = $_REQUEST['page'];
        $smarty = new MvcSkel_Helper_Smarty("Static/$p.tpl");
        $smarty->caching = 2;
        $smarty->cache_lifetime = 3600*24*7;

        // static seo information
        MvcSkel_Helper_Meta::assign($p, $smarty);

        $smarty->assign('staticPage', $p);
        echo $smarty->render($p);
    }

}
?>