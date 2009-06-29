<?php
/**
* MvcSkel auto login filter.
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
 * Checks for necessary cookie and try to set found member authenticated.
 * @category   framework
 * @package    MvcSkel
 * @subpackage    Filter
 */
class MvcSkel_Filter_AutoLogin extends MvcSkel_Filter {
    /**
     * Try to start .
     */
    public function filter() {
        $auth = new MvcSkel_Helper_Auth();
        $auth->start();
        
        // try to auto login
        if (!$auth->checkAuth()) {
            $auth->autoLoginGet();
        }

        return true;
    }
}
?>
