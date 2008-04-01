<?php
/**
 * Form for editing of User.
 * @package form
 * @subpackage User
 */

/**
 * Include base class.
 */ 
require_once 'MvcSkel/ModelEditCache.php';

/** 
 * Include Form.
 */
require_once 'MvcSkel/Phrame/ActionForm.php';

/**
 * Include validator.
 */
require_once 'MvcSkel/Validator.php';

/**
 * Form for editing of User.
 * @package form
 * @subpackage User
 */
class Form_User_AddEdit extends MvcSkel_Phrame_ActionForm {
    var $object;
    
    /**
     * @see ActionForm::putAll()
     */
    function putAll($vals) {
        parent::putAll($vals);
        $mec = new MvcSkel_ModelEditCache('User');
        $this->object =& $mec->get();
        $this->object->setFrom($this->values());
    }
    
    /**
     * @see ActionForm::validate()
     */
    function validate() {
        $isValid = true;
        
        $userTmp = DB_DataObject::factory('User');
        $userTmp->login = $this->object->login;
        if ($userTmp->find(true) and $userTmp->id!=$this->object->id) {
            MvcSkel_ErrorManager::raise('Username', 'user with such username already exists');
            $isValid = false;
        }
        
        if (!MvcSkel_Validator::checkNotEmpty('Username', $this->get('login'))) {
            $isValid = false;
        }

        if (!MvcSkel_Validator::checkNotEmpty('Email', $this->get('email'))) {
            $isValid = false;
        }
	
        if (!MvcSkel_Validator::checkEmail('Email', $this->get('email'))) {
            $isValid = false;
        }	

        // we will use this var many times
        $password = $this->get('password');
        
        if (!isset($this->object->id)) {
            if (!MvcSkel_Validator::checkNotEmpty('Password', $password)) {
                $isValid = false;
            }
        }

        if (strcmp($password, $this->get('retype_password'))!=0) {
            MvcSkel_ErrorManager::raise('Password', 'passwords do not match');
            $isValid = false;
        } else {
            if (empty($password)) {
                unset($this->object->password);
            }
        }
        
        return $isValid;
    }
}