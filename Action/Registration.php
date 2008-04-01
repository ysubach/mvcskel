<?php
/**
 * Registration action.
 * 
 * @package action
 * @subpackage toppages
 */

/** 
 * Include Action.
 */
require_once 'MvcSkel/Phrame/Action.php';

/**
 * Class that performs registration action. 
 * 
 * @package action
 * @subpackage toppages
 */
class Action_Registration extends MvcSkel_Phrame_Action {
    function perform($actionMapping, $actionForm) {
        $actionForm->object->setRights(array('User'));
        $actionForm->object->insert();
        return $actionMapping->get('ok');    	
    }
}
?>