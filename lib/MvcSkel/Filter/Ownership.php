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
    private $links;

    /**
     * C-r.
     * Usage:
     * entity Restaurant belong to entity User
     * <code>
     * new MvcSkel_Filter_Ownership('Restaurant');
     * </code>
     * it will check if current user try to access his restaurant.
     * More complex case (develop the case above):
     * entity MenuCategory belong to Restaurant (which belong to User)
     * <code>
     * new MvcSkel_Filter_Ownership(array('MenuCategory', 'Restaurant'));
     * </code>
     * Thus you give a way to get User entity (owner). Just to check
     * if user access it's own record:
     * <code>
     * new MvcSkel_Filter_Ownership(array());
     * </code>
     * @param string | array $model model to check the entity
     */
    public function __construct($links) {
        if (!is_array($links)) {
            $links = array($links);
        }
        $this->links = $links;
    }

    /**
     * Check userId of the got model object.
     */
    public function filter() {
        // it is not an access to a model, nothing todo
        if (!isset($_REQUEST['id']) || empty($_REQUEST['id'])) {
            return true;
        }

        $auth = new MvcSkel_Helper_Auth();

        // admin is allowed to do everything
        if ($auth->checkRole('Administrator')) {
            return true;
        }

        $user = $auth->getUser();

        // case when we check User entity itself
        if (!count($this->links)) {
            $obj = Doctrine::getTable('User')->find($_REQUEST['id']);
            return $obj->id==$user->id;
        }
        
        // in case we call filter twice (by some strange reason)
        $copyLinks = $this->links;

        $first = array_shift($copyLinks);
        $obj = Doctrine::getTable($first)->find($_REQUEST['id']);
        // follow by links
        foreach ($copyLinks as $modelName) {
            $obj = $obj->$modelName;
        }

        return $obj->User->id==$user->id;
    }
}
?>
