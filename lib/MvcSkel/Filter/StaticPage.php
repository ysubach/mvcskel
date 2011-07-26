<?php
/**
* Static page filter
*/ 
class MvcSkel_Filter_StaticPage extends MvcSkel_Filter {

    /**
     * Redirect *.html to Controller_Static
     */
    public function filter() {
        $ctrl = $_REQUEST['mvcskel_c'];
        if (class_exists("Controller_$ctrl")) {
            return true;
        }
        if (file_exists("app/templates/Static/$ctrl.tpl")) {
            $_REQUEST['mvcskel_c'] = 'StaticPage';
            $_REQUEST['mvcskel_a'] = 'Render';
            $_REQUEST['page'] = $ctrl;
        }
        return true;
    }
}
?>
