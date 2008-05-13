<?php
/**
* Index file.
*
* PHP versions 5
*
* @category   framework
* @package    MvcSkel
* @copyright  2008, Whirix Ltd.
* @license    http://www.gnu.org/licenses/lgpl.html GNU Lesser Public General License (LGPL).
* @link       http://code.google.com/p/mvcskel/
*/

// set PATH to project related PEAR installation
// and to 
$p = array('lib/pear/MvcSkel', 'lib/pear', 'lib', ini_get('include_path'));
ini_set('include_path', join(PATH_SEPARATOR, $p));

/**
* Base framework class.
*/
require_once 'MvcSkel.php';

/**
* Resolve the problem of default page.
*/
require_once 'MvcSkel/Filter/DefaultPage.php';

$mvcskel = new MvcSkel();
$mvcskel->addFilter(new MvcSkel_Filter_DefaultPage('Main', 'Index'));
$mvcskel->run();
?>
