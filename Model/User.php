<?php
/**
 * Table Definition for User
 * @package model
 */

/**
 * Base object definition
 */
require_once 'DB/DataObject.php';

/**
 * Model Model_User
 * @package model
 */
class Model_User extends DB_DataObject 
{
    ###START_AUTOCODE
    /* the code below is auto generated do not remove the above tag */

    var $__table = 'User';                            // table name
    var $id;                              // int(20)  not_null primary_key auto_increment
    var $login;                           // string(255)  not_null unique_key
    var $password;                        // string(255)  not_null
    var $rights;                          // string(255)  not_null
    var $email;                           // string(255)  not_null
    var $fname;                           // string(255)  not_null

    /* ZE2 compatibility trick*/
    function __clone() { return $this;}

    /* Static get */
    function staticGet($k,$v=NULL) { return DB_DataObject::staticGet('Model_User',$k,$v); }

    /* the code above is auto generated do not remove the tag below */
    ###END_AUTOCODE

    /**
     * Set new user rights
     * @param array $rights array contains accesses allowed to user
     */
    function setRights($rights) {
        $this->rights = join(',', $rights);
    }

    /**
     * Retups current user rights
     * @return  array contains accesses allowed to user (strings)
     */
    function getRights() {
        $rights = explode(',', $this->rights);
        
        for ($i=count($rights)-1; $i>-1; $i--) {
            $rights[$i] = trim($rights[$i]);
        }
        
        return $rights;
    }

    /**
     * Setups new user password
     * @param $newPassword new user password
     */
    function setPassword($newPassword) {
        $this->password = md5($newPassword);
    }

    /**
     * Checks if user have access (right)
     * @param $access access type, string
     * @return true if user have given access and false otherwise
     */
    function haveAccess($access) {
        $rights = $this->getRights();
        return in_array($access, $rights);
    }
}
