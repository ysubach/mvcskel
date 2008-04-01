<?php
/**
* Access controller.
*
* PHP versions 4 and 5
*
* @category   framework
* @package    MvcSkel
* @copyright  2007, Whirix Ltd.
* @license    http://www.gnu.org/licenses/lgpl.html GNU Lesser Public General License (LGPL).
* @link       http://trac.whirix.com/mvcskel
*/

/**
* Include view access class.
*/
require_once 'MvcSkel/Accessable/View.php';
require_once 'MvcSkel/Accessable/Action.php';
require_once 'MvcSkel/Utils/Url.php';
require_once 'MvcSkel/UrlFactory/View.php';

/**
* Class represents access controller (security monitor)
* @package    MvcSkel
*/
class MvcSkel_AccessController {
    /**
     * Called automaticaly when user is logged in and have no access to view
     * or an action. Now it simply show error message and finish execution.
     * OVERWRITE it if you need different functionality. All REQUEST varables
     * are accesable, you have enouth information to make non-trivial decision.
     */
    function accessDenied () {
        if (isset($_REQUEST['action'])) {
            echo "ERROR: Access denied to action ".$_REQUEST['action'];
        } else if (isset($_REQUEST['view'])) {
            echo "ERROR: Access denied to view ".$_REQUEST['view'];
        } else {
            echo "ERROR: Access denied";
        }
        exit();
    }
    
    /**
     * Checks if current user have access to given view.
     * if user have no access and is not logged, displays login form and after
     * successful login redirects user to this view.
     * if user have no access and logged, calls accessDenied()
     * if user have access do nothing
     * @param $viewName name of checking view
     */
    function checkViewAccess($viewName) {
        $auth = &PEAR::getStaticProperty('MvcSkel', 'auth');
        if (!MvcSkel_AccessController::haveAccess($auth->getUser(), new MvcSkel_Accessable_View($viewName))) {

            if ($auth->getAuth()) {
                MvcSkel_AccessController::accessDenied();
            } else {
                $loginUrl = MvcSkel_UrlFactory_View::makeUrl('Login');
                $loginUrl->addVar('destination', $viewName);
                MvcSkel_Utils_Url::redirect($loginUrl->construct());
            }
        }
    }

    /**
     * Checks if current user have access to given action.
     * if user have no access, redirects to site root
     * if user have access do nothing
     * @param $actionName name of checking view
     */
    function checkActionAccess($actionName) {
        $auth = &PEAR::getStaticProperty('MvcSkel', 'auth');
        if (!MvcSkel_AccessController::haveAccess($auth->getUser(), new MvcSkel_Accessable_Action($actionName))) {        
            MvcSkel_AccessController::accessDenied();
        }
    }


    /**
     * Checks that given user have access to given object
     * @param $user checking user
     * @param $accessable checking object, that implements accessable interface
     * @return true if user have access and false otherwise
     */
    function haveAccess($user, $accessable) {
        $accessVector = $accessable->getAccessVector();
        foreach ($accessVector as $access) {
            
            if ($user->haveAccess($access)) {
                return true;
            }

            if (strcasecmp($access, 'Anonymous') == 0) {
                return true;
            }
        }

        return false;
    }
}
