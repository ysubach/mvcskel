<?php
/**
 * MvcSkel table view helper.
 * 
 * PHP versions 5
 *
 * @category   framework
 * @package    MvcSkel
 * @copyright  2008, Whirix Ltd.
 * @license    http://www.gnu.org/licenses/lgpl.html GNU Lesser Public General License (LGPL).
 * @link       http://code.google.com/p/mvcskel/
 */

require_once 'MvcSkel/Helper/Config.php';

/**
* Table view helper.
* Retrieve list of objects from datasource and assign them to Smarty instance
* for rendering. Support paging of the list and sorting by different columns.
*
* @package    MvcSkel
* @subpackage Helper
*/
abstract class MvcSkel_Helper_TableView {
    /** Total number of objects */
    protected $count = 0;

    /** List of object read from datasource */
    protected $objects = array();
    
    /**
    * Get list of objects from datasource.
    * Child classes HAVE TO override this method w/ own implementation.
    * @return array Fetched objects.
    */
    abstract protected function getObjects();
    
    /**
    * Count objects in the database.
    * Child classes HAVE TO override this method w/ own implementation.
    * @return int Count of objects.
    */
    abstract protected function countObjects();

    /** 
    * Assign value in Smarty object - main entry point
    * @param object $smarty Values assigned here
    */
    public function assignValues($smarty) {
        $this->objects = $this->getObjects();
        
        $smarty->assign('objects', $this->objects);
        //return "mvcskel_form_".$this->id;
    }
}
?>
