<?php
/**
* Extended authentication class.
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
require_once 'Auth/Auth.php';

/**
* Extended auth class.
* Added some specific functions.
* @package    PhpSkel
*/
class MvcSkel_Auth extends Auth {
    /** Current user object */
    var $_currentUser = null;
    
    /**
     * Constructor
     */
    function MvcSkel_Auth() {
        $dbOpt = &PEAR::getStaticProperty('DB_DataObject', 'options');
        $opt = &PEAR::getStaticProperty('MvcSkel', 'options');
        
        $this->Auth('MDB2',
                    array('dsn'         => $dbOpt['database'],
                          'table'       => $opt['auth']['table'],
                          'usernamecol' => 'login',
                          'passwordcol' => 'password'),
                    'loginFunction'); // trick, this function not defined ever
        $this->setShowLogin(false);
    }

    /**
     * Get authenticated user object.
     * @return User object on success, null if no authenticated user.
     */
    function &getUser() {
        if ($this->_currentUser===null) {
            $usernamecol = $this->storage_options['usernamecol'];
            $user = DB_DataObject::factory('User');
            $user->$usernamecol = $this->getUsername();
            if (!empty($user->$usernamecol)) {
                $user->find(true);
            }
            $this->_currentUser = &$user;
        }
        return $this->_currentUser;
    }

    /**
     * The function does the same as in basis class, but clear some
     * session vars also
     */
    function logout() {
        $this->_currentUser = null;
        session_unset();
        return parent::logout();
    }
}
