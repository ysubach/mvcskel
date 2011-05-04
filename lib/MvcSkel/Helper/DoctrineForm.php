<?php

/**
 * MvcSkel doctrine  form helper.
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
 * Doctrine object form processing helper.
 *
 * Save doctine objects.
 *
 * @package    MvcSkel
 * @subpackage Helper
 */
abstract class MvcSkel_Helper_DoctrineForm extends MvcSkel_Helper_Form {
    /**
     * Fill form object's fields from request.
     * Anyway, the default method is trivial, you will have to override it
     * in most cases.
     */
    protected function fillByRequest() {
        $R = $_REQUEST;
        $o = $this->getObject();
        $o->cleanData($R);
        $o->setArray($R);
        $this->setObject($o);
    }

    /**
     * Performs action, called after successful validation.
     * Anyway, the action is trivial, you will have to override it
     * in most cases.
     */
    protected function action() {
        $o = $this->getObject();
        $o->save();
    }

}

?>
