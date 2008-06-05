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
* Include PEAR Auth library.
*/
    require_once 'Auth.php';
    
    /**
     * Config helper is required to read Logger setup.
     */
    require_once 'MvcSkel/Helper/Config.php';

/**
 * Auth helper. 
 * 
 * It is a wrapper for PEAR::Auth lib. It uses MBD2 driver
 * and get options from config file.
 *  Usage:
 *  <code>
 *    $auth = new MvcSkel_Helper_Auth();
 *    if ($auth->getAuth()) {
 *        echo 'some private information...';
 *    }
 *  </code>
 * It also adds role model to the standard pear class. So you 
 * can have different level of security on your site. 
 *  Example:
 *  <code>
 *    $auth = new MvcSkel_Helper_Auth();
 *    if ($auth->getAuth()) {
 *        echo 'show to authenticated users';
 *    }
 *    if ($auth->checkRole('Publisher')) {
 *        echo 'show to publisher users only';
 *    }
 *    if ($auth->checkRole('Administrator')) {
 *        echo 'only administrator can see it';
 *    }
 *  </code>
 * @see MvcSkel_Filter_Auth
 *    
 * @package    MvcSkel
 * @subpackage    Helper
 */
    class MvcSkel_Helper_Auth extends Auth {
        public function __construct() {
            $config = MvcSkel_Helper_Config::read();
            
            $options = array('dsn'=>$config['dsn'],
                'table'      => 'User',
                'db_fields'  => array('roles', 'fname'),
                'db_options' => array('portability' => MDB2_PORTABILITY_ALL ^ MDB2_PORTABILITY_FIX_CASE)
            );
            
            $this->Auth('MDB2', $options, '', false);
        }
        
        /**
         * Check user role.
         * 
         * Check if authenticated user has a role. 
         * @param string $role role name
         * @return boolean true if the user has the role, false otherwise
         */
        public function checkRole($role) {
            $roles = $this->getAuthData('roles');
            $res = preg_split('/,\s*/', $roles, -1, PREG_SPLIT_NO_EMPTY);
            return in_array($role, $res);
        }
    }
?>
