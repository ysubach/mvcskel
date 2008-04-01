<?php
/**
* Phrame plugin, action forwarding.
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
* Phrame plugin, action forwarding.
*/
function Phrame_Forward_action($actionName) {
    $url =& MvcSkel_UrlFactory_Action::makeUrl($actionName);
    return $url;
}

