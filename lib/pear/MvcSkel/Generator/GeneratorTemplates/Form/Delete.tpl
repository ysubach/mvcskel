<?php
/**
 * Form for editing of {{$table}}.
 * @package form
 * @subpackage {{$table}}
 */

/** 
 * Include Form.
 */
require_once 'MvcSkel/Phrame/ActionForm.php';

/**
 * Form for editing of {{$table}}.
 * @package form
 * @subpackage {{$table}}
 */
class Form_{{$table}}_{{$tplName}} extends MvcSkel_Phrame_ActionForm {
    var $object;
    
    /**
     * @see ActionForm::validate()
     */
    function validate() {
        if (isset($_REQUEST['id'])) {
            $this->object = DB_DataObject::factory('{{$table}}');
            $this->object->id = $_REQUEST['id'];
            return true;
        }			
        MvcSkel_ErrorManager::raise('Id', 'id is not set');
        return false;
    }
}
