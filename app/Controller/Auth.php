<?php
/**
* MvcSkel filter.
*
* PHP versions 5
*
* @category   framework
* @package    MvcSkel
* @copyright  2008, Whirix Ltd.
* @license    http://www.gnu.org/licenses/lgpl.html GNU Lesser Public General License (LGPL).
* @link       http://code.google.com/p/mvcskel/
*/
    
require_once 'MvcSkel/Controller.php';
require_once 'MvcSkel/Helper/Smarty.php';
require_once 'MvcSkel/Helper/Auth.php';
require_once 'MvcSkel/Helper/Url.php';

/**
 * Auth controller.
 * Renders login form, start authenticated session.
 * Makes logout.
 */
class Controller_Auth extends MvcSkel_Controller {
    public function actionLogin() {
        $auth = new MvcSkel_Helper_Auth();
        $auth->start();
        
        if (!$auth->getAuth()) {
            $smarty = new MvcSkel_Helper_Smarty('login.html');            
            return $smarty->render();
        }
        
        MvcSkel_Helper_Url::redirect("Main/Index");
    }

    public function actionLogout() {
        $auth = new MvcSkel_Helper_Auth();
        $auth->start();
        if ($auth->checkAuth()) {
            $auth->logout();
            $auth->start();
        }

        MvcSkel_Helper_Url::redirect("Main/Index");
    }
}
?>