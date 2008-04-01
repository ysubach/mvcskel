<?php
/**
* View access controller.
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
* Require base class.
*/
require_once 'MvcSkel/Accessable.php';

/**
* Class for checking access to view
* @package    MvcSkel
*/
class MvcSkel_Accessable_View extends MvcSkel_Accessable {
    /** Name of checking view */
    var $viewName;

    /**
     * Constructor
     * @param $viewName name of checking view
     */
    function MvcSkel_Accessable_View ($viewName) {
        $this->viewName = $viewName;
    }

    function &getAccessVector() {
        $viewACL = 'Denied';
        // get view acl
        $access = &PEAR::getStaticProperty('MvcSkel', 'access');
        foreach ($access['ACL']['view'] as $item) {
            if ($this->correspondsAccessVector($item['@']['name'], $this->viewName)) {
                $viewACL = $item['allow'];
                break;
            }
        }

        if (!is_array($viewACL)) {
            $viewACL = array($viewACL);
        }
        return $viewACL;
    }
}
