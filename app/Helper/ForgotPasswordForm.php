<?php
/**
 * MvcSkel forgot password form helper.
 *
 * PHP versions 5
 *
 * @category   framework
 * @package    startup
 * @copyright  2009, Whirix Ltd.
 * @license    http://www.gnu.org/licenses/lgpl.html GNU Lesser Public General License (LGPL).
 * @link       http://code.google.com/p/mvcskel/
 */
class Helper_ForgotPasswordForm extends MvcSkel_Helper_Form {
    protected function buildFresh() {
        MvcSkel_Helper_Captcha::init(true);
        
        $u = new ArrayObject();
        return $u;
    }

    protected function fillByRequest() {
        $R = $_REQUEST;
        $u = $this->getObject();
        $u['email'] = $R['email'];
        $this->setObject($u);
    }

    protected function validate() {
        $u = $this->getObject();
        $validator = new MvcSkel_Helper_Validator($this);

        // email related check
        $validator->checkNotEmpty('email', $u['email']);
        $validator->checkEmail('email', $u['email']);
        if ($u['email']!='') {
            $user = Doctrine::getTable('User')->findOneByEmail($u['email']);
            if ($user===false) {
                $this->attachError('email', 'Email is not registered');
            }
        }

        // check captcha image
        if (!MvcSkel_Helper_Captcha::check($_REQUEST['captcha'])) {
            MvcSkel_Helper_Captcha::init(true);
            $this->attachError('captcha', 'Text does not match.');
        }
    }

    protected function action() {
        // save new password
        $u = $this->getObject();
        $user = Doctrine::getTable('User')->findOneByEmail($u['email']);
        $newPass = $this->genRandomPassword();
        $user->password = md5($newPass);
        $user->save();

        // send email notification
        MvcSkel_Helper_Mail::systemMessage('forgot', $user, array('newPass'=>$newPass));
        
        // clear image for next page render
        MvcSkel_Helper_Captcha::init(true);
    }

    /**
     * Generate new random password
     * @return string New password in clear text
     */
    protected function genRandomPassword() {
        $newPass = mt_rand(1000000, 9000000);
        return $newPass;
    }
}
?>
