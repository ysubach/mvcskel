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
    
    /** Current page */
    protected $page = 0;
    
    /** Count of objects per page */
    protected $pageSize = 25;
    
    /** Pager enable flag */
    protected $pagerEnabled = true;

    /**
    * Get list of objects from datasource.
    * Child classes HAVE TO override this method w/ own implementation.
    * @param integer $offset Shows which object from the full list must
    *                   be returned, null means start from first
    * @param integer $limit Shows maximum count of objects from the full 
                        list to be returned, null means all
    * @return array Fetched objects.
    */
    abstract protected function getObjects($offset=null, $limit=null);
    
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
        $this->processRequest();
        $this->count = $this->countObjects();
        if ($this->pagerEnabled) {
            $offset = $this->page * $this->pageSize;
            $this->objects = $this->getObjects($offset, $this->pageSize);
            $this->assignPagerValues($smarty);
        } else {
            $this->objects = $this->getObjects();
        }
        $smarty->assign('objects', $this->objects);
    }
    
    /**
    * Process values from request
    */
    protected function processRequest() {
        if (isset($_REQUEST['page'])) {
            $this->setPage($_REQUEST['page']);
        }
    }
    
    /**
    * Assign Smarty values for pager rendering
    */
    protected function assignPagerValues(&$smarty) {
        $pager = array();
        $maxPage = floor($this->count/$this->pageSize);
        if ($this->count%$this->pageSize>0) {
            $maxPage++;
        }
        // prev link
        if ($this->page>0) {
            $pager[] = array('page' => $this->page-1, 'text' => 'prev');
        }
        // middle links
        $pstart = 0;
        $pend = $maxPage;
        $shrinkMode = false;
        if ($maxPage>16) {
            $shrinkMode = true;
            $pstart = $this->page-8;
            if ($pstart<0) {
                $pstart = 0;
            } else {
                $pager[] = array('page' => -1, 'text' => '...');
            }
            $pend = $pstart+16;
            if ($pend>$maxPage) {
                $pend = $maxPage;
            }
        }
        for ($p=$pstart; $p<$pend; $p++) {
            $pager[] = array('page' => $p, 'text' => $p+1);
        }
        if ($shrinkMode && $pend<$maxPage) {
            $pager[] = array('page' => -1, 'text' => '...');
        }
        // next link
        if ($this->page<($maxPage-1)) {
            $pager[] = array('page' => $this->page+1, 'text' => 'next');
        }
        $smarty->assign('pager', $pager);
        $smarty->assign('currentPage', $this->page);
    }
    
    /** Enable/disable pager */
    public function pagerEnable($flag) {
        $this->pagerEnabled = $flag;
    }

    /** Set current page number */
    public function setPage($page) {
        $this->page = $page;
    }
    
    /** Set current page size */
    public function setPageSize($size) {
        $this->pageSize = $size;
    }
}
?>
