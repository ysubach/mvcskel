<?php
/**
* MvcSkel status helper.
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
* Status helper. 
* 
* Small helper class useful for tracking of operation status.
* It uses session to store status string, use add() method for adding new one.
* Method fetch() allows to read status, which is removed from session
* at the same time. That's why status is visible only one time.
*    
* @package    MvcSkel
* @subpackage    Helper
*/
class MvcSkel_Helper_Status {
    /**
    * Add new status message. Only one message supported for now,
    * so second call to add() overwrites previous message.
    * @param string $message Text of status message 
    */
    public static function add($message) {
        $_SESSION['MvcSkel_Helper_Status'] = $message;
    }
    
    /**
    * Check that status message exists.
    * @return boolean True - status exists, False - not found. 
    */
    public static function exists() {
        return isset($_SESSION['MvcSkel_Helper_Status']);
    }
    
    /**
    * Fetch current status message
    * @return string Text of message, null if not found.
    */
    public static function fetch() {
        if (MvcSkel_Helper_Status::exists()) {
            $value = $_SESSION['MvcSkel_Helper_Status'];
        } else {
            $value = null;
        }
        unset($_SESSION['MvcSkel_Helper_Status']);
        return $value;
    }
}
?>
