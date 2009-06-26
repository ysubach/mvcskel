<?php

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
        $u->save();

        // clear image for next sigup
        MvcSkel_Helper_Captcha::init(true);

        $auth = new MvcSkel_Helper_Auth();
        $auth->start();
        $auth->setAuth($u->username);
    }
}
?>
