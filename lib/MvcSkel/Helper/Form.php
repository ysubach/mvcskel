<?php
/**
 * MvcSkel form helper.
 * 
 * PHP versions 5
 *
 * @category   framework
 * @package    MvcSkel
 * @copyright  2008, Whirix Ltd.
 * @license    http://www.gnu.org/licenses/lgpl.html GNU Lesser Public General License (LGPL).
 * @link       http://code.google.com/p/mvcskel/
 */

require_once 'MvcSkel/Helper/Config.php';

/**
* Form processing helper.
* Save object edited in the form and associated validation errors. Provide
* methods for access to object and errors.
*
* @package    MvcSkel
* @subpackage Helper
*/
class MvcSkel_Helper_Form {
    /** Form identifier */
    private $id;
    
    /** Flag indicates that form found in request */
    private $inRequest;
    
    /** Object edited by the form */
    private $object;
    
    /** Errors associated with object */
    private $errors;
    
    /**
    * Contructor.
    * If 'mvcskel_form_id' field found in request, then form is loaded
    * from session. Otherwise new fresh form created.
    * @param string $freshObject Object to be used if nothing found in request
    *      and session
    */
    public function __construct($freshObject=null) {
        if (isset($_REQUEST['mvcskel_form_id'])) {
            // Existing form
            $this->inRequest = true;
            $this->id = $_REQUEST['mvcskel_form_id'];
            $this->object = $_SESSION[$this->getSid()]['object'];
            $this->errors = $_SESSION[$this->getSid()]['errors'];
        } else {
            // New form
            $this->inRequest = false;
            if ($freshObject==null) {
                throw new Exception("No form Id in request and freshObject".
                    " is null => Can't create new form");
            }
            $this->id = get_class($freshObject); //md5(microtime());
            $this->setObject($freshObject);
            $this->resetErrors();
        }
    }
    
    /**
    * Destructor.
    * Save current form into session.
    */
    public function __destruct() {
        if (!isset($this->id)) {
            return;
        }
        $data = array('object' => $this->object, 'errors' =>$this->errors);
        $_SESSION[$this->getSid()] = $data;
    }
    
    /** Return session Id of the form */
    private function getSid() {
        return "mvcskel_form_".$this->id;
    }
    
    /** Return current form identifier */
    public function getId() {
        return $this->id;
    }

    /** Return current `inRequest` flag */
    public function foundInRequest() {
        return $this->inRequest;
    }

    /** Return current object */
    public function getObject() {
        return $this->object;
    }

    /** Set current object */
    public function setObject($object) {
        $this->object = $object;
        $this->resetErrors();
    }
    
    /** Reset errors to nothing */
    public function resetErrors() {
        $this->errors = array();
    }
    
    /** 
    * Check that we have any errors 
    */
    public function haveErrors() {
        if (isset($this->errors) && count($this->errors)>0) {
            return true;
        } else {
            return false;
        }
    }
    
    /** 
    * Attach new error
    * @param string $fieldId Field identifier where error happen 
    * @param string $errorMessage Description of error
    */
    public function attachError($fieldId, $errorMessage) {
        if (isset($this->errors[$fieldId])) {
            $elist = $this->errors[$fieldId];
        } else {
            $elist = array();
        }
        $elist[] = $errorMessage;
        $this->errors[$fieldId] = $elist;
    }
    
    /**
    * Check that we have error for specific field
    * @param string $fieldId Field identifier
    */
    public function haveError($fieldId) {
        if (isset($this->errors[$fieldId])) {
            return true;
        } else {
            return false;
        }
    }
    
    /**
    * Retrieve error(s) associated with field
    * @param string $fieldId Field identifier
    */
    public function getError($fieldId) {
        if (!$this->haveError($fieldId)) {
            return "";
        }
        return implode("<br>", $this->errors[$fieldId]);
    }
}
?>
