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

/**
* Form processing helper.
* Save object edited in the form and associated validation errors. Provide
* methods for access to object and errors.
*
* Provide generalized form processing framework as a set of abstract methods
* which must be implemented by child classes. Top level form processing logic
* implemented in process() method.
*
* @package    MvcSkel
* @subpackage Helper
*/
abstract class MvcSkel_Helper_Form {
    /** Session FIFO queue size */
    private static $QUEUE_SIZE = 50;

    /** Form identifier */
    private $id;

    /** Flag indicates that form found in request */
    private $inRequest;

    /** Object edited by the form */
    private $object;

    /** Errors associated with object */
    private $errors;

    /** Smarty instance for form rendering */
    protected $smarty;

    /** Name of form source action  */
    protected $sourceAction;

    /** Name of form exit action  */
    protected $exitAction;

    /**
    * Contructor.
    * If 'mvcskel_form_id' field found in request, then form is loaded
    * from session. Otherwise new fresh form created.
    */
    public function __construct($sourceAction, $exitAction, $smarty) {
        $this->sourceAction = $sourceAction;
        $this->exitAction = $exitAction;
        $this->smarty = $smarty;
        if (isset($_REQUEST['mvcskel_form_id'])) {
            // Existing form
            $this->inRequest = true;
            $this->id = $_REQUEST['mvcskel_form_id'];
            $this->object = $_SESSION[$this->getSid()]['object'];
            $this->errors = $_SESSION[$this->getSid()]['errors'];
        } else {
            // New form
            $this->inRequest = false;
            $freshObject = $this->buildFresh();
            $this->id = get_class($freshObject).'-'.md5(rand());
            $this->setObject($freshObject);
            $this->resetErrors();
        }
    }

    /**
     * Destructor.
     * Save current form into session. Check queue and remove old forms.
     */
    public function __destruct() {
        // Get queue
        $qn = 'mvcskel_form_queue';
        $queue = !empty($_SESSION[$qn]) ? $_SESSION[$qn] : array();

        // Save form
        if (isset($this->id)) {
            $data = array('object' => $this->object, 'errors' =>$this->errors);
            $_SESSION[$this->getSid()] = $data;
            array_push($queue, $this->getSid());
        }

        // Check queue, remove old forms
        while (count($queue)>self::$QUEUE_SIZE) {
            $sid = array_shift($queue);
            unset($_SESSION[$sid]);
        }
        $_SESSION[$qn] = $queue;
    }

    /** Return session Id of the form */
    private function getSid() {
        return "mvcskel_form_".$this->id;
    }

    /** Return current form identifier */
    public function getId() {
        return $this->id;
    }

    /** Return associated action name */
    public function getSourceAction() {
        return $this->sourceAction;
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

    /**
     * Form processing entry point.
     * Implements top level processing logic, supported by set of
     * abstract methods.
     * @param bool $ajax if request was done by ajax, at that case
     * JSON structure will be return. Example:
     * <code>
     * {
     *      success:false,
     *      errors:{username:['the field is mandatory'],
     *              password:['the field is mandatory','minimum 5 symbols']}
     * }
     * </code>
     * OR
     * <code>
     * {
     *      success:true,
     *      location:'http://new-location/path'
     * }
     * </code>
     * @return string Content of rendered Smarty instance,
     *       or exit if redirect was done
     */
    public function process($ajax = false) {
        // process request
        if ($this->foundInRequest()) {
            $this->fillByRequest();
            $this->validate();
        }

        // doing action or rendering (ajax mode)
        if ($ajax && $this->foundInRequest()) {
            $response = array('success'=>true);
            if ($this->haveErrors()) {
                $response['success'] = false;
                $response['errors'] = $this->errors;
            } else {
                $this->action();
                $response['location'] = MvcSkel_Helper_Url::url($this->exitAction);
            }
            return json_encode($response);
        }

        // doing action or rendering
        if ($this->foundInRequest() && !$this->haveErrors()) {
            $this->action();
            $this->exitRedirect();
        } else {
            return $this->render();
        }
    }

    /**
     * Performs redirect to exit action. Child classes can override
     * this method for changing redirect dynamically or adding specific
     * parameters.
     */
    protected function exitRedirect() {
        MvcSkel_Helper_Url::redirect($this->exitAction);
    }

    /**
     * Form rendering through Smarty
     */
    protected function render() {
        $smarty = $this->smarty;
        $smarty->assign('form', $this);
        $smarty->assign('object', $this->getObject());
        return $smarty->render();
    }

    /**
    * @return object The fresh one for this form
    */
    abstract protected function buildFresh();

    /**
    * Fill form object's fields from request
    */
    abstract protected function fillByRequest();

    /**
    * Validate current object
    */
    abstract protected function validate();

    /**
    * Performs action, called after successful validation
    */
    abstract protected function action();
}
?>
