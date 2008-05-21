<?php
/**
* MvcSkel controller.
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
* Include Spyc library.
*/
require_once 'spyc.php5';

/**
* Configuration helper. 
* 
* This helper read configuration from YAML file.
*  Usage:
*  <code>
*    $config = new MvcSkel_Helper_Config::read();
*    echo 'Temp directory is: ' . $config['tmp_dir'] . '<br>';
*    echo 'Database name is: ' . $config['database']['name'];
*  </code>
*    
* @package    MvcSkel
* @subpackage    Helper
*/
class MvcSkel_Helper_Config {
    /**
     * Read config file and return as array.
     * Also it caches the config data within the request
     * @param string $file configuration file name,
     *  default is 'lib/config.yml'
     * @param string $tmp_dir tmp folder name, the values is used
     *  to define a place for cache file creating and put in config
     *  structure for futher usage in the application
     * @return array configuration data
     */
    public function read($file = 'app/config.yml', $tmp_dir = 'tmp') {
        static $conf = array();

        // try to read from memory cache at first
        if (!isset($conf[$file])) {
            // try to read from file cache then
            $cache = $tmp_dir . '/cache.' . basename($file);
            if (@filemtime($cache)>filemtime($file)) {
                $conf[$file] = unserialize(file_get_contents($cache));
            } else {
                // no caches read config file directly
                $conf[$file] = Spyc::YAMLLoad($file);
                $conf[$file]['tmp_dir'] = $tmp_dir;
                // create cache file
                file_put_contents($cache, serialize($conf[$file]));
            }
        }

        return $conf[$file];
    }
}
?>
