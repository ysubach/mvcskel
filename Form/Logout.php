<?php
/**
 * Logout form.
 * 
 * @package form
 * @subpackage toppages
 */

/** 
 * Include Form.
 */
require_once 'MvcSkel/Phrame/ActionForm.php';

/**
 * Logout form class.
 * 
 * @package form
 * @subpackage toppages
 */
class Form_Logout extends MvcSkel_Phrame_ActionForm {
    function validate() {
        return true;
    }
}
?>
