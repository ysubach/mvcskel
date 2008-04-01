<?php
/**
 * Action for deleting of User.
 * @package action
 * @subpackage User
 */
 
/** 
 * Include Action.
 */
require_once 'MvcSkel/Phrame/Action.php';

/**
 * Action for deleting of User.
 * @package action
 * @subpackage User
 */
class Action_User_Delete extends MvcSkel_Phrame_Action {
    /**
     * @see Action::perform()
     */ 
    function perform($actionMapping, $actionForm) {
        $actionForm->object->delete();
        return $actionMapping->get('ok');        
    }
}