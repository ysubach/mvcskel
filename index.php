<?php
/**
* Index file. Process views.
*
* PHP versions 4 and 5
*
* @category   framework
* @package    MvcSkel
* @author     Vyacheslav Iutin <iutin@whirix.com>
* @copyright  2007, Whirix Ltd.
* @license    http://www.gnu.org/licenses/lgpl.html GNU Lesser Public General License (LGPL).
* @link       http://mvcskel.whirix.com
*/

// set PATH to locally installed PEAR
// you can remove this set path block if you use
// server installed pear
$cp = realpath(dirname(__FILE__));
$p = array();
$p[] = $cp . '/lib/Smarty';
$p[] = $cp . '/lib/pear';
$p[] = ini_get('include_path');
ini_set('include_path', join(PATH_SEPARATOR, $p));

require_once 'MvcSkel/Configurator.php';

MvcSkel_Configurator::configure($cp, 'tmp');
require_once 'MvcSkel.php';

session_start();
MvcSkel_Configurator::startAuth();
MvcSkel::run();
?>
