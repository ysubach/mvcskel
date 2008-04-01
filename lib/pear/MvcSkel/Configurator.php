<?php
/**
* MvcSkel configurator.
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
* Include base pear class.
*/
require_once 'PEAR.php';

/**
* Include pear config.
*/
require_once 'Config.php';

/**
* Include pear cache.
*/
require_once 'Cache/Lite.php';

/**
* Authentication class
*/
require_once 'MvcSkel/Auth.php';


/**
* Setup all the inveronment for MvcSkel. Uses PEAR's options.
* @category   framework
* @package    MvcSkel
*/ 
class MvcSkel_Configurator {
    /**
    * Setup all environment variables.
    * @param  string $root project directory, all configs will be found here
    * @param  string $tmp (full) path to tmp folder
    * @access public
    * @static
    */	
    function configure($root, $tmp) {
        $options = &PEAR::getStaticProperty('MvcSkel', 'options');
        $options['tempDir'] = realpath($tmp) . DIRECTORY_SEPARATOR;
        $options['commonPath'] = realpath($root) . DIRECTORY_SEPARATOR;
        
        MvcSkel_Configurator::_setupPHP();
        MvcSkel_Configurator::_readConfig('etc/config.xml');
        MvcSkel_Configurator::_readACL('etc/acl.xml');
        MvcSkel_Configurator::_readMappings('etc/mappings.xml');       
        MvcSkel_Configurator::_setupDBDataObject();        
        MvcSkel_Configurator::_requireModels();
    }

    /**
    * Start authentication.
    * @access public
    * @static
    */
    function startAuth() {
        require_once 'Auth.php';
        $auth = &PEAR::getStaticProperty('MvcSkel', 'auth');
        $auth = new MvcSkel_Auth();
        $auth->start();
        $auth->getAuth();
    }

    /**
    * Read config.xml
    * @param  string $config path to config.xml
    * @access protected
    * @static
    */
    function _readConfig($config) {
        $options = &PEAR::getStaticProperty('MvcSkel', 'options');
        $conf = MvcSkel_Configurator::__readCachedXML($options['commonPath'] . $config);
        $options = array_merge($options, $conf['root']['configuration']);        
    }

    /**
    * Read acl.xml
    * @param  string $acl path to acl.xml
    * @access protected
    * @static
    */
    function _readACL($acl) {
        $opt = &PEAR::getStaticProperty('MvcSkel', 'options');
        $access = &PEAR::getStaticProperty('MvcSkel', 'access');
        $tmp = MvcSkel_Configurator::__readCachedXML($opt['commonPath'] . $acl);
        $access = $tmp['root']['access'];
    }

    /**
    * Read acl.xml
    * @param  string $acl path to acl.xml
    * @access protected
    * @static
    */
    function _readMappings($mappings) {
        $opt = &PEAR::getStaticProperty('MvcSkel', 'options');
        $map = &PEAR::getStaticProperty('MvcSkel', 'mappings');
        $tmp = MvcSkel_Configurator::__readCachedXML($opt['commonPath'] . $mappings);
        $map = MvcSkel_Configurator::__translateMappings($tmp['root']);
    }
    
    /**
    * Setup PHP, collection of ini_set() calls.
    * @param  string $root project directory
    * @access protected
    * @static
    */
    function _setupPHP() {
        // setting runtime PHP vars
        ini_set('register_globals',     '0');
        ini_set('magic_quotes_gpc',     '0');
        ini_set('magic_quotes_runtime', '0');
    }
    
    /**
    * Inclusion of models.
    * @access protected
    * @static
    */
    function _requireModels() {
        $options = &PEAR::getStaticProperty('MvcSkel', 'options');
        $modelsPath = $options['commonPath'] . '/Model/';
        if ($dir = opendir($modelsPath)) {
            while ($file = readdir($dir)) {
                if (substr($file, -4)=='.php') {
                    require_once $modelsPath . $file;
                }
            }
        }
    }
    
