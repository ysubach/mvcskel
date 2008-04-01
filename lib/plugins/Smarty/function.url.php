<?php
require_once 'MvcSkel/UrlFactory/View.php';
require_once 'MvcSkel/UrlFactory/Action.php';
/**
 * Build links in smarty templates.
 */
function smarty_function_url($params, &$smarty) {
    if (isset($params['_factory'])) {
        $urlFactory = $params['_factory'];
        unset($params['_factory']);
    } else {
        $urlFactory = 'View';
    }
    $urlFactory = 'MvcSkel_UrlFactory_' . $urlFactory;
    $factory = &new $urlFactory;
    return $factory->_smartyMakeUrl($params, $smarty);
}
