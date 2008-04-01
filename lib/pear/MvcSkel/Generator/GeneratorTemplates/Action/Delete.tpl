<?php
/**
 * Action for deleting of {{$table}}.
 * @package action
 * @subpackage {{$table}}
 */
 
/** 
 * Include Action.
 */
require_once 'MvcSkel/Phrame/Action.php';

/**
 * Action for deleting of {{$table}}.
 * @package action
 * @subpackage {{$table}}
 */
class Action_{{$table}}_{{$tplName}} extends MvcSkel_Phrame_Action {
    /**
     * @see Action::perform()
     */ 
    function perform($actionMapping, $actionForm) {
        $actionForm->object->delete();
        return $actionMapping->get('ok');        
    }
}
