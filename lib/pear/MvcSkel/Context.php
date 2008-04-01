<?php
/**
 * Current context
 * Stored here for caching purposes :)
 */
$GLOBALS['MvcSkel_Context_CurrentContext'] = null;

/**
 * Context class definition.
 * Objects of this class are identifiers of web site context - position in the
 * project structure.
 */
class MvcSkel_Context {
    /**
     * Elements of context stored in array
     * First element is top level container name, second is name of container
     * under first, etc.
     */
    var $elements;
    
    /**
     * Constructor
     *
     * @param $path string, relative path to current view or action
     */
    function MvcSkel_Context($path)
    {
        $this->elements = array();
        
        // Store elements
        $pieces = explode('/', $path);
        if ($pieces!==false) {

            foreach ($pieces as $piece) {

                $piece = trim($piece);
                if ($piece!=='') {

                    $this->elements[] = $piece;
                }
            }
        }
        
        // Set first element if we still have nothing
        if (count($this->elements)==0) {

            $this->elements[] = 'undefined';
        }
    }

    /**
     * Get first element of context path
     *
     * @return first element string
     */
    function getFirst()
    {
        return $this->elements[0];
    }

    /**
     * Get last element of context path
     *
     * @return last element string
     */
    function getLast()
    {
        $elementsCount = count($this->elements);
        return $this->elements[$elementsCount-1];
    }

    /**
     * Get path element
     *
     * @param $number number of needed element
     * @return element string if it exists, false if element not found
     */
    function getElement($number) {
        $elementsCount = count($this->elements);
        if ($number<0 || $number>($elementsCount - 1)) {
            return false;
        } 
        return $this->elements[$number];
    }

    /**
     * Get number of elements
     *
     * @return elements count
     */
    function getCount()
    {
        return count($this->elements);
    }

    /**
     * Push new (not empty) element at the end of context
     *
     * @param $element new element string
     * @return void
     */
    function push($element)
    {
        if (strlen($element)>0) {

            array_push($this->elements, $element);
        }
    }

    /**
     * Pop the element off the end of array
     *
     * @return last poped element string, boolean false if no elements found
     */
    function pop()
    {
        return array_pop($this->elements);
    }

    /**
     * Convert context to string representation
     *
     * @param $delimiter delimiter string,  '_' is default
     */
    function toString($delimiter='_') {
        return implode($delimiter, $this->elements);
    }

    /**
     * Compare with another context object
     *
     * @param $context compared Context object
     * @return boolean
     */
    function equals(&$context) {
        return $this->toString()===$context->toString();
    }

    /**
     * Compare first element with another context object
     *
     * @param $context compared Context object
     * @return bool
     */
    function equalsFirst(&$context) {
        return $this->getFirst()===$context->getFirst();
    }

    /**
     * Get current context object. Static function.
     * Take view or action path from request as current context string.
     *
     * @return current Context object
     */
    function getCurrent() {
        if ($GLOBALS['MvcSkel_Context_CurrentContext']===null) {

            $path = '';

            // Try to get path from request variables
            if (array_key_exists('v', $_REQUEST)) {

                $path = $_REQUEST['v'];
            }
            elseif (array_key_exists('view', $_REQUEST)) {

                $path = $_REQUEST['view'];
            }
            elseif (array_key_exists('action', $_REQUEST)) {

                $path = $_REQUEST['action'];
            }

            // Make new context object
            $GLOBALS['MvcSkel_Context_CurrentContext'] = new MvcSkel_Context($path);
        }

        // Return context object
        return $GLOBALS['MvcSkel_Context_CurrentContext'];
    }
    
    /**
     * Get class definition name.
     * @param string $type type of entity. Possibile values:
     *  - form
     *  - action
     *  - view
     * @return string name of class.
     */
     function getClassName($type) {
         return ucwords($type) . '_' . join('_', $this->elements);
     }
     
    /**
     * Get class definition file name.
     * @param string $type type of entity. Possibile values:
     *  - form
     *  - action
     *  - view
     * @return string name of class.
     */
     function getFileName($type) {
         return './' . ucwords($type) . '/' . join('/', $this->elements) . '.php';
     }
}
?>