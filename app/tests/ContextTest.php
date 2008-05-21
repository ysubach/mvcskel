<?php
/**
 * Tests context class.
 * 
 * @package tests
 */
 
/**
 * Require PHP units.
 */ 
require_once 'PHPUnit.php';

/**
 * Context class test.
 * 
 * @package tests
 */
class ContextTest extends PHPUnit_TestCase {
    function testGetFileName() {
        $context = new MvcSkel_Context('User/AddEdit');
        $this->assertEquals($context->getFileName('view'), './View/User/AddEdit.php');
    }
    function testGetClassName() {
        $context = new MvcSkel_Context('User/AddEdit');
        $this->assertEquals($context->getClassName('view'), 'View_User_AddEdit');
    }
    
    function suite() {
        return new PHPUnit_TestSuite('ContextTest');
    }
}
?>