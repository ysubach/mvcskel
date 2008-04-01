<?php
/**
 * An Action is an adapter between the contents of an incoming HTTP request and
 * the corresponding business logic that should be executed to process this
 * request. The ActionController will select an appropriate Action for each
 * request, create an instance (if necessary), and call the perform() method.
 *
 * @author	Arnold Cano
 * @version	$Id: Action.php,v 1.1.1.1 2004/08/05 10:23:52 subach Exp $
 */
class MvcSkel_Phrame_Action 
{
	/**
	 * Process the specified HTTP request, and create the corresponding HTTP
	 * response (or forward to another web component that will create it).
	 * Return an ActionForward instance describing where and how control should
	 * be forwarded, or null if the response has already been completed.
	 * Subclasses must override this method to provide any business logic they
	 * wish to perform.
	 *
	 * @access	public
	 * @param	ActionMapping	$actionMapping
	 * @param	ActionForm		$actionForm
	 * @return	ActionForward
	 */
	function perform($actionMapping, $actionForm) {}
}
?>
