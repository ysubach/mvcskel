<?php
/**
* Logger.
*
* PHP versions 4 and 5
*
* @category   framework
* @package    MvcSkel
* @copyright  2007, Whirix Ltd.
* @license    http://www.gnu.org/licenses/lgpl.html GNU Lesser Public General License (LGPL).
* @link       http://trac.whirix.com/mvcskel
*/

/**
* Include pear log.
*/
require_once 'Log.php';

/**
 * Log objects factory.
 */
class MvcSkel_Logger {
    /**
     * Get Logger instance.
     * example of using in class context is
     *
     * $this->logger =& MvcSkel_Logger::getInstance(get_class($this))
     *
     * @param $ident is ident of log (see PEAR::Log docs)
     * @return log instance with given name
     */
    function getInstance($ident) {
        $options = &PEAR::getStaticProperty('MvcSkel', 'options');
        return Log::singleton($options['logger']['handler'],
                              $options['logger']['name'],
                              $ident,
                              array(),
                              $options['logger']['level']);
    }
}
?>
