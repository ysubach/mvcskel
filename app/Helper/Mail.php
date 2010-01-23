<?php
/**
 * Mail rendering and delivery
 *
 * PHP versions 5
 *
 * @category   framework
 * @package    startup
 * @copyright  2009, Whirix Ltd.
 * @license    http://www.gnu.org/licenses/lgpl.html GNU Lesser Public General License (LGPL).
 * @link       http://code.google.com/p/mvcskel/
 */
class Helper_Mail {
    /**
     * Available email templates
     */
    protected static $emailTemplates = array(
            'forgot' => array(
                            'subject' => 'MvcSkel: New password created',
                            'filename' => 'forgotPass.tpl'
            ),
            'vote'=> array(
                            'subject' => 'MvcSkel: Vote Created',
                            'filename' => 'vote.tpl'
            ),
    );

    /**
     * Sends message from system to user
     * @param string $id Email template identifier
     * @param object $user Recipient user
     * @param array $data Additional data for body rendering
     */
    public static function systemMessage($id, $user, $data) {
        $config = MvcSkel_Helper_Config::read();
        $tpl = self::$emailTemplates[$id];

        // render body
        $smarty = new MvcSkel_Helper_Smarty('Mail/'.$tpl['filename'], true);
        $smarty->assign($data);
        $smarty->assign('user', $user);
        $body = $smarty->render();

        // message delivery
        $headers = "From: {$config['email-from']}";
        mail($user->email, $tpl['subject'], $body, $headers);

        $logger = MvcSkel_Helper_Log::get(__CLASS__);
        $logger->debug('headers: '.$headers);
        $logger->debug('email: '.$user->email);
        $logger->debug('subject: '.$tpl['subject']);
        $logger->debug('body: '.$body);
    }
}
?>
