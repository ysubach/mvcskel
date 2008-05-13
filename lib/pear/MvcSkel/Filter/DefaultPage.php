<?php
/**
* Index file.
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
* Include filter.
*/
require_once 'MvcSkel/Filter.php';

/**
* Define default page (controller and action)
* if the request do not contain necessary vars.
* @category   framework
* @package    MvcSkel
* @subpackage    Filter
*/ 
class MvcSkel_Filter_DefaultPage extends MvcSkel_Filter {
	private $controller;
	private $action;
	
	/**
	* C-r.
	* @param string $controller default controller name
	* @param string $action default action name
	*/
	public function __construct($controller, $action) {
		$this->controller = $controller;
		$this->action = $action;
	}
	
	/**
	* Add a and c vars to request if necessary.
	*/
	public function filter() {
		if (!isset($_REQUEST['c']) || empty($_REQUEST['c'])) {
			$_REQUEST['c'] = $this->controller;
		}
		if (!isset($_REQUEST['a']) || empty($_REQUEST['a'])) {
			$_REQUEST['a'] = $this->action;
		}
		return true;
	}
}
?>
