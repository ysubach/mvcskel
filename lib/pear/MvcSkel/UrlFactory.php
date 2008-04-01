<?php
/**
* File contains classes to easy build links (URLs).
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
* Class describes interface
* @package    MvcSkel
*/
class MvcSkel_UrlFactory {
    /**
     * Constructs url object and returns it
     */
    function &makeUrl($name) {
    }

    /**
     * Callback function for smarty (for simple cases), must return url in string representation
     *
     ********************** For complex cases *************************
     * $smarty should contain reference on parent view object, it will dial
     * with all problems!!!
     */
    function &_smartyMakeUrl($params, &$smarty) {
        $res = $this->makeUrl($params['_name']);
        unset($params['_name']);
        if (isset($params['_anchor'])) {
            $res->setAnchor($params['_anchor']);
            unset($params['_anchor']);
        }
        $res->addVars($params);
        return $res->construct();
    }
}
