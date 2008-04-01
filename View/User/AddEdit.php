<?php
/**
 * View for editing of User.
 * @package view
 * @subpackage User
 */
 
/**
 * Include base class.
 */ 
require_once 'MvcSkel/ModelEditCache.php';

/**
 * Include base class.
 */ 
require_once 'MvcSkel/View.php';

/**
 * View for editing of User.
 * @package view
 * @subpackage User
 */
class View_User_AddEdit extends MvcSkel_View {
    /**
     * Constructor.
     */
    function View_User_AddEdit() {
        $this->template = 'User/AddEdit.tpl';
    }

    /**
     * @see PhpSkel_View::prepare()
     */
    function prepare() {
        parent::prepare();
        $mec = new MvcSkel_ModelEditCache('User', 
            $_REQUEST['id'], isset($_REQUEST['new']));
        $this->smarty->assign('title', 'Add/Edit User');
        $this->smarty->assign('object', $mec->get());
        
        // prepare roles list
        $access = &PEAR::getStaticProperty('MvcSkel', 'access');
        $roles = $access['roles']['role'];
        // remove Anonymos role
        $anonymousKey = array_search('Anonymous', $roles);
        if ($anonymousKey!==false) {
            unset($roles[$anonymousKey]);
        }
        $this->smarty->assign('roles', $roles);
    }
}