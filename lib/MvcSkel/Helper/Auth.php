<?php
/**
* MvcSkel Auth helper.
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
 * @subpackage Helper
 */
class MvcSkel_Helper_Auth extends Auth {
    public function __construct() {
        $config = MvcSkel_Helper_Config::read();

        $options = array(
            'dsn'        => $config['dsn'],
            'table'      => 'User',
            'usernamecol'  => 'username',
            'passwordcol'  => 'password',
            'db_fields'  => array('roles', 'fname', 'id'),
            'db_options' => array('portability' => MDB2_PORTABILITY_ALL ^ MDB2_PORTABILITY_FIX_CASE),
            'enableLogging'=>true,
        );

        $this->Auth('MDB2', $options, '', false);
        $this->logger = MvcSkel_Helper_Log::get('MvcSkel_Helper_Auth');
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

    /**
    * Return current logged in user object
    */
    public function getUser() {
        $id = $this->getAuthData('id');
        if ($id==null) {
            throw new Exception("User identifier is missing!");
        }
        return Doctrine::getTable('User')->find($id);
    }

    /**
     * Retrieve current user, static function for more comfortable use.
     */
    public static function user() {
        $auth = new MvcSkel_Helper_Auth();
        $auth->start();
        return $auth->getUser();
    }

    /**
     * Try to autologin user (check for cookie and cookie value).
     */
    public function autoLoginGet() {
        if (!isset($_COOKIE['mvcskel_auto_login'])) {
            return false;
        }
        $user = Doctrine::getTable('User')->findOneByAutoLoginKey($_COOKIE['mvcskel_auto_login']);
        if ($user) {
            $usernamecol = $this->storage_options['usernamecol'];
            $this->setAuth($user->$usernamecol);
            // set auth data necessary for application
            foreach ($this->storage_options['db_fields'] as $field) {
                $this->setAuthData($field, $user->$field);
            }
            return true;
        }
        return false;
    }

    /**
     * Creates cookie for further auto-login.
     */
    public function autoLoginPut() {
        // keep key in db
        $user = $this->getUser();
        $user->autoLoginKey = md5(time().rand());
        $user->save();
        // keep key in cookie
        setcookie('mvcskel_auto_login', $user->autoLoginKey,
            time()+3600*24*6, '/');
    }

    public function logout() {
        $this->currentUser = null;
        $result = parent::logout();
        if (isset($_COOKIE['mvcskel_auto_login'])) {
            setcookie('mvcskel_auto_login', '', time()-3600*24*6, '/');
        }
        session_unset();
        return $result;
    }
}
?>
