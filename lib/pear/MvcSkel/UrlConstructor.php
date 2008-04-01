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
* Class for url construction, helps to easy build links with params,
* also session handling variable added if cookies don't work
* @package    MvcSkel
*/
class MvcSkel_UrlConstructor {
	
	/** First part of url (link to script) */
    var $_url;
    
    /** Array of variables, which must be added to _url */
    var $_vars = array();
    
    /** Anchor name */
    var $_anchor;
    
    /**
     * Flag shows that we need to encode html entities in the result,
     * this is required by HTML validators, set 'true'. 
     * If we don't use URL in HTML then this encode cause errors, 
     * set 'false'. Default: 'true'.
     */
	var $_useHtmlEntities = true;
        
    /**
     * Constructor
     * Initializes object
     * @param $url first part of url (link to script, may be with    
     * variables on end)
     */
    function MvcSkel_UrlConstructor($url='') {
        if ($url=='') {
            return;
        }
        
        // Get query parts
        $tmp = parse_url($url);
        $queryLen = 0;
        if (isset($tmp['query'])) {
            // Query vars exists
            parse_str($tmp['query'], $this->_vars);
            $queryLen = strlen($tmp['query']);
        }

        // Get anchor
        $anchorLen = 0;
        if (isset($tmp['fragment'])) {
            $this->_anchor = $tmp['fragment'];
            $anchorLen = strlen($tmp['fragment']);
        }

        // Separate them from original URL
        $this->_url = $url;
        if ($queryLen>0) {
            $this->_url = substr($this->_url, 0,
                                 strlen($this->_url) - ($queryLen+1));
        }
        if ($anchorLen>0) {
            $this->_url = substr($this->_url, 0,
                                 strlen($this->_url) - ($anchorLen+1));
        }
    }

    /**
     * Set value of $_useHtmlEntities flag
     * @param boolean $v New value for _$useHtmlEntities 
     */
	function setUseHtmlEntities($v) {
		$this->_useHtmlEntities = $v;
	}
    
    /**
     * @return copy of this object
     */
    function __copy() {
        $res = new MvcSkel_UrlConstructor();
        $res->_url = $this->_url;
        $res->_vars = $this->_vars;
        $res->_anchor = $this->_anchor;
        return $res;
    }

    
    /**
     * Add variables, which must be sent throuth url
     * @param $vars array (hash), which contains (variable name=> value)
     * pairs. Repeated variables are replaced.
     * For example:
     * addVars ( array("var1"=>"value1", "var1"=>"value1" )
     * will add variables var1 and var2 with values value1 and value2
     */
    function addVars($vars) {
        foreach ($vars as $key=>$value) {
            $this->addVar($key, $value);
        }
    }

    /**
     * Add variable, which must be sent throuth url
     * @param $varName name of adding variable
     * @param $value value of adding variable
     * Repeated variables are replaced.
     */
    function addVar($varName, $value) {
        $this->_vars[$varName]=$value;
    }

    /**
     * Delete variable, which must be sent throuth url
     * @param $varName name of deleting variable
     */
    function delVar($varName) {
        unset($this->_vars[$varName]);
    }

    /**
     * Constructs result url and returns it
     * @return result url (string)
     */
    function construct() {
        reset($this->_vars);

        $result_url=$this->_url;
        $pair=each($this->_vars);
        if ($pair) {
            $result_url.= '?'.$pair[0]."=".rawurlencode( $pair[1] );
        }

        while ($pair=each($this->_vars)) {
            $result_url.='&'.$pair[0]."=".rawurlencode( $pair[1] );
        }

        if (isset($this->_anchor)) {
            $result_url .= '#'.$this->_anchor;
        }
        
        if ($this->_useHtmlEntities) {
        	return htmlentities($result_url);
        } else {
        	return $result_url;
        }
    }

    /**
     * Add anchor to url
     * @param $ancName name of adding anchor
     */
    function setAnchor($ancName) {
        $this->_anchor = $ancName;
    }

    /**
     * Deletes anchor, if no call of setAnchor occured after this function call
     * no anchor added to url
     */
    function delAnchor() {
        unset($this->_anchor);
    }

    /**
     * Constructs result url and prints it
     */
    function purl () {
        print $this->Construct();
    }
}
