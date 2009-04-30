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
* Include PEAR Log library.
*/
require_once 'Log.php';

/**
 * Log helper.
 *
 * Just shorthand wrapper for PEAR::Log package.
 *  Usage:
 *  <code>
 *    $log = new MvcSkel_Helper_Log::get();
 *    $log->debug('some test message');
 *  </code>
 *
 * @package    MvcSkel
 * @subpackage    Helper
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
        return Log::singleton($config['logger']['handler'],
            $config['logger']['name'],
            $logId, array(), $config['logger']['level']);
    }
}
?>
