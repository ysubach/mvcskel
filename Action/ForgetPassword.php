<?php
/**
 * Send forget password.
 * 
 * @package action
 * @subpackage toppages
 */

/** 
 * Include Action.
 */
require_once 'MvcSkel/Phrame/Action.php';

/**
 * 
 */ 
require_once 'Text/Password.php';
require_once 'Mail.php';
require_once 'Mail/mime.php';
require_once 'MvcSkel/Smarty.php';

/**
 * Class that performs ForgetPassword action by changing password an send new
 * one on email
 * 
 * @package action
 * @subpackage toppages
 */
class Action_ForgetPassword extends MvcSkel_Phrame_Action {    
    /**
     * @see Action::perform()
     */
    function perform($actionMapping, $actionForm) {
        $password = $this->generatePassword();

        // write new password
        $actionForm->user->password = md5($password);
        $actionForm->user->update();

        // send email
        $crlf = "\r\n";
        $hdrs = array('Subject' => 'Your password on PHP Skel',
                      'From' => 'admin@phpskel.whx');
        $mime = new Mail_mime($crlf);

        $smarty = new PhpSkel_Smarty();
        $smarty->assign('login', $actionForm->user->login);
        $smarty->assign('password', $password);
        $mime->setTXTBody($smarty->fetch('Mail/forgetPassword.tpl'));
        
        $body = $mime->get();
        $hdrs = $mime->headers($hdrs);
        
        $mail =& Mail::factory('mail');
        $mail->send($actionForm->user->email, $hdrs, $body);

        $_SESSION['FORGET_PASSWORD_USER'] = $actionForm->user;
        
        return $actionMapping->get('sent');
    }

    function generatePassword() {
        return Text_Password::create(9, 'unpronounceable', 'alphanumeric');
    }
}
?>