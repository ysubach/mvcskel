<?php
/**
 * View for list of User.
 * @package view
 * @subpackage User
 */
 
/**
 * Include base class.
 */ 
require_once 'MvcSkel/View/Table.php';

/**
 * View for User list.
 * @package view
 * @subpackage User
 */
class View_User_Index extends MvcSkel_View_Table {
    /**
     * Constructor.
     */
    function View_User_Index() {
        parent::MvcSkel_View_Table();	
        $this->columns = array('id' => array('db_name'=>'id'),
                               'login' => array('db_name'=>'login'),
                               'password' => array('db_name'=>'password'),
                               'rights' => array('db_name'=>'rights'),
                               'email' => array('db_name'=>'email'),
                               'fname' => array('db_name'=>'fname'),
                               );
        
        $this->searchColumns = array('id',
                                     'login',
                                     'password',
                                     'rights',
                                     'email',
                                     'fname',
                                     );

        $object = new Model_User();
        $this->initialize($object);
    }

    /**
     * @see PhpSkel_View::prepare()
     */
    function prepare() {
        parent::prepare();
        $this->smarty->assign('title', 'User list');
    }
}