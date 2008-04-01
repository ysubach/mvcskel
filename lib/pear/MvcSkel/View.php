<?php
/**
* View base class.
*
* PHP versions 4 and 5
*
* @category   framework
* @package    MvcSkel
* @copyright  2007, Whirix Ltd.
* @license    http://www.gnu.org/licenses/lgpl.html GNU Lesser Public General License (LGPL).
* @link       http://trac.whirix.com/mvcskel
*/

/**
* Include Error Manager.
*/
require_once 'MvcSkel/ErrorManager.php';

/**
 * View base class.
 * @package    MvcSkel
 */
class MvcSkel_View {
    /** Smarty template file name */
    var $masterTemplate = 'master.tpl';
	
    /** Smarty body template file name */
    var $template = '';
    
	/** Smarty template instance */
	var $smarty;
	
	/**	Flag: has the view been prepared for rendering */
	var $prepared = false;
	
	/**
	 * Constructor
     *
     * @param $smarty Smarty instance
	 * @return void
	 */
	function init(&$smarty) {
		$this->smarty =& $smarty;
	}

    /**
     * Prepare smarty object for display
     *
     * @return void
     */
    function prepare() {
        $auth = &PEAR::getStaticProperty('MvcSkel', 'auth');
        $this->smarty->assign('__currentUser', $auth->getUser());
        $this->smarty->assign('__auth', $auth);
        $this->smarty->assign('bodyTemplate', $this->template);
        $this->prepared = true;
    }

	/**
	 * Render smarty template
     *
	 * @return void
	 */
	function render() {	
		if (!$this->prepared) {
            $this->prepare();
        }
		
		$this->processErrors();
		$this->smarty->display($this->masterTemplate);
	}

	/**
	 * Catch all errors for the page and put them into Smarty
	 * object. ErrorManager used here.
	 *
	 * @return void
	 */
	function processErrors() {
        $this->smarty->assign('errors', MvcSkel_ErrorManager::getErrors());
        MvcSkel_ErrorManager::clear();
	}
}

