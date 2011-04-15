<?php
require_once '../lib/doctrine/lib/Doctrine.php';

spl_autoload_register(array('Doctrine', 'autoload'));
Doctrine_Manager::connection('mysql://root:@localhost/mvcskel');

// import method takes one parameter : the import directory ( the directory where
// the generated record files will be put in
Doctrine::generateModelsFromDb('../app/Model') ;
?>