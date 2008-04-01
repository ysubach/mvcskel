<?php
/**
* Interface class.
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
* Interface class, defines interface of object, access for that
* can be checked
* @package    MvcSkel
*/
class MvcSkel_Accessable {
    /**
     * Returns access vector for object
     * vector must contain allowed roles for access
     * @return array of roles
     */
    function &getAccessVector() {
        return array();                 // access not allowed
    }

    /**
     * Checks corresponds whether or not the View/Action to a rule
     *
     * @param $rule rule string
     * @param $name View/Action name string
     *
     * @return 1 if corresponds, 0 - if not corresponds
     */
    function correspondsAccessVector($rule, $name) {
        $rule = str_replace('/', '\/', $rule);
        $rule = str_replace('*', '.*', $rule);
        return preg_match('/^'.$rule.'$/', $name);
    }
}

