<?php
/**
* Access action checker.
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
* Class for checking access to action
* @package    MvcSkel
*/
class MvcSkel_Accessable_Action extends MvcSkel_Accessable {
    /** Name of checking view */
    var $actionName;
    
    /**
     * Constructor
     * @param $actionName name of checking action
     */
    function MvcSkel_Accessable_Action($actionName) {
        $this->actionName = $actionName;
    }
    
    function &getAccessVector() {
        $actionACL = 'Denied';
        // get action acl
        $access = &PEAR::getStaticProperty('MvcSkel', 'access');
        foreach ($access['ACL']['action'] as $item) {
            if ($this->correspondsAccessVector($item['@']['name'], $this->actionName)) {
                $actionACL = $item['allow'];
                break;
            }
        }

        if (!is_array($actionACL)) {
            $actionACL = array($actionACL);
        }
        return $actionACL;
    }
}
