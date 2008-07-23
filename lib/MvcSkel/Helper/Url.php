<?php
/**
* MvcSkel url helper.
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
* Include PEAR Log library.
*/
require_once 'Log.php';

/**
* Config helper is required to read Logger setup.
*/
require_once 'MvcSkel/Helper/Config.php';

/**
* Url helper. 
* 
* Builds urls.
* 
* @package    MvcSkel
* @subpackage    Helper
*/
class MvcSkel_Helper_Url {
    /**
    * Url builder.
    * Usage:
    *  <code>
    *    echo MvcSkel_Helper_Url::url('Main/Index', array('p1'=>'a'), 'anch');
    *    // displays:
    *    // /mvcskel/Main/Index/p1/a#anch
    *  </code>
    * @param string $url controller/action format path
    * @param array $array of query values ('p1'=>'a', 'p2'=>'1'), null by default
    * @param string $anchor anchor, empty by default
    * @return string target URL
    */
    public static function url($url, $params = null, $anchor = '') {
        if ($params==null) {
            $params = array();
        }
        
        foreach ($params as $k=>$v) {
            $url .= "/$k/".urlencode($v);
        }
        if (!empty($anchor)) {
            $url .= "#$anchor";
        }
        
        $config = MvcSkel_Helper_Config::read();
        return $config['root'] . $url;
    }
    
    /**
    * Redirect tool.
    * Usage:
    * <code>
    *    // make a redirect
    *    MvcSkel_Helper_Log::redirect('Main/Index');
    *  </code>
    * @param string $url controller/action format path
    * @return void, it actually stop flow doing exit().
    */
    public static function redirect($url) {
        header('Location: ' . MvcSkel_Helper_Url::url($url));
        exit();
    }
}
?>
