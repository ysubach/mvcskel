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

require_once 'MvcSkel/Filter.php';
require_once 'MvcSkel/Helper/Config.php';
require_once 'Doctrine.php';

/**
 * @category   framework
 * @package    MvcSkel
 * @subpackage Filter
 */ 
class MvcSkel_Filter_DoctrineInit extends MvcSkel_Filter {
    public function filter() {
        spl_autoload_register(array('Doctrine', 'autoload'));
        Doctrine_Manager::getInstance()->setAttribute('model_loading', 'aggressive');
        Doctrine::loadModels('app/Model/generated');
        Doctrine::loadModels('app/Model');
        
        $config = MvcSkel_Helper_Config::read();
        Doctrine_Manager::connection($config['dsn']);
        
        return true;
    }
}
?>
