<?php
/**
 * MvcSkel Smarty helper.
 *
 * Read more about this awesome tool at
 * @link http://smarty.php.net/
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
 * Use Smarty as template engine.
 */
require_once 'Smarty.class.php';

/**
 * Smarty helper. Implements basic concept of templates rendering.
 * @package    MvcSkel
 * @subpackage    Helper
 */
class MvcSkel_Helper_Smarty extends Smarty {
/** Currently rendered file */
    protected $bodyTemplate;

    /**
     * Flag shows that Smarty instance will be used for AJAX rendering.
     * This mean template is rendered without master.tpl.
     */
    protected $forAjax;

    /**
     * Master template.
     * @var string path to master template
     */
    protected $masterTemplate = 'master.tpl';

    /**
     * Constructor.
     *
     * Special class Helper_SmartyAssigner is used for assigning of
     * project specific variables to Smarty instance ($this). It have to
     * implement single public function assignCommon($smarty), parameter
     * $smarty is reference to current Smarty instance. Class example:
     * <pre>
     * //
     * // Smarty assigner, used to assign common variables before
     * // template processing happen
     * //
     * class Helper_SmartyAssigner {
     *
     *     public function assignCommon($smarty) {
     *         $smarty->assign('aaa', 1);
     *     }
     * }
     * </pre>
     *
     * @param string $bodyTemplate template which is used for rendering
     * @param boolean $forAjax AJAX rendering flag, default false
     * of the page content
     */
    public function __construct($bodyTemplate, $forAjax=false) {
        parent::Smarty();
        $this->bodyTemplate = $bodyTemplate;
        $this->forAjax = $forAjax;

        $config = MvcSkel_Helper_Config::read();
        $this->compile_dir = $config['tmp_dir'] . '/templates_c';

        // assign common variables
        $this->assign('bodyTemplate', $bodyTemplate);
        $this->assign('auth', new MvcSkel_Helper_Auth());
        $this->assign('root', $config['root']);

        // assign variables from external class Helper_SmartyAssigner
        if (class_exists('Helper_SmartyAssigner')) {
            $sa = new Helper_SmartyAssigner();
            $sa->assignCommon($this);
        }

        // add MvcSkel specific plugin(s)
        $this->register_function('url',
            array('MvcSkel_Helper_Smarty', 'pluginUrl'));
    }

    /**
     * Short hand for fetching of master.tpl.
     * @return result of @see Smarty::fetch()
     */
    public function render() {
        if (!$this->forAjax) {
            return $this->fetch($this->masterTemplate);
        } else {
            return $this->fetch($this->bodyTemplate);
        }
    }

    /**
     * Set master template. This call should be used to
     * change master template value from 'master.tpl' (which is default value)
     * to something other.
     * Usage:
     * <code>
     * $smarty = new MvcSkel_Helper_Smarty('index.tpl');
     * $smarty->setLayout('masterForAdmin.tpl');
     * </code>
     * Example above give you a possiblity to use another page
     * layout for admin panel of your applicarion.
     * @param string $masterTemplate new master template name
     */
    public function setLayout($masterTemplate) {
        $this->masterTemplate = $masterTemplate;
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
     *    'to' - contains target controller and action,
     *    'a' - contains HTML page anchor name,
     *    'cc' - flag for copying current request before building new URL,
     *        this include controller/action name and request parameters;
     *        if this flag set, then parameter 'to' is not required.
     * @param Smarty $smarty Reference to smarty instance
     * @return string Formatted MvcSkel URL
     */
    public static function pluginUrl($params, &$smarty) {
    // handling 'cc' or 'to'
        if (isset($params['to'])) {
            $url = $params['to'];
            unset($params['to']);
        } else if (isset($params['cc'])) {
                $url = $_REQUEST['mvcskel_c'].'/'.$_REQUEST['mvcskel_a'];
                foreach ($_GET as $k=>$v) {
                    if (substr($k, 0, 7)!='mvcskel' && !isset($params[$k])) {
                        $params[$k] = $v;
                    }
                }
                unset($params['cc']);
            } else {
                throw new Exception("Parameter 'to' is required");
            }

        // handling 'a'
        $anchor = $params['a'];
        unset($params['a']);
        // build URL
        return MvcSkel_Helper_Url::url($url, $params, $anchor);
    }

    /**
     * Extract date string in ISO format from request fields.
     * Create for work with {html_select_date} - standard Smarty custom function.
     * @param string $prefix Prefix used in template in {html_select_date}
     */
    public static function extractDate($prefix) {
        return $_REQUEST[$prefix.'Year'].'-'.
            $_REQUEST[$prefix.'Month'].'-'.$_REQUEST[$prefix.'Day'];
    }

    /**
     * Extract time string in ISO format from request fields.
     * Create for work with {html_select_time} - standard Smarty custom function.
     * @param string $prefix Prefix used in template in {html_select_time}
     */
    public static function extractTime($prefix) {
        $h = $_REQUEST[$prefix.'Hour'];
        $m = $_REQUEST[$prefix.'Minute'];
        if (isset($_REQUEST[$prefix.'Meridian']) &&
            $_REQUEST[$prefix.'Meridian']=='pm') {
            $h += 12;
        }
        $time = $h.':'.$m;
        if (isset($_REQUEST[$prefix.'Second'])) {
            $time = $time.':'.$_REQUEST[$prefix.'Second'];
        }
        return $time;
    }
}
?>
