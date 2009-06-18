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

/**
 * Use PEAR package for creating of excel output.
 */
require_once "Spreadsheet/Excel/Writer.php";

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
     * Columns used for sorting, hash map with sort identifier as key
     * and real field name used in datasource
     */
    protected $sortColumns;

    /** Sorting enable flag */
    protected $sortEnabled = false;

    /** Current sort column */
    protected $sortCol;

    /** Current sort dir */
    protected $sortDir;

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
     * Enable sorting for datasource.
     * Default implementation is empty.
     * @param string $sortColumn Datasource name of column to be used,
     *                   taken from $sortColumns hash map
     * @param string $sortDirection Direction of sorting: 'asc', 'desc'
     */
    protected function setSorting($sortColumn, $sortDirection) {}

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
        if ($this->sortEnabled) {
            $dsCol = $this->sortColumns[$this->sortCol];
            $this->setSorting($dsCol, $this->sortDir);
            $smarty->assign('sortCol', $this->sortCol);
            $smarty->assign('sortDir', $this->sortDir);
        }
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
     * Export data into Excel format.
     * Method sends all headers and necessary data to client, that's why
     * any other output to browser must be stopped after it.
     */
    public function exportToExcel() {
        // prepare data
        $this->processRequest();
        if ($this->sortEnabled) {
            $dsCol = $this->sortColumns[$this->sortCol];
            $this->setSorting($dsCol, $this->sortDir);
        }
        $objects = $this->getObjects();

        // generate excel
        $xls = new Spreadsheet_Excel_Writer();
        $xls->send("cft-report.xls");
        $sheet = $xls->addWorksheet('CFT Report');
        for ($i=0; $i<count($objects); $i++ ) {
            $row = $objects[$i];
            $j = 0;
            while (isset($row[$j])) {
                $sheet->write($i, $j, trim($row[$j]));
                $j++;
            }
        }
        $xls->close();
    }

    /**
    * Process values from request
    */
    protected function processRequest() {
        if (isset($_REQUEST['page'])) {
            $this->setPage($_REQUEST['page']);
        }
        if ($this->sortEnabled) {
            if (isset($_REQUEST['sortCol']) &&
                array_key_exists($_REQUEST['sortCol'], $this->sortColumns)) {
                $this->sortCol = $_REQUEST['sortCol'];
            }
            if (isset($_REQUEST['sortDir']) &&
                ($_REQUEST['sortDir']=='asc' || $_REQUEST['sortDir']=='desc')) {
                $this->sortDir = $_REQUEST['sortDir'];
            }
        }
    }

    /**
    * Assign Smarty values for pager rendering
    */
    protected function assignPagerValues(&$smarty) {
        $pager = array();
        $maxPage = ceil($this->count/$this->pageSize);
        // prev link
        if ($this->page>0) {
            $pager['prev'] = array('page' => $this->page-1, 'text' => 'prev');
        }

        // middle links
        $pstart = 0;
        $pend = $maxPage;
        
        for ($p=$pstart; $p<$pend; $p++) {
            $pager['pages'][$p] = $p+1;
        }

        // next link
        if ($this->page<($maxPage-1)) {
            $pager['next'] = array('page' => $this->page+1, 'text' => 'next');
        }
        $smarty->assign('pager', $pager);
        $smarty->assign('pagesTotal', count($pager['pages']));
        $smarty->assign('currentPage', $this->page);
        $smarty->assign('currentPageText', $this->page+1);
        $smarty->assign('pagerTotalCount', $this->count);
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

    /** Set sorting columns */
    public function setSortColumns($sc) {
        $this->sortColumns = $sc;
        if (count($sc)>0) {
            $this->sortEnabled = true;
            $sortIds = array_keys($this->sortColumns);
            $this->sortCol = $sortIds[0]; // default column
            $this->sortDir = 'asc';       // default direction
        }
    }
}
?>
