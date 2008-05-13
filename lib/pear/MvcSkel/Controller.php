<?php
/**
* MvcSkel controller.
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
* Include base framework class to inherite some basic methods.
*/
require_once 'MvcSkel.php';

/**
* Controller base class.
* @category   framework
* @package    MvcSkel
* @subpackage    Controller
*/ 
abstract class MvcSkel_Controller extends MvcSkel {
	/**
	* Remove the method, it does not necessary here.
	*/
	final public function run() {}
}
?>
