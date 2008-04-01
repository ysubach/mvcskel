<?php
/**
 * Main view (home page).
 * 
 * @package view
 * @subpackage toppages
 */
 
/**
 * Includes View class.
 */
require_once 'MvcSkel/View.php';
 
/**
 * Home page class.
 *
 * @package view
 * @subpackage toppages
 */
class View_Main extends MvcSkel_View {
    var $template = 'main.tpl';

    /**
     * Prepare function
     */
    function prepare() {
        parent::prepare();
        $this->smarty->assign('title', 'The First Page');
    }
}
?>
