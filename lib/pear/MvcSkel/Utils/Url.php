<?php
/**
* Some url functions.
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
* Common url-related functions namespace.
* @package    MvcSkel
*/
class MvcSkel_Utils_Url {
    /**
    * Get root URL.
    * @static
    * @access public
    */
    function getRootURL() {
        $options = &PEAR::getStaticProperty('MvcSkel', 'options');
        return "http://{$_SERVER['HTTP_HOST']}/{$options['Links']['root']}";    
    }
    
    /**
    * Function for redirect.
    *
    * Function redirect browser to another URL and exit current flow.
    *
    * @param string $url redirect to this URL.
    */
    function redirect($url) {
        header("Location: $url");
        exit();
    }
}
