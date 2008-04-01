<?php
/**
* MvcSkel runner.
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
*
*/
require_once 'MvcSkel/Smarty.php';
require_once 'MvcSkel/ViewFactory.php';
require_once 'MvcSkel/AccessController.php';
require_once 'MvcSkel/Phrame/ActionController.php';
require_once 'MvcSkel/ErrorManager.php';

/**
* Handle request with MvcSkel framework.
* @category   framework
* @package    MvcSkel
*/ 
class MvcSkel {
    /**
    * Run framework
    * @static
    * @access public
    */
    function run($defaultView = 'Main') {
        if (isset($_REQUEST['view'])) {
            MvcSkel::_handleView($_REQUEST['view']);
        } else if (isset($_REQUEST['action'])) {
            MvcSkel::_handleAction($_REQUEST['action']);
        } else {
            MvcSkel::_handleView($defaultView);
        }
    }
    
    /**
    * Process view.
    * @access protected
    * @static
    */
    function _handleView($viewName) {
        MvcSkel_AccessController::checkViewAccess($viewName);
        $viewFactory = new MvcSkel_ViewFactory();        
        $view =& $viewFactory->build($viewName);
        $view->init(new MvcSkel_Smarty());
        $view->render();        
    }

    /**
    * Process action.
    * @access protected
    * @static
    */
    function _handleAction($actionName) {
        MvcSkel_AccessController::checkActionAccess($actionName);
        $controller = new MvcSkel_Phrame_ActionController();
        MvcSkel_ErrorManager::clear();
        $map = &PEAR::getStaticProperty('MvcSkel', 'mappings');
        $controller->process($map, $_REQUEST);    
    }
}