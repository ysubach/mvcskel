<?php

/**
 * Static page filter (*.html)
 */
class MvcSkel_Filter_StaticPage extends MvcSkel_Filter {

    /**
     * Redirect *.html to Controller_Static
     */
    public function filter() {
        $r = preg_match('/(\w+)\.html$/', $_REQUEST['mvcskel_c'], $matches);
        if ($r > 0) {
            $_REQUEST['mvcskel_c'] = 'StaticPage';
            $_REQUEST['mvcskel_a'] = 'Render';
            $_REQUEST['page'] = $matches[1];
        }
        return true;
    }

}

?>
