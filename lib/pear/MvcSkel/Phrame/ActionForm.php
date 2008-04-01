<?php
/**
 * An ActionForm is optionally associated with one or more ActionMappings. The
 * properties will be initialized from the corresponding request parameters
 * before the corresponding Action's perform() method is called. When the
 * properties have been populated, but before the perform() method of the
 * Action is called, the validate() method will be called, which gives a chance
 * to verify that the properties submitted by the user are correct and valid.
 *
 * @author	Arnold Cano
 */
class MvcSkel_Phrame_ActionForm extends MvcSkel_Utils_HashMap
{
	/**
	 * Validate the properties that have been set for this request and
	 * return ActionError objects representing any validation errors that have
	 * been found. Subclasses must override this method to provide any
	 * validation they wish to perform.
	 *
	 * @access	public
	 * @return	boolean
	 */
	function validate() {}

    /**
     * Process the input URL of this form. This step necessary to add some
     * variable to the input URL that can not be set in the mappings.xml
     * file. For example: dynamic identifier of some object in the database
     * necessary to render the input page.
     *
     * This is default implementation, it just return default URL.
     *
     * @param $url default UrlConstructor object, it is defined in the
     *             mappings.xml file
     * @return modified UrlConstructor object
     */
    function processInputUrl($url) { return $url; }
    
	/**
	 * Reset all properties to their default state. This method is called
	 * before the properties are repopulated by the ActionController. The
	 * default implementation does nothing. Subclasses should override this
	 * method to reset all bean properties to default values.
	 *
	 * @access	public
	 */
	function reset() {}
}
?>
