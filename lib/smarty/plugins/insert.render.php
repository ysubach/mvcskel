<?php
/**
 * Avoid caching of user name in header.
 */
function smarty_insert_render($params, &$smarty) {
    $newSmarty = clone $smarty;
    $newSmarty->caching = 0;
    return $newSmarty->fetch($params['file']);
}
