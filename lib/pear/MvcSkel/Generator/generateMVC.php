#!/usr/local/php4/bin/php -f
<?php
// you can remove this set path block if you use
// server installed pear
$cp = dirname(__FILE__) . '/../../../../';
$p = array();
$p[] = $cp . '/lib/Smarty';
$p[] = $cp . '/lib/pear';
$p[] = ini_get('include_path');
ini_set('include_path', join(PATH_SEPARATOR, $p));

require_once 'MvcSkel/Configurator.php';

MvcSkel_Configurator::configure(dirname(__FILE__) . '/../../../..', '../../../../tmp');

require_once 'MvcSkel/Generator.php';

$generator = new MvcSkel_Generator();
$generator->start();
?>
