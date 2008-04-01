<?php
/**
 * Action for editing of {{$table}}.
 * @package action
 * @subpackage {{$table}}
 */
 
/** 
 * Include Action.
 */
require_once 'MvcSkel/Phrame/Action.php';

/**
 * Action for editing of {{$table}}.
 * @package action
 * @subpackage {{$table}}
 */
class Action_{{$table}}_{{$tplName}} extends MvcSkel_Phrame_Action {
    /**
     * @see Action::perform()
     */ 
    function perform($actionMapping, $actionForm) {
        if ($actionForm->object->id) {
            $actionForm->object->update();
        } else {
            $actionForm->object->insert();
        }
        return $actionMapping->get('ok');        
    }
}
