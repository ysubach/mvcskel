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
$p = array('app', 'lib', 'lib/pear', 'lib/misc', 'lib/smarty', 
    'lib/doctrine/lib', ini_get('include_path'));
ini_set('include_path', join(PATH_SEPARATOR, $p));

/**
* Base framework class.
*/
require_once 'MvcSkel/Runner.php';

$mvcskel = new MvcSkel_Runner();
$mvcskel->addFilter(new MvcSkel_Filter_DefaultPage('Main', 'Index'));
$mvcskel->run();
?>
