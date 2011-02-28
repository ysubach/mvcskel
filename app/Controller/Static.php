<?php
/**
 * Rendering of statoc pages
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
        $seo = MvcSkel_Helper_Config::read('app/meta.yml');
        $meta_page = empty($seo[$p]) ? array() : $seo[$p];
        $meta_all = empty($seo['all']) ? array() : $seo['all'];
        $smarty->assign('meta', $meta_page + $meta_all);

        $smarty->assign('staticPage', $p);
        echo $smarty->render($p);
    }

}
?>