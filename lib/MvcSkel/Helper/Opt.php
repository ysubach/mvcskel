<?php
/**
 * MvcSkel options helper.
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
 * Options helper.
 *
 * Provides a set of static methods for manipulations
 * with name => value options, they are saved in database
 * table `Opt`.
 *
 * @package    MvcSkel
 * @subpackage Helper
 */
class MvcSkel_Helper_Opt {
    
    /**
     * Current options collection
     */
    private static $map;

    /**
     * Get option value. If not found, then return
     * default value passed in parameters.
     * 
     * @param type $name Option name
     * @param type $default Default option value
     */
    public static function get($name, $default=false) {
        self::loadMap();
        if (isset(self::$map[$name])) {
            return self::$map[$name];
        } else {
            return $default;
        }
    }

    /**
     * Set option value. It overwrite existing option value, 
     * if option exists. Otherwise it'll be created.
     * 
     * @param type $name Option name
     * @param type $value Option value
     */
    public static function set($name, $value) {
        self::loadMap();
        self::$map[$name] = $value;
        $opt = Doctrine::getTable('Opt')->findOneByName($name);
        if ($opt) {
            $opt->value = $value;
        } else {
            $opt = new Opt();
            $opt->name = $name;
            $opt->value = $value;
        }
        $opt->save();
    }
    
    /**
     * Remove option
     * 
     * @param type $name Option name to be removed
     */
    public static function remove($name) {
        self::loadMap();
        unset(self::$map[$name]);
        Doctrine_Query::create()->delete('Opt')
            ->addWhere('name=?', $name)->execute();
    }
    
    /**
     * Get all current options in form of hash map (array)
     */
    public static function getMap() {
        self::loadMap();
        return self::$map;
    }
    
    /**
     * Load all available options from database
     */
    private static function loadMap() {
        if (is_array(self::$map)) {
            // already loaded
            return;
        }
        self::$map = array();
        $opts = Doctrine_Query::create()->from('Opt')->execute();
        foreach ($opts as $opt) {
            self::$map[$opt->name] = $opt->value;
        }
    }
}

?>