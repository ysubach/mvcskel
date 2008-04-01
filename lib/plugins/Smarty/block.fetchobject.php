<?php
/**
 * Call fetch function for object
 */
function smarty_block_fetchobject($params, $content, &$smarty, &$repeat) {
    if (!isset($params['name'])) {
        $smarty->trigger_error('Can\'t get required parameter name');
    } else {
        $obj = &$smarty->get_template_vars($params['name']);
        $repeat = $obj->fetch();
    }
    return $content;
}
?>
