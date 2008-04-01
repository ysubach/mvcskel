<?php
/**
 * Login page.
 * 
 * @package view
 * @subpackage toppages
 */
 
/**
 * Includes View class.
 */
require_once 'MvcSkel/View.php';

/**
 * Login view class.
 *
 * @package view
 * @subpackage toppages
 */ 
class View_Login extends MvcSkel_View {
    var $template = 'login.tpl';

    /**
     * Prepare function
     */
    function prepare() {
        parent::Prepare();
        if (isset($_REQUEST['destination'])) {
            
            $this->smarty->assign('destination', $_REQUEST['destination']);
        }
        
        $this->smarty->assign('title', 'Login');
    }
}
?>