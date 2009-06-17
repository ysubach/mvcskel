<?php
/**
 * MvcSkel Signup controller.
 *
 * PHP versions 5
 *
 * @category   framework
 * @package    MvcSkel
 * @copyright  2008, Whirix Ltd.
 * @license    http://www.gnu.org/licenses/lgpl.html GNU Lesser Public General License (LGPL).
 * @link       http://code.google.com/p/mvcskel/
 *
 * @todo forgot password
 */
class Controller_Signup extends MvcSkel_Controller {
    public function __construct() {
    }

    public function actionIndex() {
        $smarty = new MvcSkel_Helper_Smarty('Member/signup.tpl');
        $form = new Helper_SignupForm('Signup', 'Member', $smarty);
        $smarty->assign('title', 'Sign Up MvcSkel');
        return $form->process();
    }

    /**
     * Output captcha image.
     */
    public function actionCaptcha() {
        MvcSkel_Helper_Captcha::init(isset($_REQUEST['c']));
        MvcSkel_Helper_Captcha::getImage();
    }
}
?>