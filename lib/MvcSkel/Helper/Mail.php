<?php

/**
 * Mail rendering and delivery. Please see
 * configuration file app/mail.yml
 *
 * PHP versions 5
 *
 * @category   framework
 * @package    startup
 * @copyright  2009, Whirix Ltd.
 * @license    http://www.gnu.org/licenses/lgpl.html GNU Lesser Public General License (LGPL).
 * @link       http://code.google.com/p/mvcskel/
 */
class MvcSkel_Helper_Mail {

    /**
     * Sends message from system to user
     * @param string $id Email template identifier
     * @param object $user Recipient user
     * @param array $data Additional data for body rendering
     */
    public static function systemMessage($id, $user, $data) {
        $mailConfig = MvcSkel_Helper_Config::read('app/mail.yml');
        $tpl = $mailConfig[$id];

        // render body
        $smarty = new MvcSkel_Helper_Smarty('Mail/' . $tpl['filename'], true);
        $smarty->enableEmailMode();
        $smarty->assign($data);
        $smarty->assign('user', $user);
        $body = $smarty->render();

        // message delivery
        $headers = "From: {$mailConfig['config']['from']}";
        if (!empty($data['html'])) {
            $headers .= "Mime-Version: 1.0\r\n";
            $headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
        }

        /* if (!empty($data['attachment'])) {
          $filename = $data['attachment'];

          $f = fopen($filename, "rb");
          $un = strtoupper(uniqid(time()));
          $headers .= "Mime-Version: 1.0\n";
          $headers .= "Content-Type:multipart/mixed;";
          $headers .= "boundary=\"----------" . $un . "\"\n\n";
          $zag = "------------" . $un . "\nContent-Type:text/html;\n";
          $zag .= "Content-Transfer-Encoding: 8bit\n\n$body\n\n";
          $zag .= "------------" . $un . "\n";
          $zag .= "Content-Type: application/octet-stream;";
          $zag .= "name=\"" . basename($filename) . "\"\n";
          $zag .= "Content-Transfer-Encoding:base64\n";
          $zag .= "Content-Disposition:attachment;";
          $zag .= "filename=\"" . basename($filename) . "\"\n\n";
          $zag .= chunk_split(base64_encode(fread($f, filesize($filename)))) . "\n";

          $body = $zag;
          } */

        mail($user->email, $tpl['subject'], $body, $headers);

        $logger = MvcSkel_Helper_Log::get(__CLASS__);
        $logger->debug('headers: ' . $headers);
        $logger->debug('email: ' . $user->email);
        $logger->debug('subject: ' . $tpl['subject']);
        $logger->debug('body: ' . $body);
    }

}

?>
