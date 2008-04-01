<?php
/**
 * Login action.
 * 
 * @package action
 * @subpackage toppages
 */

/** 
 * Include Action.
 */
require_once 'MvcSkel/Phrame/Action.php';
require_once 'MvcSkel/UrlFactory/View.php';

/**
 * Class that performs login action by checking username and password with user
 * database 
 * 
 * @package action
 * @subpackage toppages
 */
class Action_Login extends MvcSkel_Phrame_Action {
    function perform($actionMapping, $actionForm) {
        $auth = &PEAR::getStaticProperty('MvcSkel', 'auth');

        // if successful
        if ($auth->getAuth()) {
            if (isset($_REQUEST['destination']) and !empty($_REQUEST['destination'])) {
                $url = MvcSkel_UrlFactory_View::makeUrl($_REQUEST['destination']);
                MvcSkel_Utils_Url::redirect($url->construct());
            } else {
                MvcSkel_Utils_Url::redirect(MvcSkel_Utils_Url::getRootURL());
            }
        } else {      
            MvcSkel_ErrorManager::raise('Login', 'login or password incorrect');
            $url = MvcSkel_UrlFactory_View::makeUrl('Login');
            if (isset($_REQUEST['destination'])) {
                $url->addVar('destination', $_REQUEST['destination']);
            }
            MvcSkel_Utils_Url::redirect($url->construct());
        }
    }
}

