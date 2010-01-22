<?php

/** Implementation of regions list view */
class Helper_UsersList extends MvcSkel_Helper_DoctrineTableView {
    /** C-tor */
    public function __construct() {
        $this->query = Doctrine_Query::create()
            ->select('*')
            ->from('User u');

        $this->setSortColumns(
            array(
                'id'=>'u.id',
                'un'=>'u.username',
                'fn'=>'u.fname',
                'em'=>'u.email'
            ));
    }
}
?>
