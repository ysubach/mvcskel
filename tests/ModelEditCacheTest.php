<?php
require_once 'PHPUnit.php';
require_once 'PHPUnit/TestSuite.php';
require_once 'DataobjectTestSetup.php';
require_once 'ModelEditCache.php';
require_once 'Model/User.php';

class ModelEditCacheTest extends PHPUnit_TestCase {
    function testStoragingIntoCache() {
    	$mec = new MvcSkel_ModelEditCache('User', null, true);
        $user1 =& $mec->get();        
        $user1->login = 'TestLogin';
        $id = $user1->insert();
        $mec = new MvcSkel_ModelEditCache('User', $id, false);
        $user2 =& $mec->get();     
        $this->assertSame($user1, $user2);

        $mec = new MvcSkel_ModelEditCache('User');
        $user2 =& $mec->get();
        $this->assertSame($user1, $user2);

        $mec = new MvcSkel_ModelEditCache('User', $id, true);
        $user2 =& $mec->get();
        $this->assertNotSame($user1, $user2);
        $this->assertEquals($user1->login, $user2->login);
    }
        
    function suite() {
        return new DataobjectTestSetup(new PHPUnit_TestSuite('ModelEditCacheTest'));
    }
}
?>
