<?php
/**
 * The ActionMapping class represents the information that the ActionController
 * knows about the ActionMapping of a particular request to an instance of a
 * particular Action class. The ActionMapping is passed to the perform() method
 * of the Action class itself, enabling access to this information directly.
 */
class MvcSkel_Phrame_ActionMapping extends MvcSkel_Utils_HashMap
{
	/**	Logical name of the ActionMapping */
	var $_name;

    /** Name of related from class */
    var $_form;
    
	/**	Input path string */
	var $_input;

	/**
	 * Create new ActionMapping.
	 *
	 * @param $name name of the action
	 */
	function MvcSkel_Phrame_ActionMapping($name)
	{
		$this->clear();
		$this->_name = $name;
        $this->_form = '';
        $this->_input = '';
	}
      
	/** Get the name of the ActionMapping. */
	function getName()
	{
		return $this->_name;
	}
    
	/** Get the input path */
	function getInput()
	{
		return $this->_input;
	}
    
	/** Set the input path. */
	function setInput($input)
	{
		$this->_input = $input;
	}
    
	/**
	 * Get name of the action class.
     * Return default name based on action name.
	 */
	function getAction()
    {
        return $this->_name.'Action';
    }

    /**
	 * Get name of the form class.
     * If $this->_form not specified it return default form name based on action
     * name.
	 */
	function getForm()
	{
        if ($this->_form!='')
        {
            return $this->_form.'Form';
        }
        else
        {
            return $this->_name.'Form';
        }
    }
    
	/**
	 * Set form class name
	 */
	function setForm($form)
	{
		$this->_form = $form;
	}
}
?>
