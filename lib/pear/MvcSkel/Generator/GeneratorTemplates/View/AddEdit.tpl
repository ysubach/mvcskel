<?php
/**
 * View for editing of {{$table}}.
 * @package view
 * @subpackage {{$table}}
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
 * View for editing of {{$table}}.
 * @package view
 * @subpackage {{$table}}
 */
class View_{{$table}}_{{$tplName}} extends MvcSkel_View {
    /**
     * Constructor.
     */
    function View_{{$table}}_{{$tplName}}() {
        $this->template = '{{$table}}/{{$tplName}}.tpl';
    }

    /**
     * @see PhpSkel_View::prepare()
     */
    function prepare() {
        parent::prepare();
        $mec = new MvcSkel_ModelEditCache('{{$table}}', 
            $_REQUEST['id'], isset($_REQUEST['new']));
        $this->smarty->assign('title', 'Add/Edit {{$table}}');
        $this->smarty->assign('object', $mec->get());
    }
}
