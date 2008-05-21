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

require_once 'MvcSkel/Filter.php';
require_once 'MvcSkel/Filter/Router.php';

/**
* Handle request with MvcSkel framework.
* 
* @category   framework
* @package    MvcSkel
*/ 
class MvcSkel_Runner {
    /**
    * Array of filters.
    */
    protected $filters = array();

    /**
     * Constructor. Add some default filters like routing.
     */
    public function __construct() {
        $this->addFilter(new MvcSkel_Filter_Router());
    }
    
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
     * Run framework. 
     * 
     * Get Controller name from request variable 'mvcskel_c',
     * and get Action name from request variable 'mvcskel_a'. 
     * So this names are reserved names. It also do output of the action 
     * return result.
     * 
     * Usage:
     * <code>
     * $mvcskel = new MvcSkel_Runner();
     * $mvcskel->run();
     * </code>
     */
    public function run() {
        // application scope filters
        if ($this->applyFilters()) {
            $controller = $_REQUEST['mvcskel_c'];
            $action = $_REQUEST['mvcskel_a'];

            require_once "Controller/{$controller}.php";

            $conName = "Controller_{$controller}";
            $conObj = new $conName();

            $actName = "action{$action}";
            // controller scope filters
            if ($conObj->applyFilters()) {
                echo $conObj->$actName();
            }
        }
    }
}
?>