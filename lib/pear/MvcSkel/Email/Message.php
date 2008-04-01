<?php
require_once 'Mail.php';
require_once 'Mail/mime.php';
require_once 'MvcSkel/Smarty.php';

/**
 * Email message class. 
 * Allows to construct new message using Smarty templates
 * and send it via PEAR class.
 * 
 * Usage example:
 * 
 * $msg = new MvcSkel_Email_Message($user->email);
 * $msg->setFrom('noreply@website.com');
 * $msg->setSubject('Your password for WebSite.com');
 * $msg->assign('login', $login);
 * $msg->assign('password', $password);
 * $msg->setTemplate('Mail/forgotPassword.tpl');
 * $msg->send();
 */
class MvcSkel_Email_Message {
	/** Message subject */
	var $_subject;
	
	/** Recipient address */
	var $_to;
	
	/** From address */
	var $_from;
	
	/** Template file name */
	var $_template;
	
	/** Line separator constant */
	var $_crlf = "\r\n";
	
	/** Smarty object */
	var $_smarty;
	
	/**
	 * Constructor
	 * 
	 * @param string $to Recipient address
	 */
	function MvcSkel_Email_Message($to) {
		$this->_to = $to;
		$this->_smarty = new MvcSkel_Smarty();
	}
	
	/**
	 * Send prepared message
	 */
	function send() {
        $mime = new Mail_mime($this->_crlf);
        $mime->setTXTBody($this->_smarty->fetch($this->_template));
        $body = $mime->get();
        $hdrs = $mime->headers(array(
        	'Subject' => $this->_subject,
        	'From' => $this->_from
        ));        
        $mail =& Mail::factory('mail');
        $mail->send($this->_to, $hdrs, $body);		
	}
	
	/**
	 * Set message subject
	 */
	function setSubject($subject) {
		$this->_subject = $subject;
	}
	
	/**
	 * Set "From:" address
	 */
	function setFrom($from) {
		$this->_from = $from;
	}
	
	/**
	 * Assign variable to message (Smarty)
	 */
	function assign($name, $value) {
		$this->_smarty->assign($name, $value);
	}
	
	/**
	 * Set smarty template
	 */
	function setTemplate($template) {
		$this->_template = $template;
	}
}

?>