    /**
    * Setup DB_DataObject.
    */
    function _setupDBDataObject() {
        define('DB_DATAOBJECT_NO_OVERLOAD', true);
        $options = &PEAR::getStaticProperty('MvcSkel', 'options');
        $dbOption = &PEAR::getStaticProperty('DB_DataObject', 'options');
        
        // absolute path for schema bacause it reads by parse_ini_file function
        $dbOption = $options['DB_DataObject'];
        $dbOption['class_location'] = $options['commonPath'] . $options['DB_DataObject']['class_location'];
        $dbOption['schema_location'] = $dbOption['class_location'];
        unset($options['DB_DataObject']);
    }
    
    /**
    * Read cached XML
    * @param  string $file name of file
    * @return array|mixed structure of parsed file
    * @access private
    * @static
    */
    function __readCachedXML($file) {
        $options = &PEAR::getStaticProperty('MvcSkel', 'options');
        
        $cache = new Cache_Lite(array(
            'cacheDir'=>$options['tempDir'],
            'automaticSerialization'=>true,
            'lifeTime'=>null));
        
        $cacheId = filemtime($file) . '-' . $file;

        $res = $cache->get($cacheId);
        if ($res===false) {
            $config = new Config();
            $res = $config->parseConfig($file, 'xml');
            $res = $res->toArray();
            $cache->save($res, $cacheId);
        }
        
        return $res;
    }
    
    /**
    * Translates mapping to phrame format.
    * @param  array $config mappings array from config
    * @return HashMap mappings object
    * @access private
    * @static
    */
    function __translateMappings($config) {
        require_once 'Utils/HashMap.php';
        require_once 'Phrame/ActionMapping.php';
        require_once 'Phrame/ActionForward.php';

        /**
         * Converted data array
         */
        $mappings = new MvcSkel_Utils_HashMap();
        
        /**
         * To have one action is to have [action] as one action, not
         * to array of actions. Bad. Need to convert in array with
         * one include
         */
        if (isset($config['actions']['action']['@'])) {
            $tmp = $config['actions']['action'];
            $config['actions']['action'] = array($tmp);
        }

        /**
         * Move actions from XML to array of ActionMappings
         */
        foreach ($config['actions']['action'] as $action) {
            // Action name
            if (!isset($action['@']['name'])) {
                trigger_error("mappings: name attribute required for action");
            }
            $mapping = new MvcSkel_Phrame_ActionMapping($action['@']['name']);

            // Action form
            if (isset($action['@']['form'])) {
                $mapping->setForm($action['@']['form']);
            }
       
            // Input path
            $input = new MvcSkel_Phrame_ActionForward('input', '');
            if (isset($action['input'])) {
                if (!isset($action['input']['@'])) {
                    trigger_error("mappings: only one input tag allow for action");
                } else if (!isset($action['input']['@']['path'])) {
                    trigger_error("mappings: path attribute required for input");
                } else {
                    $input = new MvcSkel_Phrame_ActionForward('input', $action['input']['@']['path']);
                }
            }
            $mapping->setInput($input);
            
            // Forwards
            if (isset($action['forward']['@'])) {
                $tmp = $action['forward'];
                $action['forward'] = array($tmp);
            }
            $forwards =& $action['forward'];
            for ($i=0; $i<count($forwards); $i++) {
                $forward =& $forwards[$i];
                if (!isset($forward['@']['name']) or
                    !isset($forward['@']['path'])) {
                    trigger_error("mappings: invalid forward, name and path required");
                }
                $fName = $forward['@']['name'];
                $fwd = new MvcSkel_Phrame_ActionForward($fName, $forward['@']['path']);
                $mapping->put($fName, $fwd);
            }
            $mappings->put($mapping->getName(), $mapping);
        }
        
        // Rewrite $config with the Phrame mappings data
        return $mappings;
    }
}