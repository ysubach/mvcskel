<?php
/**
 * MvcSkel request strip slashes.
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
 * Strip slashes from request if
 * magic_quotes_gpc is set to 1
 * Most of modern servers has it option to 0, but some servers could
 * cause problem. If you have a possiblity just disable quoting via .htaccess
 * and do not use this filter.
 * @category   framework
 * @package    MvcSkel
 * @subpackage    Filter
 */
class MvcSkel_Filter_Stripslashes extends MvcSkel_Filter {
    private $vars;

    /**
     * C-r.
     * Usage:
     * give request variable names to strip slashes
     * @param string | array $vars what vars to strip from slashes
     */
    public function __construct($vars) {
        if (!is_array($vars)) {
            $vars = array($vars);
        }
        $this->vars = $vars;
    }

    /**
     * Check magic_quotes_gpc value and strip splashes from given variable
     * if necessary.
     */
    public function filter() {
        if (!get_magic_quotes_gpc()) {
            // the slashes was not added to this request
            // nothing to do
            return true;
        }

        foreach ($this->vars as $v) {
            if (isset($_REQUEST[$v])) {
                $_REQUEST[$v] = stripslashes($_REQUEST[$v]);
            }
        }

        return true;
    }
}
?>
