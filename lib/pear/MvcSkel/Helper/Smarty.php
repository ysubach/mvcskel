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
* Include Smarty library.
*/
    require_once 'Smarty.class.php';
    
    /**
     * Config helper is required to read the tmp_dir.
     */
    require_once 'MvcSkel/Helper/Config.php';

/**
* Smarty helper. Implements basic concept of templates rendering.
* @package    MvcSkel
* @subpackage    Helper
*/
    class MvcSkel_Helper_Smarty extends Smarty {
        /**
        * C-r.
        * @param string $bodyTemplate template which is used for rendering 
        * of the page content
        */
        public function __construct($bodyTemplate) {
            // define tmp folder
            $config = MvcSkel_Helper_Config::read();
            
            $this->compile_dir   = $config['tmp_dir'] . '/templates_c';
            $this->assign('bodyTemplate', $bodyTemplate);
        }
        
        /**
        * Short hand for fetching of master.html.
        * @return result of @see Smarty::fetch()
        */
        public function render() {
            return $this->fetch('master.html');
        }
    }
?>
