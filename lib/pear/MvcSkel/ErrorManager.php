<?php
/**
* Error Manager, MvcSkel error handler.
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
 * Class for error management.
 */
class MvcSkel_ErrorManager {
    /**
     * Check if we have any errors
     * @param $context required Context object, if this parameter null or not
     *                        specified current context used
     * @return 'true' if have errors, 'false' if no errors
     * @static	 
     */
    function haveErrors($context=null) {
        $context = MvcSkel_ErrorManager::_getRealContext($context);
        $_errors = MvcSkel_ErrorManager::_getSessionName($context);
        if (!isset($_SESSION[$_errors])) {
            ErrorManager::clear($context);
        }
        return count($_SESSION[$_errors]);
    }

    /**
     * Get all errors in one array
     * @param $context required Context object, if this 
	 * parameter null or not specified current context used
     * @return array of errors
     * @static	 
     */
    function getErrors($context=null) {
        $context = MvcSkel_ErrorManager::_getRealContext($context);
        $_errors = MvcSkel_ErrorManager::_getSessionName($context);
        if (!isset($_SESSION[$_errors])) {
            MvcSkel_ErrorManager::clear($context);
        }
        return $_SESSION[$_errors];
    }
    
    /**
     * Clear all errors
     * @param $context required Context object, 
	 * if this parameter null or not specified current context used
     * @static	 
     */
    function clear($context=null) {
        $context = MvcSkel_ErrorManager::_getRealContext($context);
        $_errors = MvcSkel_ErrorManager::_getSessionName($context);
        $_SESSION[$_errors] = array();
    }

    /**
     * Raise new error
     * @param $localContext local context of error (string)
     * @param $description description of entire error (string)
     * @static	 
     */
    function raise($localContext, $description) {
        $context = MvcSkel_Context::getCurrent();
        $_errors = MvcSkel_ErrorManager::_getSessionName($context);
        if (!isset($_SESSION[$_errors])) {
            MvcSkel_ErrorManager::clear();
        }
        $errors = $_SESSION[$_errors];
        if (isset($errors[$localContext])) {
            $errors[$localContext][] = $description;
        } else {
            $errors[$localContext] = array($description);
        }
        $_SESSION[$_errors] = $errors;
    }

    /**
     * Get session name of errors array. Name defined according with given
     * context.
     * @param $context required Context object
     * @static	 
     */
    function _getSessionName($context) {
        return ('MvcSkel_ErrorManager_' . $context->toString());
    }

    /**
     * Get real context object.
     * @param $context desired Context object
     * @return given context if it not null, current context if null
     * @static	 
     */
    function _getRealContext($context) {
        if ($context===null) {
            return MvcSkel_Context::getCurrent();
        } else {
            return $context;
        }
    }
}
