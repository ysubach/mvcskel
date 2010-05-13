<?php
/**
* Static page filter (*.html)
*/ 
class Filter_StaticPage extends MvcSkel_Filter {
    /**
     * Redirect *.html to Controller_Static
     */
    public function filter() {
        $r = preg_match('/(\w+)\.html$/', $_REQUEST['mvcskel_c'], $matches);
        if ($r>0) {
            $_REQUEST['mvcskel_c'] = 'Static';
            $_REQUEST['mvcskel_a'] = 'Render';
            $_REQUEST['page'] = $matches[1];
        }
        return true;
    }
}
?>
