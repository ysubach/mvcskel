<?php
/**
 * Phrame plugin paths
 */
$GLOBALS['MvcSkel_Phrame_PluginPaths'] = array('lib/plugins/Phrame/');

/**
 * Names of the loaded plugins
 */
$GLOBALS['MvcSkel_Phrame_Forward_LoadedPlugins'] = array();

/**
 * The ActionForward class represents a destination to which the
 * ActionController might be directed to forward or redirect to, as a result of
 * the processing activities of an Action class. Instances of this class may be
 * created dynamically as necessary, or configured in association with an
 * ActionMapping instance for named lookup of potentially multiple destinations
 * for a particular ActionMapping instance.
 *
 * An ActionForward has the following minimal set of properties. Additional
 * properties can be provided as needed by subclasses.
 * <ul>
 * <li><b>name</b> - Logical name by which this instance may be looked up in
 * relationship to a particular ActionMapping.</li>
 * <li><b>path</b> - The absolute or relative URI to which control should be
 * forwarded or redirected.</li>
 * <li><b>redirect</b> - Set to 1 if the ActionController should kill the
 * session and redirect on the URI. [0]</li>
 * </ul>
 */
class MvcSkel_Phrame_ActionForward {
	/** Logical name of the forward*/
	var $_name;
    
	/** Forward path, plugins can be used here */
	var $_path;

    /** Cached UrlConstructor object */
    var $_url;

    /** Construct plugin filename */
    function _getPluginFilename($plugin)
    { return 'forward.' . $plugin . '.php'; }

    /** Construct plugin function name */
    function _getPluginFunction($plugin)
    { return 'Phrame_Forward_' . $plugin; }

    /**
	 * Constructor.
	 *
	 * @param $name name of the forward, string
	 * @param $path forward path string
	 */
	function MvcSkel_Phrame_ActionForward($name, $path)
	{
		$this->setName($name);
		$this->setPath($path);
        $this->_url = null;
	}

    /**
     * Perform redirection according to specified path.
     */
    function redirect()
    {
        $url = $this->getUrl();
        $urlString = $url->construct();
        if ($urlString!='')
        {
            header("Location: ".$urlString);
        }
    }

    /**
     * Get current UrlConstructor object.
     *
     * It will be cached in the $this->_url variable. Next attempt to receive
     * URL give cached result. If no cached result URL generated according to
     * $this->_path variable:
     *   'http://...' - simple URL
     *   'phrame://...' - generate object using specific plugin
     *
     * @return current UrlConstructor object
     */
    function getUrl()
    {
        if ($this->_url!=null)
        {
            // Return cached URL
            return $this->_url;
        }

        // Generate new URL
        $url = null;
        if (substr($this->_path, 0, 7)=='http://')
        {
            // Absolute path
            $url = new UrlConstructor($this->_path);
        }
        else
        {
            // Building URL
            $url = $this->_buildUrl();
        }
        $this->_url = $url;
        return $url;
    }

    /**
     * Set (overwrite) current (cached) UrlConstructor object
     *
     * @param $url new UrlConstructor object
     */
    function setUrl($url)
    {
        $this->_url = $url;
    }

    /**
     * Build UrlConstructor object using plugin specified in $this->_path
     * variable.
     *
     * @return new UrlConstructor object
     */
    function _buildUrl()
    {
        $path = $this->_path;

        // Strip phrame:// prefix
        if (substr($path, 0, 9)=='phrame://')
        {
            $path = substr($path, 9);
        }

        // Parse path
        if (preg_match("/(.*)\((.*)\)/", $path, $matches))
        {
            $plugin = $matches[1];
            $param  = $matches[2];
            $this->_loadPlugin($plugin);
            $func = $this->_getPluginFunction($plugin);
            return $func($param);
        }
        else
        {
            trigger_error("Wrong forward path string '$path'", E_USER_ERROR);
        }
    }

    /**
     * Load forward plugin from plugin paths.
     * If plugin already loaded do nothing.
     *
     * @param $plugin string, required plugin name
     */
    function _loadPlugin($plugin)
    {
        $loaded =& $GLOBALS['MvcSkel_Phrame_Forward_LoadedPlugins'];
        $paths  =& $GLOBALS['MvcSkel_Phrame_PluginPaths'];

        if (isset($loaded[$plugin]))
        {
            // Already loaded
            return;
        }

        // Load plugin
        $isLoaded = false;
        $fileName = $this->_getPluginFilename($plugin);
        $function = $this->_getPluginFunction($plugin);
        foreach ($paths as $path)
        {
            $fullName = $path . $fileName;
            if (is_file($fullName))
            {
                require_once $fullName;
                if (!function_exists($function))
                {
                    trigger_error("Plugin file '$fullName' loaded but ".
                                  "required function '$function' not found", E_USER_ERROR);
                }
                $isLoaded = true;
            }
        }
        if (!$isLoaded)
        {
            trigger_error("Plugin file '$fileName' not found, ".
                          "plugin paths: ".implode(', ', $paths), E_USER_ERROR);
        }
        $loaded[$plugin] = true;
    }
    
	/** Get the name */
	function getName()
	{
		return $this->_name;
	}
    
	/** Set the name of the ActionForward */
	function setName($name)
	{
		$this->_name = $name;
	}
    
	/** Get the path of the ActionForward */
	function getPath()
	{
		return $this->_path;
	}
    
	/** Set the path of the ActionForward */
	function setPath($path)
	{
		$this->_path = $path;
	}
}
?>
