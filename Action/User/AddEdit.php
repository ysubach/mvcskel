<?php
/**
 * Action for editing of User.
 * @package action
 * @subpackage User
 */
 
/** 
 * Include Action.
 */
require_once 'MvcSkel/Phrame/Action.php';

/**
 * Action for editing of User.
 * @package action
 * @subpackage User
 */
class Action_User_AddEdit extends MvcSkel_Phrame_Action {
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