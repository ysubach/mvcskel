<?php
require_once 'MvcSkel/Controller.php';

/**
* Test controller.
*/
class Controller_Main extends MvcSkel_Controller {
	public function actionIndex() {
		return 'test action is working';
	}
}
?>