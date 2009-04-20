<?php
/**
 * MvcSkel table view helper implementation for Doctrine.
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
 * Table view helper for SQL query.
 * Helper implemented using standard SQL query as data source. This query
 * is executed through Doctrine library but it's not critical requirement,
 * any other library for database access works fine here.
 *
 * @package    MvcSkel
 * @subpackage Helper
 */
class MvcSkel_Helper_SqlTableView extends MvcSkel_Helper_TableView {
    /**
     * Basic SQL query text, must be defined by child classes.
     * Do not include "ORDER BY" and "LIMIT" instruction because they
     * are added automatically when query executed.
     */
    protected $sqlQuery;

    /** Sorting column */
    protected $sqlSortCol;
    
    /** Sorting direction */
    protected $sqlSortDir;

    /**
     * Implementation specific for SQL query
     */
    protected function getObjects($offset=null, $limit=null) {
        $res = $this->getResult();
        $start = 0;
        $end = count($res);
        if ($offset) {
            $start = $offset;
        }
        if ($limit) {
            $end = $start + $limit;
        }
        $data = array();
        for ($i=$start; $i<$end; $i++) {
            $data[] = $res[$i];
        }
        return $data;
    }

    /**
     * Implementation specific for SQL query
     */
    protected function countObjects() {
        $res = $this->getResult();
        return count($res);
    }

    /**
     * Retrieve result records from database.
     */
    protected function getResult() {
        $con = Doctrine_Manager::getInstance()->connection();
        $q = $this->sqlQuery;
        if ($this->sqlSortCol!=null) {
            $q .= " ORDER BY {$this->sqlSortCol} {$this->sqlSortDir}";
        }
        $st = $con->execute($q);
        return $st->fetchAll();
    }

    /**
     * Implementation specific for SQL query
     */
    protected function setSorting($col, $dir) {
        $this->sqlSortCol = $col;
        $this->sqlSortDir = $dir;
    }
}
?>
