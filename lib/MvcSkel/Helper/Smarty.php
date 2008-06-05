<?php
/**
 * MvcSkel Smarty helper.
 * 
 * Read more about this awesome tool at
 * http://smarty.php.net/
 *
 * PHP versions 5
 *
 * @category   framework
 * @package    MvcSkel
 * @copyright  2008, Whirix Ltd.
 * @license    http://www.gnu.org/licenses/lgpl.html GNU Lesser Public General License (LGPL).
 * @link       http://code.google.com/p/mvcskel/
 */

require_once 'Smarty.class.php';
require_once 'MvcSkel/Helper/Config.php';
require_once 'MvcSkel/Helper/Url.php';
require_once 'MvcSkel/Helper/Auth.php';


/**
 * Smarty helper. Implements basic concept of templates rendering.
 * @package    MvcSkel
 * @subpackage    Helper
 */
class MvcSkel_Helper_Smarty extends Smarty {
    /**
     * C-r.
     * @param string $bodyTemplate template which is used for rendering 
     * of the page content
     */
    public function __construct($bodyTemplate) {
        $config = MvcSkel_Helper_Config::read();

        $this->compile_dir = $config['tmp_dir'] . '/templates_c';
        $this->assign('bodyTemplate', $bodyTemplate);
        $this->assign('auth', new MvcSkel_Helper_Auth());
        
        $this->register_function('url', 
            array('MvcSkel_Helper_Smarty', 'pluginUrl'));
    }

    /**
     * Short hand for fetching of master.tpl.
     * @return result of @see Smarty::fetch()
     */
    public function render() {
        return $this->fetch('master.html');
    }
    
    /**
     * Plugin "url" for Smarty, generates correct nicely formatted
     * MvcSkel URL. Example of smarty call:
     * <pre>{url to='Main/Hello' v=12 a='head2'}</pre>
     * Result returned by plugin:
     * <pre>/Main/Hello/v/12#head2</pre>
     * 
     * @param array $params Parameters passed to plugin, they are
     *    treated as request parameters with following exceptions:
     *    'to' - contains target controller and action, 'a' - contains
     *    HTML page anchor name.
     * @param Smarty $smarty Reference to smarty instance
     * @return string Formatted MvcSkel URL
     */
    public static function pluginUrl($params, &$smarty) {
        if (!isset($params['to'])) {
            throw new Exception("Parameter 'to' is required");
        }
        $url = $params['to'];
        $anchor = $params['a'];
        unset($params['to']);
        unset($params['a']);
        return MvcSkel_Helper_Url::url($url, $params, $anchor);
    }
}
?>
