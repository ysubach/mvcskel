<?php
/**
 * Rendering of statoc pages
 */
class Controller_Static extends MvcSkel_Controller {
    /** Static pages list */
    private $staticPageList = array(
        'Home' => 'Summary Page',
    );

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
        $smarty->assign('title', $this->staticPageList[$p]);
        $smarty->assign('staticPage', $p);
        $smarty->assign('staticPageList', $this->staticPageList);
        echo $smarty->render($p);
    }

}
?>
