<?php

/**
 * MvcSkel logger helper.
 *
 * PHP versions 5
 *
 * @category   framework
 * @package    MvcSkel
 * @copyright  2008, Whirix Ltd.
 * @license    http://www.gnu.org/licenses/lgpl.html GNU Lesser Public General License (LGPL).
 * @link       http://code.google.com/p/mvcskel/
 */
/**
 * Include PEAR Loger.
 * @link http://pear.php.net/package/Log
 */
require_once 'Log.php';

/**
 * Log helper.
 *
 * Just shorthand wrapper for PEAR::Log package.
 *  Usage:
 *  <code>
 *    $log = new MvcSkel_Helper_Log::get(__CLASS__);
 *    $log->debug('some test message');
 *  </code>
 *
 * @package    MvcSkel
 * @subpackage Helper
 */
class MvcSkel_Helper_Log {

    /**
     * Log instance getter.
     * Wrapper for Log::singleton method.
     * @param string $logId log messages id, default is 'MvcSkel'
     * @return PEAR::Log object instance
     */
    public static function get($logId = 'MvcSkel') {
        $config = MvcSkel_Helper_Config::read();
        return Log::singleton($config['logger']['handler'], $config['logger']['name'], $logId, array(), $config['logger']['level']);
    }

    /**
     * Send email message site admin (option 'email-admin' in config.yml). 
     * Very useful to be notifies about strange events on the site.
     *
     *  Usage 1:
     *  <code>
     *    MvcSkel_Helper_Log::emailAlert('Customer have purchsed the same ebook twice!');
     *  </code>
     *  Usage 2:
     *  <code>
     *    // $exception is Exception class instance
     *    MvcSkel_Helper_Log::emailAlert($exception, __FILE__, __LINE__);
     *  </code>
     *
     * @param mixed $message string or Exception object
     * @param string $file script filename where the alert was sent
     * @param string $line script line number where the alert was sent
     */
    public static function emailAlert($message, $file = '', $line = '') {
        if (is_subclass_of($message, 'Exception')) {
            $message = $message->getMessage() .
                    "\n\n" . $message->getTraceAsString() .
                    "\n\n" . $message->getFile() . ' line ' . $message->getLine();
            if (!empty($file) || !empty($line)) {
                $message .= "\n\nthrown at " . $file . ' line ' . $line;
            }
        }

        $config = MvcSkel_Helper_Config::read();
        mail($config['email-admin'], 'MvcSkel Alert', $message);
    }

}

?>
