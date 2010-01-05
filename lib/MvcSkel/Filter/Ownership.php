<?php
/**
 * MvcSkel entity ownership check.
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
 * Check ownership of gived object.
 * Return ok (pass request) if no 'id' in request
 * @category   framework
 * @package    MvcSkel
 * @subpackage    Filter
 */
class MvcSkel_Filter_Ownership extends MvcSkel_Filter {
    private $model;

    /**
     * C-r.
     * @param string $model model to check the entity
     */
    public function __construct($model) {
        $this->model = $model;
    }

    /**
     * Check userId of the got model object.
     */
    public function filter() {
        if (!isset($_REQUEST['id']) || empty($_REQUEST['id'])) {
            return true;
        }
        
        $auth = new MvcSkel_Helper_Auth();
        $user = $auth->getUser();

        $q = Doctrine_Query::create()
            ->from("{$this->model} obj")
            ->where('obj.userId = ? AND obj.id = ?', array($user->id, $_REQUEST['id']));

        return $q->count()>0;
    }
}
?>
