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
 * Show when password is succesfuly sent.
 *
 * @package view
 * @subpackage toppages
 */
class View_PasswordSent extends MvcSkel_View {
    var $template = 'passwordSent.tpl';

    /**
     * Prepare function
     */
    function prepare() {
        parent::prepare();
        
        $this->smarty->assign('login', $_SESSION['FORGET_PASSWORD_USER']->login);
        $this->smarty->assign('email', $_SESSION['FORGET_PASSWORD_USER']->email);
        $this->smarty->assign('title', 'Password sent');
    }
}
?>
