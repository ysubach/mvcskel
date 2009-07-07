<?php
/**
 * MvcSkel signup form helper.
 *
 * PHP versions 5
 *
 * @category   framework
 * @package    startup
 * @copyright  2009, Whirix Ltd.
 * @license    http://www.gnu.org/licenses/lgpl.html GNU Lesser Public General License (LGPL).
 * @link       http://code.google.com/p/mvcskel/
 */
class Helper_SignupForm extends MvcSkel_Helper_Form {
    protected function buildFresh() {
        $u = new User();
        return $u;
    }

    protected function fillByRequest() {
        $R = $_REQUEST;
        $u = $this->getObject();
        $u->username = $R['username'];
        $u->password = $R['password'];
        $u->email = $R['email'];
        $u->fname = $R['fname'];
        $this->setObject($u);
    }

    protected function validate() {
        $u = $this->getObject();
        $validator = new MvcSkel_Helper_Validator($this);

        // username checks
        $validator->checkNotEmpty('username', $u->username);
        $user = Doctrine::getTable('User')->findOneByUsername($u->username);
        if ($user!==false) {
            $this->attachError('username', 'Username is already in use.');
        }

        // password checks
        $validator->checkNotEmpty('password', $u->password);
        if ($u->password!='' && $u->password!=$_REQUEST['pass2']) {
            $this->attachError('pass2', 'Password do not match original!');
        }

        // email related check
        $validator->checkNotEmpty('email', $u->email);
        $validator->checkEmail('email', $u->email);
        $user = Doctrine::getTable('User')->findOneByEmail($u->email);
        if ($user!==false) {
            $this->attachError('email', 'Email is already in use.');
        }

        // check captcha image
        if (!MvcSkel_Helper_Captcha::check($_REQUEST['captcha'])) {
            MvcSkel_Helper_Captcha::init(true);
            $this->attachError('captcha', 'Text does not match.');
        }
    }

    protected function action() {
        $u = $this->getObject();
        $u->password = md5($u->password);
        $u->roles = 'User';
        $u->registrationDT = new Doctrine_Expression('now()');
        $u->save();

        // clear image for next signup
        MvcSkel_Helper_Captcha::init(true);

        $auth = new MvcSkel_Helper_Auth();
        $auth->start();
        $auth->setAuth($u->username);
    }
}
?>
