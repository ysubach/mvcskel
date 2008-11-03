<?php
/**
* MvcSkel request parser filter.
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
 * Performs routing from nicely formatted URLs into standard MvcSkel request
 * representation. Example "controller/action/v1/s1/v2/s2/..." is mapped
 * into:
 * <ul>
 * <li>$_REQUEST['mvcskel_c'] = controller</li>
 * <li>$_REQUEST['mvcskel_a'] = action</li>
 * <li>$_REQUEST[v1] = s1</li>
 * <li>etc.</li>
 * <ul>
 * Only controller part is necessary, action and variables can be dropped.
 * @category   framework
 * @package    MvcSkel
 * @subpackage Filter
 */ 
class MvcSkel_Filter_Router extends MvcSkel_Filter {
    /**
     * Perform routing.
     */
    public function filter() {
        if (!isset($_REQUEST['mvcskel_redirect_url'])) {
            return true;
        }
        $parts = explode('/', $_REQUEST['mvcskel_redirect_url']);
        if (isset($parts[0]) && $parts[0]!='') {
            // controller
            $_REQUEST['mvcskel_c'] = $parts[0];
        }
        if (isset($parts[1])) {
            // action
            $_REQUEST['mvcskel_a'] = $parts[1];
        }
        if (count($parts)>=4) {
            // vars processing
            for ($i=2; ($i+1)<count($parts); $i+=2) {
                $_REQUEST[$parts[$i]] = urldecode($parts[$i+1]);
            }
        }
        return true;
    }
}
?>
