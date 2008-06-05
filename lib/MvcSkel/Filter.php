<?php
/**
* MvcSkel filter.
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
* Do some manupulations with global vars like request, etc.
* Most of Controller related operations cound achieved by 
* filter creating. For example authentication, IP-address
* restrictions, form validations, SEO URLs parsing, etc.
* @category   framework
* @package    MvcSkel
* @subpackage    Filter
*/ 
abstract class MvcSkel_Filter {
	/**
	* The core filter function. Must be implemented
	* by inherited classes.
	* @return boolean, return false if the flow have to stopped, 
	* true otherwise.
	*/
	abstract public function filter();
}
?>
