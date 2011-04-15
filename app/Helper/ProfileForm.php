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
        if (isset($_REQUEST['id'])) {
            $u = Doctrine::getTable('User')->find($_REQUEST['id']);
            return $u;
        } elseif (isset($_REQUEST['new'])) {
            $o = new User();
            $o->roles = 'User';
            return $o;
        }
        $auth = new MvcSkel_Helper_Auth();
        return $auth->getUser();
    }

    protected function fillByRequest() {
        $R = $_REQUEST;
        $u = $this->getObject();
        $u->username = $R['username'];
        $u->email = $R['email'];
        $u->fname = $R['fname'];

        // only admin can setup roles
        $auth = new MvcSkel_Helper_Auth();
        if ($auth->checkRole('Administrator') && isset($R['roles'])) {
            $u->roles = $R['roles'];
        }

        $this->setObject($u);
    }

    protected function validate() {
        $u = $this->getObject();
        $validator = new MvcSkel_Helper_Validator($this);

        // username checks
        $validator->checkNotEmpty('username', $u->username);
        $unc = Doctrine_Query::create()->from('User')
            ->addWhere('username=?', $u->username)->addWhere('id!=?', $u->id)
            ->count();
        if ($unc>0) {
            $this->attachError('username', 'Username is already in use.');
        }

        // password checks
        if ($_REQUEST['password']!=$_REQUEST['pass2']) {
            $this->attachError('pass2', 'Password do not match original!');
        }
        if (!$u->id) {
            $validator->checkNotEmpty('password', $_REQUEST['password']);
        }

        // email related check
        $validator->checkNotEmpty('email', $u->email);
        $validator->checkEmail('email', $u->email);
        $emc = Doctrine_Query::create()->from('User')
            ->addWhere('email=?', $u->email)->addWhere('id!=?', $u->id)
            ->count();
        if ($emc>0) {
            $this->attachError('email', 'Email is already in use.');
        }
    }

    protected function action() {
        $u = $this->getObject();
        if (!empty($_REQUEST['password'])) {
            $u->password = md5($_REQUEST['password']);
        }
        $u->save();
        MvcSkel_Helper_Auth::clearCurrentUser();
    }
}
?>
