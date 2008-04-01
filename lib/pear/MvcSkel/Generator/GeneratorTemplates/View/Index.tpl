<?php
/**
 * View for list of {{$table}}.
 * @package view
 * @subpackage {{$table}}
 */
 
/**
 * Include base class.
 */ 
require_once 'MvcSkel/View/Table.php';

/**
 * View for {{$table}} list.
 * @package view
 * @subpackage {{$table}}
 */
class View_{{$table}}_{{$tplName}} extends MvcSkel_View_Table {
    /**
     * Constructor.
     */
    function View_{{$table}}_{{$tplName}}() {
        parent::MvcSkel_View_Table();	
        $this->columns = array({{section name="field" loop=$properties}}'{{$properties[field]->name}}' => array('db_name'=>'{{$properties[field]->name}}'),
                               {{/section}});
        
        $this->searchColumns = array({{section name="field" loop=$properties}}'{{$properties[field]->name}}',
                                     {{/section}});

        $object = new Model_{{$table}}();
        $this->initialize($object);
    }

    /**
     * @see PhpSkel_View::prepare()
     */
    function prepare() {
        parent::prepare();
        $this->smarty->assign('title', '{{$table}} list');
    }
}
