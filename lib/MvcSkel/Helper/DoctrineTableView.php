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
 * Table view helper for Doctrine.
 * Helper implemented using Doctrine query object as data source.
 *
 * @package    MvcSkel
 * @subpackage Helper
 */
class MvcSkel_Helper_DoctrineTableView extends MvcSkel_Helper_TableView {
    /**
     * Object of type Doctrine_Query which is used as data source.
     * This object is declared but not initialized in this class. Child
     * classes have to create and initialize $query object according to
     * specific requirements.
     */
    protected $query;

    /**
     * Implementation specific for Doctrine query
     */
    protected function getObjects($offset=null, $limit=null) {
        if ($offset) {
            $this->query->offset($offset);
        }
        if ($limit) {
            $this->query->limit($limit);
        }
        return $this->query->execute()->getData();
    }

    /**
     * Implementation specific for Doctrine query
     */
    protected function countObjects() {
        return $this->query->count();
    }

    /**
     * Implementation specific for Doctrine query
     */
    protected function setSorting($col, $dir) {
        $this->query->orderBy("$col $dir");
    }
}
?>
