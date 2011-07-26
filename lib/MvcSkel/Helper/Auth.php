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
require_once 'Auth.php';
require_once 'MDB2.php';

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

    /** Current user object */
    protected static $currentUser = null;

    /**
     * Constructor
     */
    public function __construct() {
        $config = MvcSkel_Helper_Config::read();

        $options = array(
            'dsn' => $config['dsn'],
            'table' => 'User',
            'usernamecol' => 'username',
            'passwordcol' => 'password',
            'db_fields' => array('roles', 'fname', 'id', 'lastLoginDT'),
            'db_options' => array('portability' => MDB2_PORTABILITY_ALL ^ MDB2_PORTABILITY_FIX_CASE),
            'enableLogging' => false,
        );

        $this->Auth('MDB2', $options, '', false);
        $this->logger = MvcSkel_Helper_Log::get('MvcSkel_Helper_Auth');

        $this->setLoginCallback(array('MvcSkel_Helper_Auth', 'onLogin'));
    }

    /**
     * Callback method, called by parent after login.
     */
    protected function onLogin($username, $auth) {
        $user = $auth->getUser();
        $user->lastLoginDT = new Doctrine_Expression('now()');
        $user->lastIP = $_SERVER['REMOTE_ADDR'];
        $user->save();
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
     * Return current logged in user object, NULL if no logged user
     */
    public function getUser() {
        if (self::$currentUser != null) {
            return self::$currentUser;
        }

        $id = $this->getAuthData('id');
        if ($id == null) {
            // user identifier is missing
            return null;
        }

        self::$currentUser = Doctrine::getTable('User')->find($id);
        return self::$currentUser;
    }

    /**
     * Reset cached user instance: to force get data from database.
     */
    public static function clearCurrentUser() {
        self::$currentUser = null;
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
        $this->logger->debug('try autologin by key ' . $_COOKIE['mvcskel_auto_login']);
        if ($user) {
            $this->setAuthUser($user);
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
        $user->autoLoginKey = md5(time() . rand());
        $user->save();
        // keep key in cookie
        setcookie('mvcskel_auto_login', $user->autoLoginKey, time() + 3600 * 24 * 6, '/');
    }

    /**
     * Set user as currently authorized
     * @param User $user
     */
    public function setAuthUser($user) {
        // set auth
        $usernamecol = $this->storage_options['usernamecol'];
        $this->setAuth($user->$usernamecol);

        // set additional auth data (by some reason it is not done yet?)
        $this->logger->debug('found user with id ' . $user->id .
                ' and username ' . $user->$usernamecol);
        $db_fields = $this->storage_options['db_fields'];
        foreach ($db_fields as $field) {
            $this->setAuthData($field, $user->$field);
            $this->logger->debug('set auth data:' . $field . '-' . $user->$field);
        }

        // update user last login data
        $user->lastLoginDT = new Doctrine_Expression('now()');
        $user->lastIP = $_SERVER['REMOTE_ADDR'];
        $user->save();
    }

    /**
     * Logout and kill session
     */
    public function logout() {
        self::$currentUser = null;
        $result = parent::logout();
        if (isset($_COOKIE['mvcskel_auto_login'])) {
            setcookie('mvcskel_auto_login', '', time() - 3600 * 24 * 6, '/');
        }
        session_unset();
        return $result;
    }

}

?>
