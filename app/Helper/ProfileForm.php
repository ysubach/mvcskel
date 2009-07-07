<?php
/**
 * MvcSkel member profile form helper.
 *
 * PHP versions 5
 *
 * @category   framework
 * @package    startup
 * @copyright  2009, Whirix Ltd.
 * @license    http://www.gnu.org/licenses/lgpl.html GNU Lesser Public General License (LGPL).
 * @link       http://code.google.com/p/mvcskel/
 */
class Helper_ProfileForm extends MvcSkel_Helper_Form {
    protected function buildFresh() {
        $auth = new MvcSkel_Helper_Auth();
        return $auth->getUser();
    }

    protected function fillByRequest() {
        $R = $_REQUEST;
        $u = $this->getObject();
        $u->username = $R['username'];
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
        if ($user!==false && $user->id!=$u->id) {
            $this->attachError('username', 'Username is already in use.');
        }

        // password checks
        if ($_REQUEST['password']!=$_REQUEST['pass2']) {
            $this->attachError('pass2', 'Password do not match original!');
        }

        // email related check
        $validator->checkNotEmpty('email', $u->email);
        $validator->checkEmail('email', $u->email);
        $user = Doctrine::getTable('User')->findOneByEmail($u->email);
        if ($user!==false && $user->id!=$u->id) {
            $this->attachError('email', 'Email is already in use.');
        }
    }

    protected function action() {
        $u = $this->getObject();
        if (!empty($_REQUEST['password'])) {
            $u->password = $_REQUEST['password'];
        }
        $u->save();
        MvcSkel_Helper_Auth::clearCurrentUser();
    }
}
?>
