<?php
/**
* Model edit cache.
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
* Class is used for editing of Model-objects. It helps to keep
* in session object of model during edit process. It allows do not
* keep id of object in form and filled html-forms on error back input.
*/
class MvcSkel_ModelEditCache {
    /**
    * Model name.
    * @access protected
    */
    var $_model;
    
    /**
    * C-r. Cache will be cleared on new PK or if $clear flag is set to true.
    * @param string $model name of model object.
    * @param int $pk key for model loading
    * @param bool $clear flag to clear cache
    */
    function MvcSkel_ModelEditCache($model, $pk = null, $clear = false) {
        $this->_model = $model;
        if ($clear) {
            $this->clear();
        }
        $this->_init($pk);
    }
    
    /**
    * Returns reference to session variable. 
    * IMPORTANT NOTE!!! When make new object for store to DB, use &get();
    * $mec = new MvcSkel_ModelEditCache('your_model', null, true);
    * $var = & $mec->get();
    * $id = $var->insert();
    * $var->id = $id;
    * @return object model object
    */
    function &get() {
        return $_SESSION[$this->__getSessionVarName()];
    }
    
    /**
    * Removes session caches model object.
    * @access public
    */
    function clear() {
        unset($_SESSION[$this->__getSessionVarName()]);
    }
    
    /** 
    * Init cached variable, do nothing if session var already
    * created, but clear if pk is not null or other pk.
    * @access protected
    */
    function _init($pk) {
        // ref to object
        $var =& $this->get();
        //echo ('pk = '. $pk . '   stored = ' . $var . '<br>');
        if (!isset($var)) {
        	//echo('==!isset, create new==     '. '<br>');        
            $this->_createNew($pk);
        } else {            	
        	//echo('    ==isset->id :' . $var->id .'     !  '. '<br>');
           // var_dump($var);       
            // get by PK
            if (isset($pk) && ($var->id != $pk)) {
            	//echo(' ===isset, create new===    '. '<br>');                           
                //echo 'clear because key is given';            
                $this->clear();
                $this->_createNew($pk);
            }
        }
    }
    
    function _createNew($pk){
        //echo '!isset';
        $var = DB_DataObject::factory($this->_model);            //
        if (isset($pk)) {
            //echo 'get';
            $var->get($pk);
        }
        $_SESSION[$this->__getSessionVarName()] = clone($var);    	
    }
    
    function __getSessionVarName() {
        return get_class($this) . '_' . $this->_model;
    }
}
