<?php
/**
 * MvcSkel Member controller.
 *
 * PHP versions 5
 *
 * @category   framework
 * @package    startup
 * @copyright  2009, Whirix Ltd.
 * @license    http://www.gnu.org/licenses/lgpl.html GNU Lesser Public General License (LGPL).
 * @link       http://code.google.com/p/mvcskel/
 */
class Controller_Member extends MvcSkel_Controller {
    public function __construct() {
        $this->addFilter(new MvcSkel_Filter_Auth(array('User', 'Administrator')));
        $this->addFilter(new MvcSkel_Filter_Ownership(array()));
    }

    /**
     * Profile edit page.
     * @todo upload avatar
     */
    public function actionIndex() {
        $smarty = new MvcSkel_Helper_Smarty('Member/profile.tpl');
        $form = new Helper_ProfileForm('Member', 'Member/View', $smarty);
        $smarty->assign('title', 'Profile Page');
        $smarty->assign('roles', array('User', 'Administrator'));
        return $form->process(true);
    }

    /**
     * @todo Profile view page.
     */
    public function actionView() {
        $smarty = new MvcSkel_Helper_Smarty('Member/view.tpl');
        $smarty->assign('title', 'Profile View');
        echo $smarty->render();
    }
}
?>