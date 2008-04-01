<?php
/**
 * Form for editing of User.
 * @package form
 * @subpackage User
 */

/** 
 * Include Form.
 */
require_once 'MvcSkel/Phrame/ActionForm.php';

/**
 * Form for editing of User.
 * @package form
 * @subpackage User
 */
class Form_User_Delete extends MvcSkel_Phrame_ActionForm {
    var $object;
    
    /**
     * @see ActionForm::validate()
     */
    function validate() {
        if (isset($_REQUEST['id'])) {
            $this->object = DB_DataObject::factory('User');
            $this->object->id = $_REQUEST['id'];
            return true;
        }			
        MvcSkel_ErrorManager::raise('Id', 'id is not set');
        return false;
    }
}