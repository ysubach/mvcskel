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
        //$u->pass2 = $R['pass2'];
        $u->email = $R['email'];
        $u->fname = $R['fname'];
        $this->setObject($u);
    }

    protected function validate() {
        $u = $this->getObject();
        $validator = new MvcSkel_Helper_Validator($this);
        $validator->checkNotEmpty('username', $u->username);
        $validator->checkNotEmpty('password', $u->password);
        if ($u->password!='' && $u->password!=$_REQUEST['pass2']) {
            $this->attachError('pass2', 'Password do not match original!');
        }
        $validator->checkNotEmpty('email', $u->email);
        $validator->checkEmail('email', $u->email);
    }

    protected function action() {
        $u = $this->getObject();
        $u->password = md5($u->password);
        $u->save();
    }

    protected function render() {
        $smarty = $this->smarty;
        $smarty->assign('form', $this);
        $smarty->assign('object', $this->getObject());
        return $smarty->render();
    }
}
?>
