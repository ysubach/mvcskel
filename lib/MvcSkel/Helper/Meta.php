<?php

/**
 * Assigning page meta information.
 * See file app/meta.yml
 *
 * About file app/meta.yml Hash named 'all' will be merged with the
 * specific page meta information. Psecific page information is a
 * priority.
 */
class MvcSkel_Helper_Meta {

    /**
     * Assigns meta information to smarty.
     * @param string $pageName page name to find in app/meta.yml
     * @param MvcSkel_Helper_Smarty $smarty smarty object to assign meta-info to
     */
    static public function assign($pageName, MvcSkel_Helper_Smarty $smarty) {
        $seo = MvcSkel_Helper_Config::read('app/meta.yml');
        $meta_page = empty($seo[$pageName]) ? array() : $seo[$pageName];
        $meta_all = empty($seo['all']) ? array() : $seo['all'];
        $smarty->assign('meta', $meta_page + $meta_all);
    }

}

?>
