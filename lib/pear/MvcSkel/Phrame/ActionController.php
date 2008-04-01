<?php
/**
 * The ActionController class represents the controller in the
 * Model-View-Controller (MVC) design pattern. The ActionController receives
 * and processes all requests that change the state of the application.
 *
 * @package phrame
 */
 
/** 
 * Context is used for path and class creation.
 */
require_once 'MvcSkel/Context.php';

/**
 * The ActionController class represents the controller in the
 * Model-View-Controller (MVC) design pattern. The ActionController receives
 * and processes all requests that change the state of the application.
 *
 * @package phrame
 */
class MvcSkel_Phrame_ActionController {
	/**
	 * Create a ActionController
	 */
	function MvcSkel_Phrame_ActionController()
	{
	}
    
	/**
	 * Process the request.
	 *
	 * @param	array	$mappings
	 * @param	array	$request
	 */
	function process($mappings, $request) 	{
        // Check for correct parameters
		if (!is_array($request)) {
			trigger_error('MvcSkel_Phrame_ActionController: Invalid request', E_USER_ERROR);
		}

        // Check for action in request
        if (!isset($request['action'])) {
			trigger_error('MvcSkel_Phrame_ActionController: Action not specified', E_USER_ERROR);
        }
      
        // Get mapping object
        $action = $request['action'];
        if (!$mappings->containsKey($action)) {
			trigger_error("MvcSkel_Phrame_ActionController: Mapping for '$action' not found", E_USER_ERROR);
        }
        
        $context = new MvcSkel_Context($action);
        
        // Include action and form files
        $actionFile = $context->getFileName('action');
        $formFile   = $context->getFileName('form');
        if (!file_exists($actionFile)) {                
            trigger_error("Action file '$actionFile' not found!", E_USER_ERROR);
        } else if (!file_exists($formFile)) {                
            trigger_error("Form file '$formFile' not found!", E_USER_ERROR);
        }
        require_once $actionFile;
        require_once $formFile;

        // Make action objects
        $actionClass = $context->getClassName('action');
        $actionObject = new $actionClass;

        // Make form object
        $formClass = $context->getClassName('form');
        $formObject = new $formClass;

        // Validate request
        $map = $mappings->get($action);
        $formObject->putAll($request);
        if ($formObject->validate()==false) {
            $input = $map->getInput();
            $url = $input->getUrl();
            $url = $formObject->processInputUrl($url);
            $input->setUrl($url);
            $input->redirect();
            return;
        }

        // Peform action
        $actionForward = $actionObject->perform($map, $formObject);

        // Redirecting
        $actionForward->redirect();
	}    
}

?>
