<?php
/**
* Phrame plugin, view forwarding.
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
* Url Factory.
*/
require_once 'MvcSkel/UrlFactory/View.php';

/**
 * View forward plugin for the Phrame
 *
 * @param string $viewName name of the view
 * @return URL to required view, UrlConstructor object
 */
function Phrame_Forward_view($viewName) {
    $url =& MvcSkel_UrlFactory_View::makeUrl($viewName);
    return $url;
}

