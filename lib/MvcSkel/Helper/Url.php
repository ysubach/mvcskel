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
        $url = strtolower($url);
        $config = MvcSkel_Helper_Config::read();
        if ($params == null) {
            $params = array();
        }

        if (isset($config['nice-urls']) && $config['nice-urls'] == true) {
            /*
             * Search engine friendly URLs like /Main/Index/p1/a
             */
            foreach ($params as $k => $v) {
                $url .= "/$k/" . urlencode($v);
            }
            if (!empty($anchor)) {
                $url .= "#$anchor";
            }

            if (substr($url, 0, 1) != '/') {
                // add root path
                $config = MvcSkel_Helper_Config::read();
                return $config['root'] . $url;
            } else {
                // url already has root
                return $url;
            }
        } else {
            /*
             * Usual URLs like /Main/Index?p1=a
             */
            if (substr($url, 0, 1) != '/') {
                $params['mvcskel_redirect_url'] = $url;
                $config = MvcSkel_Helper_Config::read();
                $url = $config['root'];
            }
        }

        // Return result
        $urlObj = new MvcSkel_Url($url);
        $urlObj->setUseHtmlEntities(false);
        $urlObj->addVars($params);
        if ($anchor != '') {
            $urlObj->setAnchor($anchor);
        }
        return $urlObj->construct();
    }

    /**
     * Redirect tool.
     * Usage:
     * <code>
     *    // make a redirect
     *    MvcSkel_Helper_Url::redirect('Main/Index');
     *  </code>
     * @param string $url controller/action format path
     * @param array  $params query values ('p1'=>'a', 'p2'=>'1'), null by default
     * @return void, it actually stop flow doing exit().
     */
    public static function redirect($url, $params=null) {
        header('Location: ' . MvcSkel_Helper_Url::url($url, $params));
        session_write_close();
        exit();
    }

    /**
     * The full url generator.
     * Similar to url() method in parameters.
     */
    public static function absoluteUrl($url, $params = null, $anchor = '') {
        $prefix = 'http://';
        if (!empty($_SERVER['HTTPS'])) {
            $prefix = 'https://';
        }
        return $prefix . $_SERVER['SERVER_NAME'] . self::url($url, $params, $anchor);
    }

    /**
     * Get variable from request (higher priority) 
     * or session (lower priority), and put it into session
     * for later usage in further requests.
     * @param string $varName Variable name
     * @param mixed $default Default variable value, used if it's
     *                   not found in request and session
     * @return mixed Current variable value
     */
    public static function getStickyVar($varName, $default) {
        $sessionKey = "{$_REQUEST['mvcskel_c']}_{$_REQUEST['mvcskel_a']}_$varName";
        $value = $default;
        if (isset($_REQUEST[$varName])) {
            $value = $_REQUEST[$varName];
        } else if (isset($_SESSION[$sessionKey])) {
            $value = $_SESSION[$sessionKey];
        }

        $_SESSION[$sessionKey] = $value;
        return $value;
    }

}

?>
