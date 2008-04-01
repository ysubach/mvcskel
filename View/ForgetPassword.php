<?php
/**
 * Forget password form page.
 * 
 * @package view
 * @subpackage toppages
 */
 
/**
 * Includes View class.
 */
require_once 'MvcSkel/View.php';
 
/**
 * Forget password view class.
 *
 * @package view
 * @subpackage toppages
 */
class View_ForgetPassword extends MvcSkel_View {
    var $template='forgetPassword.tpl';

    function prepare() {
        parent::prepare();
        $this->smarty->assign('title', 'Forget Password');
        if (isset($_SESSION['forgetLogin'])) {
            $this->smarty->assign('forgetLogin', $_SESSION['forgetLogin']);
        }
    }
}
?>