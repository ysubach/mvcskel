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

require_once 'Runner.php';

/**
* Controller base class.
* @category   framework
* @package    MvcSkel
* @subpackage    Controller
*/ 
abstract class MvcSkel_Controller extends MvcSkel_Runner {
    /**
     * Remove the method.
     * 
     * The method does not make sense in context of controller, so we
     * remove it from here to avoid a confusion. You could safely 
     * disregard this redefinition.
     */
    final public function run() {}
}
?>
