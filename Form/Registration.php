<?php
/**
 * Registration form.
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
require_once 'MvcSkel/ModelEditCache.php';
/**
 * RegistrationForm class performs validation of user name.
 * 
 * @package form
 * @subpackage toppages
 */
class Form_Registration extends MvcSkel_Phrame_ActionForm {
    function putAll($vals) {
        parent::putAll($vals);
        $mec = new MvcSkel_ModelEditCache('User');
        $this->object =& $mec->get();
        $this->object->setFrom($this->values());
    }
    
    function validate() {
        $isValid = true;

        if (!MvcSkel_Validator::checkNotEmpty('Username', $this->object->login)) {
                
            $isValid = false;
        }
        if ($this->object->password == md5('')) {
                
            $isValid = false;
            MvcSkel_ErrorManager::raise('Password', 'not empty value expected');
        }
        if ($this->object->password != md5($_REQUEST['retype_password'])) {

            $isValid = false;
            MvcSkel_ErrorManager::raise('Password', 'password confirm mistaken');
        }
        if (!MvcSkel_Validator::checkNotEmpty('Email', $this->object->email)) {
                
            $isValid = false;
        } elseif (!MvcSkel_Validator::checkEmail('Email', $this->object->email)) {
                
            $isValid = false;
        }

        $userTmp = DB_DataObject::factory('User');
        $userTmp->login = $this->object->login;
        if ($userTmp->find(true) and $userTmp->id!=$this->object->id) {
            MvcSkel_ErrorManager::raise('Login', 'user with such login already exists');
            $isValid = false;
        }        
        return $isValid;
    }
}
?>
