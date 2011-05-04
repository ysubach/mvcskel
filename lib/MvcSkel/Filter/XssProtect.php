<?php
/**
 * MvcSkel XSS protection filter.
 *
 * PHP versions 5
 *
 * @category   framework
 * @package    MvcSkel
 * @copyright  2011, Whirix Ltd.
 * @license    http://www.gnu.org/licenses/lgpl.html GNU Lesser Public General License (LGPL).
 * @link       http://code.google.com/p/mvcskel/
 */

/**
 * Prevent XSS security problems by applying modifications
 * to incoming data.
 *
 * @category   framework
 * @package    MvcSkel
 * @subpackage    Filter
 */
class MvcSkel_Filter_XssProtect extends MvcSkel_Filter {
    /**
     * C-r
     */
    public function __construct() {}

    /**
     * Encode HTML entities
     */
    public function filter() {
        $this->protect('_REQUEST');
        $this->protect('_GET');
        $this->protect('_POST');
        return true;
    }

    /**
     * Encode HTML entities in global array,
     * additionally encode subarrays on the first level only
     */
    private function protect($n) {
        foreach ($GLOBALS[$n] as $i=>$v) {
            if (is_numeric($v)) {
                // nothing for numeric
                continue;
            } else if (is_array($v)) {
                // array
                $GLOBALS[$n][$i] = $this->protectArray($v);
            } else {
                // string encode
                $GLOBALS[$n][$i] = $this->stringTransform($v);
            }
        }
    }

    /**
     * Encode simple array
     * @param array $arr Source array
     * @return array Safe array
     */
    private function protectArray($arr) {
        $pa = array();
        foreach ($arr as $k => $v) {
            if (is_numeric($v)) {
                $pa[$k] = $v;
            } else if (is_array($v)) {
                $pa[$k] = $this->protectArray($v);
            } else {
                $pa[$k] = $this->stringTransform($v);
            }
        }
        return $pa;
    }

    /**
     * Transform string to safe form
     */
    private function stringTransform($w) {
        if (strstr($w, '<')!==false && strstr($w, '>')!==false) {
            $x = htmlentities($w);
            return $x;
        } else {
            return $w;
        }
    }
}
?>
