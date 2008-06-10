<?php
/**
* MvcSkel auth filter.
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
require_once 'MvcSkel/Helper/Auth.php';

/**
* Authentication filter.
* 
* Check for authenticated users. Also can check user roles.
* 
* @category   framework
* @package    MvcSkel
* @subpackage    Filter
*/ 
class MvcSkel_Filter_Auth extends MvcSkel_Filter {
    private $roles;

    /**
     * C-r.
     * @param string|array $roles role name or array of role names
     *  if the parameter is empty, then the filter will just check whether
     *  user authenticated or not. Otherwise filter will allow request if the 
     *  user have at least one of the roles.
     * @see MvcSkel_Helper_Auth::checkRole()
     */
    public function __construct($roles = null) {
        if ($roles===null) {
            $roles = array();
        } else if (!is_array($roles)) {
            $roles = array($roles);
        }
        $this->roles = $roles;
    }

    /**
     * Add 'mvcskel_a' and 'mvcskel_c' vars to request if necessary.
     */
    public function filter() {
        $auth = new MvcSkel_Helper_Auth();
        $auth->start();
        
        
        // visitor have to be authenticated at least
        if (!$auth->checkAuth()) {
            $this->showLogin();
            return false;
        }
        
        // check if we need to check roles also
        if (count($this->roles)) {
            foreach ($this->roles as $role) {
                if ($auth->checkRole($role)) {
                    return true;
                }
            }
            $this->showLogin();
            return false;
        }
        
        return true;
    }
    
    /**
     * Do redirect to login form.
     */
    protected function showLogin() {
        // redirect to login form
        header('Location: Auth/Login');
        exit();
    }
}
?>
