<?php
/**
 * Forget password form.
 * 
 * @package form
 * @subpackage toppages
 */

/** 
 * Include Form.
 */
require_once 'MvcSkel/Phrame/ActionForm.php';

/**
 * Use common validator.
 */
require_once 'MvcSkel/Validator.php';

/**
 * Forget password form.
 * 
 * @package form
 * @subpackage toppages
 */
class Form_ForgetPassword extends MvcSkel_Phrame_ActionForm
{
    var $user;

    function reset() {
        $this->user = null;
    }

    function putAll($vals) {
        parent::putAll($vals);
        $this->user = new User();
        $this->user->login = $this->get('login');
        $this->user->find(true);
    }
    
    /**
     * Validates user input
     */
    function validate() {
        $isValid = true;
        if (!MvcSkel_Validator::checkNotEmpty('Login', $this->user->login)) {
        
            $isValid = false;
        } else { 
	
            if (!$this->user->id) {
                
                $isValid = false;
                MvcSkel_ErrorManager::raise('Login', 'user with given login does not exists');
            }
        }

        return $isValid;
    }
}
?>
