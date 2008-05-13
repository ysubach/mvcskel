<?php
/**
* MvcSkel runner.
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
* Include base filter.
*/
require_once 'MvcSkel/Filter.php';

/**
* Handle request with MvcSkel framework.
* @category   framework
* @package    MvcSkel
*/ 
class MvcSkel {
	/**
	* Array of filters.
	*/
	protected $filters = array();
	
	/**
	* Add filter for the framework. The filters applied here
	* will be executed for the application scope.
	* @param MvcSkel_Filter $filter filter object to add
	* @return void
	*/
	public function addFilter(MvcSkel_Filter $filter) {
		$this->filters[] = $filter;
	}
	
	/**
	* Execute all the filters.
	* Stop the excution if one of them return false.
	* @return boolean false if one of the filters returned false, true if all
	* filters are executed ok.
	*/
	public function applyFilters() {
		foreach ($this->filters as $filter) {
			if (!$filter->filter()) {
				return false;
			}
		}
		return true;
	}
	
	/**
	* Run framework. Get Controller name from request variable 'c',
	* and get Action name from request variable 'a'. So this names
	* are reserved names. It also do output of the action return result.
	*/
	public function run() {
		// application scope filters
		if ($this->applyFilters()) {
			$controller = $_REQUEST['c'];
			$action = $_REQUEST['a'];
			
			require_once "Controller/{$controller}.php";
			
			$conName = "Controller_{$controller}";
			if (!class_exists($conName)) {
				trigger_error('Can not find controller class definition.');
			}
			$conObj = new $conName();
			
			$actName = "action{$action}";
			if (!method_exists($conObj, $actName)) {
				trigger_error('Can not find controller method.');
			}
			// controller scope filters
			if ($conObj->applyFilters()) {
				echo $conObj->$actName();
			}
		}
	}
}
?>