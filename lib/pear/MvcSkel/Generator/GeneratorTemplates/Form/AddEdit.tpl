<?php
/**
 * Form for editing of {{$table}}.
 * @package form
 * @subpackage {{$table}}
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
 * Form for editing of {{$table}}.
 * @package form
 * @subpackage {{$table}}
 */
class Form_{{$table}}_{{$tplName}} extends MvcSkel_Phrame_ActionForm {
    var $object;
    
    /**
     * @see ActionForm::putAll()
     */
    function putAll($vals) {
        parent::putAll($vals);
        $mec = new MvcSkel_ModelEditCache('{{$table}}');
        $this->object =& $mec->get();
        $this->object->setFrom($this->values());
    }
    
    /**
     * @see ActionForm::validate()
     */
    function validate() {
        // no validation by default	
        return true;
    }
}
