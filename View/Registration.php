<?php
/**
 * Registration page.
 * 
 * @package view
 * @subpackage toppages
 */
 
/**
 * Includes View class.
 */
require_once 'MvcSkel/View.php';
require_once 'MvcSkel/ModelEditCache.php'; 
/**
 * RegistrationView class
 *
 * @package view
 * @subpackage toppages
 */
class View_Registration extends MvcSkel_View {


    function View_Registration() {
    	$this->template = 'registration.tpl';
    }
    
    /**
     * Prepare function
     */
    function prepare() {
        parent::prepare();

        $mec = new MvcSkel_ModelEditCache('User',null, $_REQUEST['new']);
        $this->smarty->assign('object', $mec->get());
        if(isset($_REQUEST['new'])){            
            MvcSkel_ErrorManager::clear();          
        }

        $this->smarty->assign('title', 'Registration');
    }
}
?>