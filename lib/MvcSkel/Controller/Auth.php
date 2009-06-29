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

/**
 * Auth controller.
 * Renders login form, start authenticated session.
 * Makes logout.
 * @package    MvcSkel
 * @subpackage Controller
 */
class MvcSkel_Controller_Auth extends MvcSkel_Controller {
    public function actionLogin() {
        $auth = new MvcSkel_Helper_Auth();
        $auth->start();

        if (!$auth->getAuth()) {
            $view = new MvcSkel_Helper_Smarty('login.tpl');
            $view->assign('title', 'Sign In Page');
            if (isset($_REQUEST['destination'])) {
                $view->assign('destination', $_REQUEST['destination']);
            }
            return $view->render();
        }

        // deal with remember me flag
        if (isset($_REQUEST['rememberme'])) {
            $auth->autoLoginPut();
        }
        
        if (isset($_REQUEST['destination'])) {
            MvcSkel_Helper_Url::redirect($_REQUEST['destination']);
        } else {
            MvcSkel_Helper_Url::redirect("Main/Index");
        }
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