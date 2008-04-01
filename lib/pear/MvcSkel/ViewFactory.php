<?php
/**
* View factory class.
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
* View factory class
* @package    MvcSkel
*/
class MvcSkel_ViewFactory {
	/**
    * Factory function
    *
    * @param $viewPath path to view like '/module_name/view_name'
    * @return the view object
    */
	function &build($viewPath)	{
        $context = new MvcSkel_Context($viewPath);
        $viewFile = $context->getFileName('view');
        if (!file_exists($viewFile)) {
            trigger_error("View file '$viewFile' not found!", E_USER_ERROR);
        }

        require_once $viewFile;

        $viewClass = $context->getClassName('view');
        return new $viewClass();
	}
}
?>