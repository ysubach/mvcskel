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
* Require base class.
*/
require_once 'MvcSkel/UrlFactory.php';

/**
* Include UrlConstructor.
*/
require_once 'MvcSkel/UrlConstructor.php';

/**
* Include url utils.
*/
require_once 'MvcSkel/Utils/Url.php';

/**
* Class implenets interface UrlFactory
* @package    MvcSkel
*/
class MvcSkel_UrlFactory_View extends MvcSkel_UrlFactory {
    /**
     * Constructs view url object and returns it
     * @param $viewName name of view link to that is constructed
     * @return constructed object (UrlConstructor)
     */
    function &makeUrl($viewName) {
        $res = &new MvcSkel_UrlConstructor(MvcSkel_Utils_Url::getRootURL() . 'index.php');
        $res->addVar('view', $viewName);
        return $res;
    }
}
