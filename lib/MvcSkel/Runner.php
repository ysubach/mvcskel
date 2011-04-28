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
        // register autoload function
        spl_autoload_register(array($this, 'autoload'));

        // add default filters
        $this->addFilter(new MvcSkel_Filter_Router());
        $this->addFilter(new MvcSkel_Filter_DoctrineInit());
        $this->addFilter(new MvcSkel_Filter_AutoLogin());
    }

    /**
     * Add filter for the framework. The filters applied here
     * will be executed for the application scope.
     * @param MvcSkel_Filter $filter filter object to add, empty parameter
     *   resets filters
     * @return void
     */
    public function addFilter(MvcSkel_Filter $filter = null) {
        if ($filter==null) {
            $this->filters = array();
        } else {
            $this->filters[] = $filter;
        }
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

            $conName = $this->getControllerClass($controller);
            $conObj = new $conName();

            $actName = "action{$action}";
            // controller scope filters
            if ($conObj->applyFilters()) {
                // Execute action
                echo $conObj->$actName();
            }
        }
    }
    
    /**
     * Autoloader function for MvcSkel framework and application classes.
     *
     * Class will be searched in set of directories including own
     * MvcSkel directory and specific application directory.
     *
     * @param string $className Name of class to be loaded
     */
    public function autoload($className) {
        // check for legal prefix
        if (substr($className, 0, 7)!='MvcSkel' &&
            substr($className, 0, 10)!='Controller' &&
            substr($className, 0, 6)!='Helper' &&
            substr($className, 0, 6)!='Filter'
        ) {
            // class is not related w/ MvcSkel
            return;
        }

        // define include paths to search for class
        static $includePaths = array('lib/MvcSkel/', 'app/');

        // construct full file name
        $fileName = str_replace('_', '/', $className).'.php';
        if (substr($fileName, 0, 7)=='MvcSkel') {
            // remove MvcSkel prefix, it exists in path
            $fileName = substr($fileName, 8, strlen($fileName)-8);
        }

        // search for file, include if found
        foreach ($includePaths as $path) {
            if (file_exists($path.$fileName)) {
                // simple search
                require_once $path.$fileName;
                return;
            } else if (substr($className, 0, 10)=='Controller') {
                // for controllers search ignoring case
                $fullpath = $path.$fileName;
                $dir = dirname($fullpath);
                $file = basename($fullpath);
                foreach (scandir($dir) as $scanFile) {
                    if (strtolower($file)==strtolower($scanFile)) {
                        require_once $dir . '/' . $scanFile;
                        return;
                    }
                }
            }
        }
    }

    /**
     * Get controller class name.
     *
     * Detects the class name defined: project related or framework related
     *
     * @param string $controller name of controller
     * @return string controller class name
     */
    private function getControllerClass($controller) {
        $className = "Controller_{$controller}";
        if (class_exists($className)) {
            return $className;
        }
        return "MvcSkel_{$className}";
    }
}
?>