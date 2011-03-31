<?php

/**
 * MvcSkel validator helper.
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
 * Validator helper.
 * Provide standard validation methods. Found errors are saved in associated
 * form object.
 *
 * @package    MvcSkel
 * @subpackage Helper
 */
class MvcSkel_Helper_Validator {

    /** Associated form */
    private $form;

    /**
     * Contructor.
     * @param string $form From object for saving errors
     */
    public function __construct($form) {
        $this->form = $form;
    }

    /**
     * Check if value not empty
     * @param string field
     * @param string value
     * @return true if value not empty, false otherwise
     */
    public function checkNotEmpty($field, $value) {
        if (trim($value) == '') {
            $this->form->attachError($field, 'This information is required');
            return false;
        }
        return true;
    }

    /**
     * Check if value is correct currency
     * @param string field
     * @param string value
     * @return true if value not empty, false otherwise
     */
    public function checkCurrency($field, $value) {
        $value = trim($value);
        if (!ereg('^[[:digit:]]*\.{0,1}[[:digit:]]{0,2}$', $value)) {
            $this->form->attachError($field, 'Please enter correct sum');
            return false;
        }
        return true;
    }

    /**
     * Compares given object userId with the current user.id.
     * @param int $userId user id of checked object
     */
    public static function checkOwnership($userId) {
        $logger = MvcSkel_Helper_Log::get(__CLASS__);
        $auth = new MvcSkel_Helper_Auth();
        if ($auth->checkRole('Administrator')) {
            return true;
        }

        $user = MvcSkel_Helper_Auth::user();
        if ($userId != $user->id) {
            $this->form->attachError('ownership', 'you are not the owner of object');

            $logger->alert('user with id:' . $user->id .
                    ' tries access in context of object of user ' . $userId);

            return false;
        }

        return true;
    }

    /**
     * Check if value is correct integer number
     * @param string field
     * @param string value
     * @return true if validation ok
     */
    public function checkInteger($field, $value) {
        $value = trim($value);
        if (!is_numeric($value)) {
            $this->form->attachError($field, 'Please enter numeric value');
            return false;
        }
        return true;
    }

    /**
     * Check if value is correct URL and present a supported protocol
     * @param string field
     * @param string value
     * @return true if value not empty, false otherwise
     */
    public function checkURL($field, $value) {
        $value = trim($value);
        if (!preg_match('/^(http|https):\/\/\w+/', $value)) {
            $this->form->attachError($field, 'Please enter correct URL');
            // additional note about protocol
            if ($this->hasProtocol($value)) {
                $this->form->attachError($field, 'Protocol is not supported');
            }
            return false;
        }
        return true;
    }

    /**
     * Check if url starts with a protocol (any protocol)
     * @param string $url
     * @return mixed @see preg_match
     */
    public function hasProtocol($url) {
        return preg_match('/:\/\/\w+/', $url);
    }

    /**
     * Try to fix url with adding of necessary protocol
     * @param string $url url string to fix
     * @param string $proto the protocol to add to url
     * @return string fixed url
     */
    public function fixURL($url, $proto = 'http://') {
        // do nothing if clever user already add a protocol
        if ($this->hasProtocol($url)) {
            return $url;
        }

        // otherwise add a default one
        return $proto . $url;
    }

    /**
     * Check if value is correct email
     * @param string field
     * @param string value
     * @return true if value is correct, false otherwise
     */
    public function checkEmail($field, $value) {
        $value = trim($value);
        if (ereg('^[-!#$%&\'*+\\./0-9=?A-Z^_`a-z{|}~]+' . '@' .
                        '[-!#$%&\'*+\\/0-9=?A-Z^_`a-z{|}~]+\.' .
                        '[-!#$%&\'*+\\./0-9=?A-Z^_`a-z{|}~]+$',
                        $value)) {
            return true;
        } else {
            $this->form->attachError($field, 'Please enter correct email address');
            return false;
        }
    }

    /**
     * Check if file is not empty
     * @param string field
     * @param HTTP_Upload_File file
     * @return true if file not empty, false otherwise
     */
    public function checkFileNotEmpty($field, $file) {
        if (!$file->isValid() or $file->getProp('size') == 0) {
            $this->form->attachError($field, 'Please upload not empty file');
            return false;
        }
        return true;
    }

    /**
     * Check if image format is correct. This implementation check it using only
     * file extension.
     * @param string field
     * @param HTTP_Upload_File file
     * @return true if file is correct image, false otherwise
     */
    public function checkImageFormat($field, $file, $extentions = FALSE) {
        if (!$extentions) {
            $extentions = array('png', 'jpg', 'gif', 'jpeg');
        }
        $result = true;
        if ($file->isValid()) {
            $filename = $file->getProp('name');
            $parts = pathinfo($filename);
            $ext = strtolower($parts['extension']);
            if (!in_array($ext, $extentions)) {
                $this->form->attachError($field, 'Possible image extentions: '.
                        implode(', ', $extentions));
                return false;
            }
        } else {
            $result = false;
        }
        return $result;
    }

}

?>
