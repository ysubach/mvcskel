<?php
/**
 * Logout action.
 * 
 * @package action
 * @subpackage toppages
 */

/** 
 * Include Action.
 */
require_once 'MvcSkel/Phrame/Action.php';

/**
 * Class that performs logout action. 
 * 
 * @package action
 * @subpackage toppages
 */
class Action_Logout extends MvcSkel_Phrame_Action {
    function perform($actionMapping, $actionForm) {
        $auth = &PEAR::getStaticProperty('MvcSkel', 'auth');
        $auth->logout();
        return $actionMapping->get('start');
    }
}
