<?php
/**
 * MvcSkel filter for Doctrine initialization.
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
 * Include Doctrine ORM lib.
 */
require_once 'Doctrine.php';

/**
 * @package    MvcSkel
 * @subpackage Filter
 */ 
class MvcSkel_Filter_DoctrineInit extends MvcSkel_Filter {
    public function filter() {
        // load models
        spl_autoload_register(array('Doctrine', 'autoload'));
        Doctrine_Manager::getInstance()->setAttribute('model_loading', 'aggressive');
        Doctrine::loadModels('app/Model/generated');
        Doctrine::loadModels('app/Model');
        
        // back to standard limit clause
        Doctrine_Manager::getInstance()->setAttribute(Doctrine::ATTR_QUERY_LIMIT, Doctrine::LIMIT_ROWS);
        // using native db enum
        Doctrine_Manager::getInstance()->setAttribute(Doctrine::ATTR_USE_NATIVE_ENUM, true);
        
        // prevent serialize error
        require_once 'Doctrine/Event.php';
        
        // setup connection
        $config = MvcSkel_Helper_Config::read();
        Doctrine_Manager::connection($config['dsn']);
        
        // uncomment if you need character set for connection
        //$con = Doctrine_Manager::getInstance()->connection();
        //$con->execute("SET CHARACTER SET utf8");

        return true;
    }
}
?>
