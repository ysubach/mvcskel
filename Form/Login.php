<?php
/**
 * Login form.
 * 
 * @package form
 * @subpackage toppages
 */

/** 
 * Include Form.
 */
require_once 'MvcSkel/Phrame/ActionForm.php';

/**
 * LoginForm class performs validation of user name.
 * It actually empty. It is not correct for now...
 * 
 * @package form
 * @subpackage toppages
 */
class Form_Login extends MvcSkel_Phrame_ActionForm {
    function validate() {
        return true;
    }
}
?>
