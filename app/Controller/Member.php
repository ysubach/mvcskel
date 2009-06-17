<?php
/**
* Test controller.
*/
class Controller_Member extends MvcSkel_Controller {
    public function __construct() {
        $this->addFilter(new MvcSkel_Filter_Auth(array('User', 'Administrator')));
    }

    /**
     * @todo complete profile
     * @todo upload avatar
     */
    public function actionIndex() {
        return 'profile page';
    }
}
?>